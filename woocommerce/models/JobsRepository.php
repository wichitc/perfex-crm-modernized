<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

/**
 * `tblwoocommerce_jobs` — the queue table behind `JobQueue`.
 *
 * Concurrency: claimNext() uses an optimistic-update pattern
 * (UPDATE … WHERE id=? AND status='pending') so two cron processes
 * picking the same row race safely — only one's UPDATE will report
 * affected_rows = 1.
 */
class JobsRepository extends BaseRepository
{
    public const STATUS_PENDING     = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE        = 'done';
    public const STATUS_FAILED      = 'failed';
    public const STATUS_QUARANTINED = 'quarantined';

    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, $tablePrefix . 'woocommerce_jobs');
    }

    /**
     * Push a new pending job. Returns its id.
     *
     * @param array<string, mixed> $payload
     */
    public function push(string $type, ?int $storeId, array $payload, int $maxAttempts = 5): int
    {
        return $this->insert([
            'type'          => $type,
            'store_id'      => $storeId,
            'payload_json'  => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'status'        => self::STATUS_PENDING,
            'attempts'      => 0,
            'max_attempts'  => $maxAttempts,
            'scheduled_for' => date('Y-m-d H:i:s'),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Try to claim the oldest runnable pending job. Returns its row
     * with status already advanced to 'in_progress', or null when
     * nothing's runnable. Race-safe: a second process competing for
     * the same row will see `affected_rows = 0` and loop.
     *
     * @return array<string, mixed>|null
     */
    public function claimNext(): ?array
    {
        $candidate = $this->db
            ->where('status', self::STATUS_PENDING)
            ->where('scheduled_for <=', date('Y-m-d H:i:s'))
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get($this->table)
            ->row_array();

        if (! is_array($candidate) || ! isset($candidate['id'])) {
            return null;
        }

        $id = (int) $candidate['id'];

        $this->db
            ->where('id', $id)
            ->where('status', self::STATUS_PENDING)
            ->update($this->table, [
                'status'      => self::STATUS_IN_PROGRESS,
                'locked_at'   => date('Y-m-d H:i:s'),
                'last_run_at' => date('Y-m-d H:i:s'),
                'attempts'    => (int) $candidate['attempts'] + 1,
            ]);

        if ((int) $this->db->affected_rows() === 0) {
            // Lost the race — caller should loop.
            return null;
        }

        $candidate['attempts'] = (int) $candidate['attempts'] + 1;
        $candidate['status']   = self::STATUS_IN_PROGRESS;
        return $candidate;
    }

    public function markDone(int $jobId): void
    {
        $this->db->where('id', $jobId)->update($this->table, [
            'status'     => self::STATUS_DONE,
            'last_error' => null,
        ]);
    }

    /**
     * Record a failed attempt. If attempts < max_attempts, the job
     * goes back to 'pending' with an exponential backoff
     * (60s × 2^attempts). Otherwise it's quarantined and stops being
     * picked up.
     */
    public function markFailed(int $jobId, int $attempts, int $maxAttempts, string $error): void
    {
        if ($attempts >= $maxAttempts) {
            $this->db->where('id', $jobId)->update($this->table, [
                'status'     => self::STATUS_QUARANTINED,
                'last_error' => $error,
            ]);
            return;
        }

        $backoffSeconds = 60 * (2 ** ($attempts - 1));

        $this->db->where('id', $jobId)->update($this->table, [
            'status'        => self::STATUS_PENDING,
            'last_error'    => $error,
            'scheduled_for' => date('Y-m-d H:i:s', time() + $backoffSeconds),
        ]);
    }
}
