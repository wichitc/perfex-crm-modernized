<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use Throwable;
use WooCommerce\Repositories\JobsRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Services\JobEnqueuer;
use WooCommerce\Services\JobHandler;

/**
 * Background job queue. Implements `JobEnqueuer` so the webhook
 * dispatcher can `enqueue()` follow-up work without knowing the queue
 * is here, and exposes `processQueue()` for the cron to drain pending
 * jobs at the end of each tick.
 *
 * Handler dispatch is a registry of `type → JobHandler` so adding a
 * new job type is a single registry call (no switch statement). Per-
 * handler failures are caught here and routed into JobsRepository's
 * retry path; the cron tick continues regardless.
 *
 * Spec refs: §16, §13.4.
 */
final class JobQueue implements JobEnqueuer
{
    /** @var array<string, JobHandler> */
    private array $handlers = [];

    public function __construct(
        private JobsRepository $repo,
        private LogRepository  $log,
    ) {
    }

    public function register(string $type, JobHandler $handler): void
    {
        $this->handlers[$type] = $handler;
    }

    public function enqueue(string $type, int $storeId, array $payload): void
    {
        $jobId = $this->repo->push($type, $storeId, $payload);

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'job.enqueued',
            ['job_id' => $jobId, 'type' => $type],
            $storeId,
            (string) ($payload['correlation_id'] ?? ''),
        );
    }

    /**
     * Drain up to `$maxJobs` pending jobs. Designed to run at the end
     * of every cron tick — bounded so a flood of jobs can't eat the
     * whole tick and starve the per-store sync loop.
     *
     * @return array{processed:int, failed:int, quarantined:int}
     */
    public function processQueue(int $maxJobs = 25): array
    {
        $stats = ['processed' => 0, 'failed' => 0, 'quarantined' => 0];

        for ($i = 0; $i < $maxJobs; $i++) {
            $job = $this->repo->claimNext();
            if ($job === null) {
                break;
            }

            $type     = (string) $job['type'];
            $storeId  = $job['store_id'] === null ? 0 : (int) $job['store_id'];
            $payload  = self::decodePayload((string) ($job['payload_json'] ?? '[]'));
            $attempts = (int) $job['attempts'];
            $maxA     = (int) $job['max_attempts'];

            $handler = $this->handlers[$type] ?? null;

            if ($handler === null) {
                $this->repo->markFailed((int) $job['id'], $attempts, $maxA, "no handler registered for type=$type");
                $stats['failed']++;
                if ($attempts >= $maxA) { $stats['quarantined']++; }
                continue;
            }

            try {
                $handler->handle($storeId, $payload);
                $this->repo->markDone((int) $job['id']);
                $stats['processed']++;
            } catch (Throwable $e) {
                $this->repo->markFailed((int) $job['id'], $attempts, $maxA, $e->getMessage());
                $stats['failed']++;
                if ($attempts >= $maxA) {
                    $stats['quarantined']++;
                }

                $this->log->write(
                    LogRepository::LEVEL_ERROR,
                    'job.handler_failed',
                    [
                        'job_id'     => (int) $job['id'],
                        'type'       => $type,
                        'attempts'   => $attempts,
                        'exception'  => $e::class,
                        'message'    => $e->getMessage(),
                    ],
                    $storeId,
                    (string) ($payload['correlation_id'] ?? ''),
                );
            }
        }

        return $stats;
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodePayload(string $json): array
    {
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
