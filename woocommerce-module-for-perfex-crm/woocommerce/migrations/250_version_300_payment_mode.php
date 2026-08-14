<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * v3.0.0 — provenance marker for Woo-sourced invoices/payments.
 *
 * Adds tblpayment_modes.is_system_managed, woocommerce_stores
 * .woocommerce_payment_mode_id, and inserts the disabled "WooCommerce"
 * payment mode row idempotently. No fee-reconciliation engine — this is
 * the v1 answer to BUG-001 (§4A.3, §20.1, locked decision).
 *
 * Spec refs: §4A.3, §7.2.
 */
class Migration_Version_300_payment_mode extends App_module_migration
{
    public const WOOCOMMERCE_PAYMENT_MODE_NAME        = 'WooCommerce';
    public const WOOCOMMERCE_PAYMENT_MODE_DESCRIPTION = 'Auto-tag for invoices and payments imported from WooCommerce. Do not enable for manual use.';

    public function up(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        // ---------------- tblpayment_modes.is_system_managed -----------------
        if (! $CI->db->field_exists('is_system_managed', 'payment_modes')) {
            $CI->dbforge->add_column('payment_modes', [
                'is_system_managed' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => false,
                    'default'    => 0,
                ],
            ]);
        }

        // -------- woocommerce_stores.woocommerce_payment_mode_id -------------
        if (! $CI->db->field_exists('woocommerce_payment_mode_id', 'woocommerce_stores')) {
            $CI->dbforge->add_column('woocommerce_stores', [
                'woocommerce_payment_mode_id' => [
                    'type' => 'INT',
                    'null' => true,
                ],
            ]);
        }

        // ---------------- insert the WooCommerce payment mode ----------------
        $existing = $CI->db->select('id')
            ->where('name', self::WOOCOMMERCE_PAYMENT_MODE_NAME)
            ->get(db_prefix() . 'payment_modes')
            ->row();

        if (! $existing) {
            $CI->db->insert(db_prefix() . 'payment_modes', [
                'name'              => self::WOOCOMMERCE_PAYMENT_MODE_NAME,
                'description'       => self::WOOCOMMERCE_PAYMENT_MODE_DESCRIPTION,
                'show_on_pdf'       => 0,
                'invoices_only'     => 0,
                'expenses_only'     => 0,
                'selected_by_default' => 0,
                'active'            => 0,
                'is_system_managed' => 1,
            ]);
        } else {
            // Existing row (e.g. from a partial earlier upgrade) — make sure
            // it stays system-managed so admins can't accidentally delete it.
            $CI->db->where('id', $existing->id)
                ->update(db_prefix() . 'payment_modes', [
                    'is_system_managed' => 1,
                    'active'            => 0,
                ]);
        }
    }

    public function down(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        $CI->db->where('name', self::WOOCOMMERCE_PAYMENT_MODE_NAME)
            ->where('is_system_managed', 1)
            ->delete(db_prefix() . 'payment_modes');

        if ($CI->db->field_exists('woocommerce_payment_mode_id', 'woocommerce_stores')) {
            $CI->dbforge->drop_column('woocommerce_stores', 'woocommerce_payment_mode_id');
        }

        if ($CI->db->field_exists('is_system_managed', 'payment_modes')) {
            $CI->dbforge->drop_column('payment_modes', 'is_system_managed');
        }
    }
}
