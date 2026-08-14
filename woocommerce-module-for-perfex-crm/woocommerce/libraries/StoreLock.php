<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

/**
 * Per-store advisory lock backed by MySQL `GET_LOCK` / `RELEASE_LOCK`.
 *
 * Purpose: when Perfex's cron runs more often than a tick can complete
 * (a slow first sync, an overloaded Woo store, etc.) two ticks could
 * land on the same store concurrently and double-upsert. The lock
 * makes the second tick's `acquire()` return `false` immediately so it
 * skips that store.
 *
 * Locks are session-scoped — they release automatically when the
 * connection closes, so a crashed cron process can't permanently wedge
 * a store. We still call `release()` on the success path to free the
 * lock the moment we no longer need it.
 *
 * Spec refs: §6.4.
 */
class StoreLock
{
    private object $db;
    /** @var array<int, true> store_ids the current process holds */
    private array $held = [];

    public function __construct(object $db)
    {
        $this->db = $db;
    }

    /**
     * Try to acquire the per-store lock without waiting. Returns true
     * iff this process is now the holder.
     */
    public function acquire(int $storeId): bool
    {
        if (isset($this->held[$storeId])) {
            // Re-entrant: this process already holds it. MySQL would
            // return 1 for a re-take, but we short-circuit so the
            // counter stays accurate.
            return true;
        }

        $name = $this->lockName($storeId);
        $row  = $this->db->query('SELECT GET_LOCK(?, 0) AS got', [$name])->row_array();

        $got = is_array($row) && (int) ($row['got'] ?? 0) === 1;
        if ($got) {
            $this->held[$storeId] = true;
        }
        return $got;
    }

    public function release(int $storeId): void
    {
        if (! isset($this->held[$storeId])) {
            return;
        }

        $this->db->query('SELECT RELEASE_LOCK(?) AS released', [$this->lockName($storeId)]);
        unset($this->held[$storeId]);
    }

    /**
     * Run a closure with the store's lock held. Returns null when the
     * lock was already taken by another process — the caller's
     * orchestrator treats null as "skip this store this tick".
     */
    public function withLock(int $storeId, \Closure $body): mixed
    {
        if (! $this->acquire($storeId)) {
            return null;
        }

        try {
            return $body();
        } finally {
            $this->release($storeId);
        }
    }

    private function lockName(int $storeId): string
    {
        // MySQL caps GET_LOCK names at 64 chars and is case-insensitive.
        // Prefixing with the module name keeps namespacing collision-free
        // when other Perfex code uses GET_LOCK on the same connection.
        return 'woocommerce_cron_store_' . $storeId;
    }
}
