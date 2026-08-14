<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\StoreDTO;

/**
 * Per-store sync interface called by the cron orchestrator. Splitting
 * `summary / checkOrders / checkProducts / checkCustomers` into named
 * methods (rather than a generic `tick()`) lets the orchestrator log
 * which step ran for which store and lets a single failing step fail
 * in isolation without skipping the others.
 *
 * The skeleton lives in this file as an interface so T3.1 can land
 * the orchestrator + lock with a passthrough no-op SyncService stub.
 * T3.2 fills in the real implementation.
 */
interface SyncService
{
    public function summary(StoreDTO $store): void;

    public function checkOrders(StoreDTO $store): void;

    public function checkProducts(StoreDTO $store): void;

    public function checkCustomers(StoreDTO $store): void;
}
