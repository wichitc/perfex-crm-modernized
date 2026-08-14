<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * v3.0.0 — background job queue for slow webhook follow-ups.
 *
 * The webhook handler always acks 200 in <500ms; anything that needs
 * to do slow work (auto-convert order → invoice, auto-import customer,
 * auto-link product) gets pushed onto this queue and drained at the
 * end of each cron tick. Spec §16, §13.4.
 *
 * Note on numbering: 260 = rate_limit (T2.6), 270 = soft_deletes
 * (T3.4). The original BUILD_PLAN penciled jobs at 260; landed at
 * 280 because the lower numbers were spoken for first. Migrations are
 * write-once after a release, so any number ≥ the highest installed
 * is fine; it's only ordering relative to peers that matters.
 */
class Migration_Version_300_jobs extends App_module_migration
{
    public function up(): void
    {
        $CI =& get_instance();
        $prefix = db_prefix();

        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}woocommerce_jobs` (
                `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `type`          VARCHAR(64)  NOT NULL,
                `store_id`      INT UNSIGNED NULL,
                `payload_json`  MEDIUMTEXT   NOT NULL,
                `status`        ENUM('pending','in_progress','done','failed','quarantined') NOT NULL DEFAULT 'pending',
                `attempts`      TINYINT UNSIGNED NOT NULL DEFAULT 0,
                `max_attempts`  TINYINT UNSIGNED NOT NULL DEFAULT 5,
                `last_error`    TEXT NULL,
                `scheduled_for` DATETIME NOT NULL,
                `locked_at`     DATETIME NULL,
                `last_run_at`   DATETIME NULL,
                `created_at`    DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_jobs_pending` (`status`, `scheduled_for`),
                KEY `idx_jobs_store_status` (`store_id`, `status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        $CI =& get_instance();
        $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'woocommerce_jobs`');
    }
}
