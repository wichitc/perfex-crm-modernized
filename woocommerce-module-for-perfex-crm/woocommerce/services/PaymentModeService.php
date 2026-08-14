<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Manages the disabled "WooCommerce" payment mode that tags every
 * Woo-sourced invoice / payment per spec §4A.3 — the v1 answer to
 * BUG-001 (no fee-reconciliation engine; provenance marker only).
 *
 * Migration 250 inserts the row on install. This service is the
 * runtime safety net: if an admin somehow deletes the row, the next
 * cron tick or webhook recreates it. Idempotent: calling
 * `ensure()` multiple times produces zero extra DB writes once the
 * row is in place.
 *
 * Spec refs: §4A.3, §7.1.1.
 */
class PaymentModeService
{
    public const MODE_NAME        = 'WooCommerce';
    public const MODE_DESCRIPTION = 'Auto-tag for invoices and payments imported from WooCommerce. Do not enable for manual use.';

    public function __construct(
        private object $db,
        private string $tablePrefix = 'tbl',
    ) {
    }

    /**
     * Returns the WooCommerce payment mode's id, creating the row
     * (and re-asserting is_system_managed=1 / active=0) if missing.
     */
    public function ensure(): int
    {
        $existing = $this->db->select('id')
            ->where('name', self::MODE_NAME)
            ->limit(1)
            ->get($this->tablePrefix . 'payment_modes')
            ->row_array();

        if (is_array($existing) && isset($existing['id'])) {
            // Re-assert the system-managed + disabled flags — an
            // admin who toggled the row gets it auto-corrected on
            // the next tick.
            $this->db
                ->where('id', (int) $existing['id'])
                ->where_in('is_system_managed', [0, 1])
                ->where('active', 1)
                ->update($this->tablePrefix . 'payment_modes', [
                    'is_system_managed' => 1,
                    'active'            => 0,
                ]);

            return (int) $existing['id'];
        }

        $this->db->insert($this->tablePrefix . 'payment_modes', [
            'name'              => self::MODE_NAME,
            'description'       => self::MODE_DESCRIPTION,
            'show_on_pdf'       => 0,
            'invoices_only'     => 0,
            'expenses_only'     => 0,
            'selected_by_default' => 0,
            'active'            => 0,
            'is_system_managed' => 1,
        ]);

        return (int) $this->db->insert_id();
    }

    /**
     * Cache the resolved mode id on every store row so the converter
     * doesn't have to JOIN tblpayment_modes for every conversion.
     * Returns the count of stores updated.
     */
    public function cacheOnStores(int $modeId): int
    {
        $this->db
            ->where('woocommerce_payment_mode_id IS NULL', null, false)
            ->or_where('woocommerce_payment_mode_id !=', $modeId)
            ->update($this->tablePrefix . 'woocommerce_stores', [
                'woocommerce_payment_mode_id' => $modeId,
            ]);

        return (int) $this->db->affected_rows();
    }
}
