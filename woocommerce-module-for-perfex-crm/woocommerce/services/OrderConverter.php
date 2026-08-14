<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use Throwable;
use WooCommerce\Exceptions\WooCommerceException;
use WooCommerce\Libraries\MappingResolver;
use WooCommerce\Libraries\WooToPerfexTransformer;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\StoreDTO;

/**
 * Converts a Woo order into a Perfex invoice with the §4A.3
 * provenance marker.
 *
 *  1. Idempotency: returns the existing invoice id if one already
 *     exists for `(store_id, wco_id)`. The `(store_id, wco_id)`
 *     unique key from migration 230 is the underlying gate.
 *  2. Resolves the client — uses `GuestClientFactory` when
 *     `customer_id = 0` so guest orders never silently drop (§4A.1).
 *  3. Builds the invoice header via the order field mapping +
 *     `WooToPerfexTransformer`.
 *  4. Builds line items from `line_items` (one Perfex item per Woo
 *     line; description + qty + rate from the order's own fields).
 *     §4A.4 shipping/refund line handling is a follow-up.
 *  5. Tags every Woo-sourced invoice with the WooCommerce payment
 *     mode id via `allowed_payment_modes`.
 *  6. All-or-nothing: the whole sequence is bracketed in a
 *     transaction so a partial failure leaves no orphan rows.
 *
 * Spec refs: §4A.1, §4A.3, §5.3, §7.2, §13.4.
 */
final class OrderConverter
{
    public function __construct(
        private InvoiceGateway          $invoices,
        private GuestClientFactory      $guestFactory,
        private MappingResolver         $mappingResolver,
        private WooToPerfexTransformer  $transformer,
        private PaymentModeService      $paymentMode,
        private LogRepository           $log,
        private ClientResolver          $clientResolver,
    ) {
    }

    /**
     * @param array<string, mixed> $orderPayload Decoded Woo order body.
     * @return array{
     *     invoice_id: int,
     *     link_existing: bool,
     *     client_id: int,
     *     guest: bool
     * }
     */
    /**
     * Build the would-be invoice without writing to the DB. Powers
     * the §5.3 step 3 "Preview" UI button.
     *
     * @param array<string, mixed> $orderPayload
     * @return array{
     *     existing_invoice_id: ?int,
     *     header: array<string, mixed>,
     *     line_items: list<array<string, mixed>>,
     *     client_id: int,
     *     guest: bool,
     *     payment_mode_id: int
     * }
     */
    public function previewConvert(array $orderPayload, StoreDTO $store, string $correlationId = ''): array
    {
        $wooOrderId = (int) ($orderPayload['id'] ?? 0);
        $storeId    = (int) $store->storeId;

        $existing = $wooOrderId > 0
            ? $this->invoices->findInvoiceIdByWooOrder($storeId, $wooOrderId)
            : null;

        $wooCustomerId = (int) ($orderPayload['customer_id'] ?? 0);
        $isGuest       = $wooCustomerId === 0;

        $clientId = $isGuest
            ? 0   // guest preview doesn't create the client; convert() does that
            : ($this->clientResolver->findClientByWooCustomerId($storeId, $wooCustomerId) ?? 0);

        $orderMappings = $this->mappingResolver->resolve($storeId, 'order');
        $header        = $this->transformer->transform('order', $orderMappings, $orderPayload, [
            'store_id'       => $storeId,
            'correlation_id' => $correlationId,
            'entity'         => 'order',
        ]);

        return [
            'existing_invoice_id' => $existing,
            'header'              => $header,
            'line_items'          => self::projectLineItems($orderPayload),
            'client_id'           => $clientId,
            'guest'               => $isGuest,
            'payment_mode_id'     => $store->woocommercePaymentModeId ?? $this->paymentMode->ensure(),
        ];
    }

    /**
     * @param array<string, mixed> $orderPayload
     * @return array{
     *     invoice_id: int,
     *     link_existing: bool,
     *     client_id: int,
     *     guest: bool
     * }
     */
    public function convert(array $orderPayload, StoreDTO $store, string $correlationId = ''): array
    {
        $wooOrderId = (int) ($orderPayload['id'] ?? 0);
        if ($wooOrderId <= 0) {
            throw new WooCommerceException('Cannot convert: order payload has no id.');
        }

        $storeId = (int) $store->storeId;

        // Idempotency: short-circuit on existing.
        $existing = $this->invoices->findInvoiceIdByWooOrder($storeId, $wooOrderId);
        if ($existing !== null) {
            return [
                'invoice_id'    => $existing,
                'link_existing' => true,
                'client_id'     => 0,
                'guest'         => false,
            ];
        }

        // Resolve client: registered → lookup; guest → factory.
        $wooCustomerId = (int) ($orderPayload['customer_id'] ?? 0);
        $isGuest       = $wooCustomerId === 0;

        $clientId = $isGuest
            ? $this->guestFactory->findOrCreate($orderPayload, $storeId, $correlationId)
            : ($this->clientResolver->findClientByWooCustomerId($storeId, $wooCustomerId)
               ?? $this->guestFactory->findOrCreate($orderPayload, $storeId, $correlationId));

        // Header via mapping + transformer.
        $orderMappings = $this->mappingResolver->resolve($storeId, 'order');
        $header        = $this->transformer->transform('order', $orderMappings, $orderPayload, [
            'store_id'       => $storeId,
            'correlation_id' => $correlationId,
            'entity'         => 'order',
        ]);

        $lineItems     = self::projectLineItems($orderPayload);
        $modeId        = $store->woocommercePaymentModeId ?? $this->paymentMode->ensure();

        $this->invoices->beginTransaction();
        try {
            $invoiceId = $this->invoices->createInvoice(
                storeId:                   $storeId,
                wooOrderId:                $wooOrderId,
                clientId:                  $clientId,
                woocommercePaymentModeId:  $modeId,
                header:                    $header,
                lineItems:                 $lineItems,
            );
            $this->invoices->commitTransaction();
        } catch (Throwable $e) {
            $this->invoices->rollbackTransaction();

            $this->log->write(
                LogRepository::LEVEL_ERROR,
                'order_converter.failed',
                [
                    'woo_order_id' => $wooOrderId,
                    'exception'    => $e::class,
                    'message'      => $e->getMessage(),
                ],
                $storeId,
                $correlationId,
            );

            throw $e;
        }

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'order_converter.created_invoice',
            [
                'woo_order_id' => $wooOrderId,
                'invoice_id'   => $invoiceId,
                'client_id'    => $clientId,
                'guest'        => $isGuest,
            ],
            $storeId,
            $correlationId,
        );

        if (function_exists('hooks')) {
            hooks()->do_action('after_wc_invoice_imported', [$invoiceId, $wooOrderId, $storeId]);
        }

        return [
            'invoice_id'    => $invoiceId,
            'link_existing' => false,
            'client_id'     => $clientId,
            'guest'         => $isGuest,
        ];
    }

    /**
     * Map Woo's line_items into Perfex's expected shape. Description
     * comes from the line's `name`, qty from `quantity`, rate from
     * `total / quantity` (so per-unit price stays accurate), tax
     * from `total_tax`. Refunds and shipping lines are handled by
     * future tasks (§4A.4).
     *
     * @param array<string, mixed> $orderPayload
     * @return list<array<string, mixed>>
     */
    private static function projectLineItems(array $orderPayload): array
    {
        $out = [];

        // 1. Product line items.
        foreach (self::asArray($orderPayload['line_items'] ?? []) as $item) {
            $item = self::asArray($item);
            if (! $item) continue;

            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $totalString = (string) ($item['total'] ?? '0');
            $rate = $qty > 0 ? bcdiv($totalString === '' ? '0' : $totalString, (string) $qty, 4) : $totalString;

            $out[] = [
                'description'    => (string) ($item['name'] ?? 'Item'),
                'qty'            => $qty,
                'rate'           => $rate,
                'tax_total'      => (string) ($item['total_tax'] ?? '0'),
                'woo_product_id' => (int) ($item['product_id'] ?? 0),
                'sku'            => (string) ($item['sku'] ?? ''),
            ];
        }

        // 2. Shipping lines — Perfex invoices don't have a dedicated
        // shipping field, so we fold each shipping line into a regular
        // line item. Description is "Shipping" + the method title (e.g.
        // "Shipping — Flat rate") so the invoice still attributes the
        // amount visibly.
        foreach (self::asArray($orderPayload['shipping_lines'] ?? []) as $sl) {
            $sl = self::asArray($sl);
            if (! $sl) continue;
            $total = (string) ($sl['total'] ?? '0');
            if ($total === '' || $total === '0') continue;
            $methodTitle = trim((string) ($sl['method_title'] ?? ''));
            $out[] = [
                'description' => 'Shipping' . ($methodTitle !== '' ? ' — ' . $methodTitle : ''),
                'qty'         => 1,
                'rate'        => $total,
                'tax_total'   => (string) ($sl['total_tax'] ?? '0'),
                'woo_kind'    => 'shipping',
            ];
        }

        // 3. Fee lines — order-level surcharges (gift wrap, payment fee).
        foreach (self::asArray($orderPayload['fee_lines'] ?? []) as $fl) {
            $fl = self::asArray($fl);
            if (! $fl) continue;
            $total = (string) ($fl['total'] ?? '0');
            if ($total === '' || $total === '0') continue;
            $name = trim((string) ($fl['name'] ?? 'Fee'));
            $out[] = [
                'description' => $name === '' ? 'Fee' : $name,
                'qty'         => 1,
                'rate'        => $total,
                'tax_total'   => (string) ($fl['total_tax'] ?? '0'),
                'woo_kind'    => 'fee',
            ];
        }

        return $out;
    }

    /**
     * @param mixed $val
     * @return array<int|string, mixed>
     */
    private static function asArray(mixed $val): array
    {
        if (is_object($val)) {
            return (array) $val;
        }
        return is_array($val) ? $val : [];
    }
}
