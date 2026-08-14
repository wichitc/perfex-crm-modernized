<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * v3.0.0 — soft-delete columns on the three cache tables.
 *
 * Spec §13.3 says delete webhooks should NOT physically drop the
 * cached row ("auditors want history") — they flip a boolean and
 * stamp a deletion timestamp. Adding the columns separately from
 * 230 because that migration may already have run on dev tenants and
 * Perfex's runner skips numbers <= installed_version.
 *
 * Spec refs: §13.3, §7.1.3, §7.1.4, §7.1.5.
 */
class Migration_Version_300_soft_deletes extends App_module_migration
{
    private const TABLES = [
        'woocommerce_orders',
        'woocommerce_products',
        'woocommerce_customers',
    ];

    public function up(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        foreach (self::TABLES as $table) {
            if (! $CI->db->field_exists('is_deleted', $table)) {
                $CI->dbforge->add_column($table, [
                    'is_deleted' => [
                        'type'       => 'TINYINT',
                        'constraint' => 1,
                        'null'       => false,
                        'default'    => 0,
                    ],
                ]);
            }

            if (! $CI->db->field_exists('deleted_at', $table)) {
                $CI->dbforge->add_column($table, [
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }
        }
    }

    public function down(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        foreach (self::TABLES as $table) {
            if ($CI->db->field_exists('deleted_at', $table)) {
                $CI->dbforge->drop_column($table, 'deleted_at');
            }
            if ($CI->db->field_exists('is_deleted', $table)) {
                $CI->dbforge->drop_column($table, 'is_deleted');
            }
        }
    }
}
