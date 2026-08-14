<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Abstraction over Perfex's `Invoices_model::add` + the
 * `tblinvoices` queries the converter needs. Lifted out so the
 * conversion logic is unit-testable without a live Perfex tenant.
 *
 * Spec refs: §4A.3, §5.3, §7.2.
 */
interface InvoiceGateway
{
    /**
     * Look up an existing invoice for a Woo order. Returns the
     * invoice id, or null when no matching row exists.
     */
    public function findInvoiceIdByWooOrder(int $storeId, int $wooOrderId): ?int;

    /**
     * Create a new invoice. Stamps wco_id, store_id, and
     * allowed_payment_modes (which must include the WooCommerce
     * payment-mode id). Returns the new invoice id.
     *
     * @param array<string, mixed>      $header     Invoice header fields.
     * @param list<array<string, mixed>> $lineItems  One element per Woo line_item.
     */
    public function createInvoice(
        int $storeId,
        int $wooOrderId,
        int $clientId,
        int $woocommercePaymentModeId,
        array $header,
        array $lineItems
    ): int;

    /**
     * Begin / commit / rollback a DB transaction. The converter
     * brackets its work so a failure mid-way leaves no orphan rows.
     */
    public function beginTransaction(): void;
    public function commitTransaction(): void;
    public function rollbackTransaction(): void;
}
