<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * v3.0.0 — webhook log + structured log tables, plus the `is_guest` flag on
 * tblclients (drives §4A.1 guest-checkout sync).
 *
 * Idempotent: tables use CREATE TABLE IF NOT EXISTS, columns are
 * field_exists-guarded.
 *
 * Spec refs: §7.1.8, §7.1.9, §4A.1.
 */
class Migration_Version_300_logging extends App_module_migration
{
    public function up(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        $prefix = db_prefix();

        // ---------------------- woocommerce_webhook_log ----------------------
        // §7.1.8 — keyed on delivery_id for replay-protection dedup.
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}woocommerce_webhook_log` (
                `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `store_id`      INT UNSIGNED NOT NULL,
                `topic`         VARCHAR(64)  NOT NULL,
                `resource`      VARCHAR(32)  NOT NULL,
                `woo_id`        INT UNSIGNED NULL,
                `delivery_id`   VARCHAR(64)  NOT NULL,
                `received_at`   DATETIME     NOT NULL,
                `signature_ok`  TINYINT(1)   NOT NULL DEFAULT 0,
                `processed`     TINYINT(1)   NOT NULL DEFAULT 0,
                `error`         TEXT         NULL,
                `payload_hash`  CHAR(64)     NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_webhook_delivery` (`delivery_id`),
                KEY `idx_webhook_store_topic` (`store_id`, `topic`),
                KEY `idx_webhook_received_at` (`received_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // -------------------------- woocommerce_log --------------------------
        // §7.1.9 — generic structured log; rotation handled by cron, not here.
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}woocommerce_log` (
                `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `store_id`       INT UNSIGNED NULL,
                `level`          ENUM('info','warn','error') NOT NULL DEFAULT 'info',
                `event`          VARCHAR(128) NOT NULL,
                `context_json`   MEDIUMTEXT   NULL,
                `correlation_id` VARCHAR(64)  NULL,
                `created_at`     DATETIME     NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_log_store_event_time` (`store_id`, `event`, `created_at`),
                KEY `idx_log_level_time`       (`level`, `created_at`),
                KEY `idx_log_correlation`      (`correlation_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // -------------------------- tblclients.is_guest ----------------------
        if (! $CI->db->field_exists('is_guest', 'clients')) {
            $CI->dbforge->add_column('clients', [
                'is_guest' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ],
            ]);
        }
    }

    public function down(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        $prefix = db_prefix();

        if ($CI->db->field_exists('is_guest', 'clients')) {
            $CI->dbforge->drop_column('clients', 'is_guest');
        }

        $CI->db->query("DROP TABLE IF EXISTS `{$prefix}woocommerce_log`");
        $CI->db->query("DROP TABLE IF EXISTS `{$prefix}woocommerce_webhook_log`");
    }
}
