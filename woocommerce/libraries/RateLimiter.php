<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

/**
 * Per-store token-bucket rate limiter, persisted in
 * `tblwoocommerce_rate_limit`. One row per store; each row carries
 * the current token count and the last update timestamp.
 *
 * `acquire()` drains tokens and refills based on elapsed wall-clock
 * since the last call; if the bucket lacks tokens the call returns
 * false and the caller decides what to do (most controllers ack 200
 * with a `rate_limited` audit row so Woo doesn't retry forever).
 *
 * The clock is injectable so tests can simulate elapsed time without
 * sleeping.
 *
 * Spec refs: §13, BUG-202.
 */
final class RateLimiter
{
    /** @var object */
    protected object $db;
    protected string $table;
    protected float $capacity;
    protected float $refillRatePerSecond;
    /** @var callable(): float */
    private $clock;

    /**
     * @param callable(): float|null $clock returns a unix timestamp w/ microseconds
     */
    public function __construct(
        object $db,
        float $capacity = 60.0,
        float $refillRatePerSecond = 1.0,
        ?callable $clock = null,
        string $tablePrefix = 'tbl',
    ) {
        $this->db                  = $db;
        $this->table               = $tablePrefix . 'woocommerce_rate_limit';
        $this->capacity            = max(1.0, $capacity);
        $this->refillRatePerSecond = max(0.0, $refillRatePerSecond);
        $this->clock               = $clock ?? static fn(): float => microtime(true);
    }

    /**
     * Try to take `$tokens` tokens from the store's bucket. Returns
     * true if the take succeeded, false if the bucket would have gone
     * negative (caller decides what to do).
     */
    public function acquire(int $storeId, int $tokens = 1): bool
    {
        $now    = ($this->clock)();
        $bucket = $this->loadBucket($storeId);

        if ($bucket === null) {
            $tokensAfter = max(0.0, $this->capacity - (float) $tokens);
            $this->saveBucket($storeId, $tokensAfter, $now, /*isNew=*/ true);
            return $tokens <= $this->capacity;
        }

        $elapsed     = max(0.0, $now - $bucket['updated_at']);
        $afterRefill = min($this->capacity, $bucket['tokens'] + $elapsed * $this->refillRatePerSecond);

        if ($afterRefill < $tokens) {
            // Persist the refilled count so the next call sees the
            // up-to-date bucket — even when this call is rejected.
            $this->saveBucket($storeId, $afterRefill, $now);
            return false;
        }

        $remaining = $afterRefill - (float) $tokens;
        $this->saveBucket($storeId, $remaining, $now);

        return true;
    }

    /**
     * @return array{tokens:float, updated_at:float}|null
     */
    private function loadBucket(int $storeId): ?array
    {
        $row = $this->db->where('store_id', $storeId)
            ->limit(1)
            ->get($this->table)
            ->row_array();

        if (! is_array($row) || $row === []) {
            return null;
        }

        return [
            'tokens'     => (float) $row['tokens'],
            'updated_at' => (float) strtotime((string) $row['updated_at']),
        ];
    }

    private function saveBucket(int $storeId, float $tokens, float $now, bool $isNew = false): void
    {
        $row = [
            'tokens'     => $tokens,
            'updated_at' => date('Y-m-d H:i:s', (int) $now),
        ];

        if ($isNew) {
            $row['store_id'] = $storeId;
            $this->db->insert($this->table, $row);
            return;
        }

        $this->db->where('store_id', $storeId)
            ->update($this->table, $row);
    }
}
