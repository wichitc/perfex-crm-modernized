<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use Throwable;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Services\SyncService;
use WooCommerce\Services\BaseApiClient;

/**
 * The per-tick loop the Perfex cron calls.
 *
 * For every active store:
 *  1. Try the per-store advisory lock (skip the store if another tick
 *     already holds it — see StoreLock).
 *  2. Run summary → checkOrders → checkProducts → checkCustomers.
 *  3. Catch every Throwable per-step so one bad store can't kill the
 *     whole tick. Errors land in `tblwoocommerce_log` with a fresh
 *     correlation id per store.
 *
 * No network, no DB, no SDK directly — every dependency is injected so
 * the orchestrator is unit-testable with mocks.
 *
 * Spec refs: §12, §6.4.
 */
final class CronOrchestrator
{
    private StoresRepository $stores;
    private StoreLock        $lock;
    private SyncService      $sync;
    private LogRepository    $log;

    public function __construct(
        StoresRepository $stores,
        StoreLock        $lock,
        SyncService      $sync,
        LogRepository    $log
    ) {
        $this->stores = $stores;
        $this->lock   = $lock;
        $this->sync   = $sync;
        $this->log    = $log;
    }

    /**
     * @return array{
     *     processed: list<int>,
     *     skipped_locked: list<int>,
     *     errored: array<int, list<string>>
     * }
     */
    public function tick(): array
    {
        $processed     = [];
        $skippedLocked = [];
        $errored       = [];

        foreach ($this->stores->listStores(activeOnly: true) as $store) {
            $storeId = (int) $store->storeId;

            if (! $this->lock->acquire($storeId)) {
                $skippedLocked[] = $storeId;
                $this->log->write(
                    LogRepository::LEVEL_INFO,
                    'cron.store_skipped_locked',
                    ['store_id' => $storeId],
                    $storeId,
                    BaseApiClient::generateCorrelationId(),
                );
                continue;
            }

            $correlationId = BaseApiClient::generateCorrelationId();

            try {
                $stepErrors = $this->runStore($store, $correlationId);
                if ($stepErrors !== []) {
                    $errored[$storeId] = $stepErrors;
                } else {
                    $processed[] = $storeId;
                }
            } finally {
                $this->lock->release($storeId);
            }
        }

        return [
            'processed'      => $processed,
            'skipped_locked' => $skippedLocked,
            'errored'        => $errored,
        ];
    }

    /**
     * Run all four sync steps for a store; isolate failures per step
     * so a broken one doesn't skip the others.
     *
     * @return list<string> step names that threw, in run order
     */
    private function runStore(\WooCommerce\Repositories\StoreDTO $store, string $correlationId): array
    {
        $errors = [];
        $storeId = (int) $store->storeId;

        $steps = [
            'summary'        => function () use ($store): void { $this->sync->summary($store); },
            'checkOrders'    => function () use ($store): void { $this->sync->checkOrders($store); },
            'checkProducts'  => function () use ($store): void { $this->sync->checkProducts($store); },
            'checkCustomers' => function () use ($store): void { $this->sync->checkCustomers($store); },
        ];

        foreach ($steps as $name => $step) {
            try {
                $step();
            } catch (Throwable $e) {
                $errors[] = $name;
                $this->log->write(
                    LogRepository::LEVEL_ERROR,
                    "cron.$name" . '_failed',
                    [
                        'exception' => $e::class,
                        'message'   => $e->getMessage(),
                        'file'      => $e->getFile() . ':' . $e->getLine(),
                    ],
                    $storeId,
                    $correlationId,
                );
            }
        }

        return $errors;
    }
}
