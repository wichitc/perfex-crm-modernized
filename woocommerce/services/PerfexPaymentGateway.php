<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Concrete `PaymentGateway` against Perfex's tblinvoicepaymentrecords
 * + tblinvoices.status. Records the Woo payment with the WooCommerce
 * payment-mode marker so an admin can spot Woo-sourced payments at a
 * glance and the auto re-flow logic (§4A.3) has a stable hook.
 *
 * Spec refs: §4A.3, §5.3, §13.4.
 */
final class PerfexPaymentGateway implements PaymentGateway
{
    public function __construct(
        private object $db,
        private string $tablePrefix = 'tbl',
    ) {
    }

    public function paymentRecorded(int $invoiceId, string $transactionId): bool
    {
        if ($invoiceId <= 0 || $transactionId === '') {
            return false;
        }
        $row = $this->db
            ->select('id')
            ->where('invoiceid',     $invoiceId)
            ->where('transactionid', $transactionId)
            ->limit(1)
            ->get($this->tablePrefix . 'invoicepaymentrecords')
            ->row_array();
        return is_array($row);
    }

    public function recordPayment(array $row): int
    {
        $row['date']        ??= date('Y-m-d');
        $row['daterecorded'] = date('Y-m-d H:i:s');
        $this->db->insert($this->tablePrefix . 'invoicepaymentrecords', $row);
        return (int) $this->db->insert_id();
    }

    public function markInvoicePaid(int $invoiceId): void
    {
        // Status 2 = paid in Perfex's invoice-status enum.
        $this->db
            ->where('id', $invoiceId)
            ->update($this->tablePrefix . 'invoices', ['status' => 2]);
    }

    public function totalPaidOnInvoice(int $invoiceId): string
    {
        $row = $this->db
            ->select_sum('amount', 'total_paid')
            ->where('invoiceid', $invoiceId)
            ->get($this->tablePrefix . 'invoicepaymentrecords')
            ->row_array();
        return is_array($row) ? (string) ($row['total_paid'] ?? '0') : '0';
    }

    public function invoiceTotal(int $invoiceId): string
    {
        $row = $this->db
            ->select('total')
            ->where('id', $invoiceId)
            ->limit(1)
            ->get($this->tablePrefix . 'invoices')
            ->row_array();
        return is_array($row) ? (string) ($row['total'] ?? '0') : '0';
    }
}
