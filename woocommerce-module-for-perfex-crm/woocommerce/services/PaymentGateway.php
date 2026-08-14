<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Abstraction over Perfex's payment-record + invoice-status update.
 * Production wiring uses `Invoices_model::set_invoice_status` and
 * `Payments_model::process` (via a thin adapter); tests pass a fake.
 */
interface PaymentGateway
{
    /**
     * Returns true iff a payment row already exists for this
     * (invoice_id, transactionid) pair. Drives the idempotency gate
     * — a Stripe webhook delivered twice must not produce two
     * payment rows.
     */
    public function paymentRecorded(int $invoiceId, string $transactionId): bool;

    /**
     * Insert a payment row. Returns the new row id.
     *
     * @param array<string, mixed> $row tblinvoicepaymentrecords-shaped fields
     */
    public function recordPayment(array $row): int;

    /**
     * Mark the invoice paid (status = 2 in Perfex). No-op if the
     * invoice is already paid.
     */
    public function markInvoicePaid(int $invoiceId): void;

    /**
     * Sum the existing payments on an invoice — used to decide
     * whether to flip the status to paid.
     */
    public function totalPaidOnInvoice(int $invoiceId): string;

    /**
     * Look up the invoice's expected total. Used as the trigger for
     * the status flip.
     */
    public function invoiceTotal(int $invoiceId): string;
}
