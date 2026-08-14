<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * No-op `JobEnqueuer` so a tenant who upgrades between T3.4 and T3.5
 * doesn't fatal-error when a webhook tries to queue an auto-convert
 * job. Replaced by `JobQueue` in T3.5.
 */
final class NullJobEnqueuer implements JobEnqueuer
{
    public function enqueue(string $type, int $storeId, array $payload): void
    {
        // Intentionally a no-op — the real queue lands in T3.5.
    }
}
