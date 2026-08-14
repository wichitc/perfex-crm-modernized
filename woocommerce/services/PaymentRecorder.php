<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\LogRepository;

/**
 * Records a Woo-sourced payment against a Perfex invoice with the
 * §4A.3 provenance marker (the WooCommerce payment mode).
 *
 * Idempotent on `(invoice_id, transactionid)` so a Stripe webhook
 * delivered twice (or a manual re-convert) never produces two
 * payment rows. Updates invoice status to paid only when the sum of
 * recorded payments meets or exceeds the invoice total.
 *
 * Spec refs: §4A.3, §5.3.
 */
final class PaymentRecorder
{
    public function __construct(
        private PaymentGateway     $payments,
        private PaymentModeService $paymentMode,
        private LogRepository      $log,
    ) {
    }

    /**
     * @param array<string, mixed> $orderPayload
     * @return array{
     *     payment_id: int,
     *     created: bool,
     *     marked_paid: bool
     * }
     */
    public function record(int $invoiceId, array $orderPayload, int $storeId, ?int $cachedModeId = null, string $correlationId = ''): array
    {
        $transactionId = (string) ($orderPayload['transaction_id'] ?? '');
        $amount        = (string) ($orderPayload['total'] ?? '0');
        $date          = (string) ($orderPayload['date_paid']
            ?? $orderPayload['date_completed']
            ?? $orderPayload['date_created']
            ?? date('Y-m-d H:i:s'));

        if ($transactionId !== '' && $this->payments->paymentRecorded($invoiceId, $transactionId)) {
            $this->log->write(
                LogRepository::LEVEL_INFO,
                'payment_recorder.dedup_hit',
                ['invoice_id' => $invoiceId, 'transaction_id' => $transactionId],
                $storeId,
                $correlationId,
            );

            return ['payment_id' => 0, 'created' => false, 'marked_paid' => false];
        }

        $modeId = $cachedModeId ?? $this->paymentMode->ensure();

        $paymentId = $this->payments->recordPayment([
            'invoiceid'     => $invoiceId,
            'amount'        => $amount,
            'paymentmode'   => $modeId,
            'date'          => self::normaliseDate($date),
            'transactionid' => $transactionId,
            'note'          => 'Imported from WooCommerce order ' . (string) ($orderPayload['number'] ?? $orderPayload['id'] ?? ''),
        ]);

        // Flip status to paid when totals balance.
        $totalPaid     = $this->payments->totalPaidOnInvoice($invoiceId);
        $invoiceTotal  = $this->payments->invoiceTotal($invoiceId);
        $marked        = false;

        if (self::totalsMeetOrExceed($totalPaid, $invoiceTotal)) {
            $this->payments->markInvoicePaid($invoiceId);
            $marked = true;
        }

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'payment_recorder.recorded',
            [
                'invoice_id'     => $invoiceId,
                'payment_id'     => $paymentId,
                'amount'         => $amount,
                'transaction_id' => $transactionId,
                'mode_id'        => $modeId,
                'marked_paid'    => $marked,
            ],
            $storeId,
            $correlationId,
        );

        return [
            'payment_id'  => $paymentId,
            'created'     => true,
            'marked_paid' => $marked,
        ];
    }

    /**
     * Woo dates are ISO 8601 (YYYY-MM-DDTHH:MM:SS); Perfex's
     * tblinvoicepaymentrecords.date column is DATETIME — strip the T.
     */
    private static function normaliseDate(string $iso): string
    {
        if ($iso === '') {
            return date('Y-m-d H:i:s');
        }
        $clean = str_replace('T', ' ', $iso);
        // Trim timezone (Perfex stores local; conversion is the deployer's choice).
        return preg_replace('/(\.\d+)?(Z|[+-]\d{2}:?\d{2})$/', '', $clean) ?? $clean;
    }

    private static function totalsMeetOrExceed(string $totalPaid, string $invoiceTotal): bool
    {
        // Use bccomp so 100.00 == 100 doesn't get fooled by string equality.
        return bccomp($totalPaid, $invoiceTotal, 4) >= 0;
    }
}
