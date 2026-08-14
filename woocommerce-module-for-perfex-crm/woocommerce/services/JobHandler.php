<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * One JobHandler per `JobEnqueuer::JOB_*` type. The handler is the
 * code that actually does the slow work (convert.order_to_invoice
 * → calls OrderConverter::convert in Phase 5, etc.).
 */
interface JobHandler
{
    /**
     * @param array<string, mixed> $payload The exact payload that was enqueued.
     * @throws \Throwable on failure — caught by JobQueue::processOne and
     *                                  routed into the retry / quarantine path.
     */
    public function handle(int $storeId, array $payload): void;
}
