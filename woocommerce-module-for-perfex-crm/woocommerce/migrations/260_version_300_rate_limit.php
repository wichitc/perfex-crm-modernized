<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * v3.0.0 — per-store token-bucket rate limiter persistence.
 *
 * One row per store, mutated in place. Schema is intentionally tiny:
 *   - store_id PRIMARY KEY (one bucket per store)
 *   - tokens   DOUBLE (fractional refill is meaningful at sub-second granularity)
 *   - updated_at DATETIME (the bucket's last drain/refill point)
 *
 * Note on numbering: BUILD_PLAN T3.5 originally reserved migration 260
 * for the background job queue. T2.6 (RateLimiter) needs a persistent
 * bucket and lands first, so this migration takes 260 and T3.5 will
 * land at 270 when it ships.
 *
 * Spec refs: §13 (rate limit), BUG-202.
 */
class Migration_Version_300_rate_limit extends App_module_migration
{
    public function up(): void
    {
        $CI =& get_instance();
        $prefix = db_prefix();

        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}woocommerce_rate_limit` (
                `store_id`   INT UNSIGNED NOT NULL,
                `tokens`     DOUBLE NOT NULL DEFAULT 0,
                `updated_at` DATETIME NOT NULL,
                PRIMARY KEY (`store_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        $CI =& get_instance();
        $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'woocommerce_rate_limit`');
    }
}
