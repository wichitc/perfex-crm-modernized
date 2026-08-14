<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Concrete `InvoiceGateway` against Perfex's tblinvoices +
 * tblitemable + tblitems_in tables. Persists the converted invoice
 * (header + line items) inside a DB transaction so a partial failure
 * leaves no orphan rows.
 *
 * Spec refs: §4A.3, §5.3, §7.2.
 */
final class PerfexInvoiceGateway implements InvoiceGateway
{
    public function __construct(
        private object $db,
        private string $tablePrefix = 'tbl',
    ) {
    }

    public function findInvoiceIdByWooOrder(int $storeId, int $wooOrderId): ?int
    {
        if ($wooOrderId <= 0) {
            return null;
        }
        $row = $this->db
            ->select('id')
            ->where('store_id', $storeId)
            ->where('wco_id',   $wooOrderId)
            ->limit(1)
            ->get($this->tablePrefix . 'invoices')
            ->row_array();

        return is_array($row) && isset($row['id']) ? (int) $row['id'] : null;
    }

    public function createInvoice(
        int $storeId,
        int $wooOrderId,
        int $clientId,
        int $woocommercePaymentModeId,
        array $header,
        array $lineItems
    ): int {
        // tblinvoices.currency is an INT FK into tblcurrencies, but the
        // preset maps Woo's `currency` (ISO code like "USD") straight
        // through. Resolve the code to an id here so the saved invoice
        // shows in the order's actual currency rather than the tenant's
        // default. Falls back to the `isdefault = 1` row when the code
        // doesn't match any currency the tenant has configured.
        $resolvedCurrencyId = $this->resolveCurrencyId($header['currency'] ?? null);
        $header['currency'] = $resolvedCurrencyId;

        // tblinvoices.number is an INT auto-sequence keyed off the
        // tenant's invoice_prefix + format (year, year/month). The
        // preset maps Woo's `number` (a STRING like "1042") straight
        // through, which silently coerces to 0 and breaks Perfex's
        // invoice template (str_pad on null/0). Always override with
        // the next sequence number so converted invoices honour the
        // tenant's numbering scheme. Woo's order id is preserved on
        // `wco_id` for cross-reference.
        //
        // Use the canonical `next_invoice_number` option (the same
        // value Perfex's own Invoices_model::add reads at line 615)
        // and bump it after the insert via increment_next_number()'s
        // SQL — keeps our sequence in lock-step with manual creates
        // happening through the standard Perfex UI.
        $nextNumber = function_exists('get_option')
            ? (int) (get_option('next_invoice_number') ?: 1)
            : 1;
        $header['number'] = $nextNumber;

        // Mirror what core does: prefix comes from the `invoice_prefix`
        // option. Only fill it when the mapping didn't (tblinvoices.prefix
        // is NOT NULL) — leave manual overrides on the mapping intact.
        if (! isset($header['prefix']) || $header['prefix'] === '') {
            $header['prefix'] = function_exists('get_option')
                ? (string) get_option('invoice_prefix')
                : '';
        }

        // tblinvoices.number_format drives Perfex's invoice template
        // branching at line 138 of invoice_template.php: $format gets
        // copied from $invoice->number_format and feeds an
        // if/elseif chain over 1/2/3/4. Anything else (including
        // NULL or 0) skips every branch and leaves $__number
        // undefined, so the view warns + str_pad(null) at line 182.
        // Default to the tenant's `invoice_number_format` option,
        // matching what Invoices_model::add stamps on manual creates.
        if (! isset($header['number_format']) || (int) $header['number_format'] <= 0) {
            $header['number_format'] = function_exists('get_option')
                ? (int) (get_option('invoice_number_format') ?: 1)
                : 1;
        }

        // tblinvoices.hash powers the public "view as customer" link
        // (`/invoice/{id}/{hash}`) and the auto-fired customer email.
        // Invoices_model::add line 359 mints one on every create via
        // app_generate_hash(); manual rows without one show a blank
        // public link and break the invoice-sent reminders job.
        // Always overwrite — a hash from the mapping wouldn't be a
        // collision-resistant value anyway.
        if (function_exists('app_generate_hash')) {
            $header['hash'] = app_generate_hash();
        } elseif (! isset($header['hash']) || $header['hash'] === '') {
            // Defence: in test contexts where the helper isn't loaded,
            // fall back to a 32-hex random value with the same shape.
            $header['hash'] = bin2hex(random_bytes(16));
        }

        // Match other small Invoices_model::add stamps that the view
        // assumes are non-null. Empty strings on TEXT columns are
        // safer than NULL for downstream HTML rendering.
        $header['cancel_overdue_reminders'] = $header['cancel_overdue_reminders'] ?? 0;
        $header['custom_recurring']         = $header['custom_recurring']         ?? 0;
        $header['recurring']                = $header['recurring']                ?? 0;

        // Header: graft store_id + wco_id onto whatever the transformer
        // already prepared. `allowed_payment_modes` is a serialized list
        // in core Perfex; force-include the WooCommerce mode so the UI
        // shows it for manual edits without requiring admins to flip
        // permissions.
        $headerRow = $header + [
            'clientid'              => $clientId,
            'date'                  => date('Y-m-d'),
            'duedate'               => date('Y-m-d'),
            'status'                => 1, // draft
            'datecreated'           => date('Y-m-d H:i:s'),
            'addedfrom'             => function_exists('get_staff_user_id') ? (int) get_staff_user_id() : 0,
            'currency'              => $resolvedCurrencyId,
        ];
        $headerRow['store_id'] = $storeId;
        $headerRow['wco_id']   = $wooOrderId;
        $headerRow['allowed_payment_modes'] = serialize([
            (string) $woocommercePaymentModeId,
        ]);

        $this->db->insert($this->tablePrefix . 'invoices', $headerRow);
        $invoiceId = (int) $this->db->insert_id();

        // Mirror Perfex's own Invoices_model::increment_next_number().
        // Done after insert so a failed insert leaves the sequence
        // untouched (matches the rollback semantics on the surrounding
        // OrderConverter transaction).
        $this->db
            ->where('name', 'next_invoice_number')
            ->set('value', 'value+1', false)
            ->update($this->tablePrefix . 'options');

        foreach ($lineItems as $idx => $item) {
            $this->db->insert($this->tablePrefix . 'itemable', [
                'rel_id'      => $invoiceId,
                'rel_type'    => 'invoice',
                'description' => (string) ($item['description'] ?? ($item['name'] ?? '')),
                'long_description' => (string) ($item['long_description'] ?? ''),
                'qty'         => (float) ($item['qty'] ?? 1),
                'rate'        => (float) ($item['rate'] ?? ($item['price'] ?? 0)),
                'unit'        => (string) ($item['unit'] ?? ''),
                'item_order'  => $idx + 1,
            ]);
        }

        return $invoiceId;
    }

    public function beginTransaction(): void
    {
        $this->db->trans_begin();
    }

    public function commitTransaction(): void
    {
        $this->db->trans_commit();
    }

    public function rollbackTransaction(): void
    {
        $this->db->trans_rollback();
    }

    /**
     * Resolve whatever the mapping handed us into a `tblcurrencies.id`.
     *
     *   - integer / numeric string → assumed to already be the id
     *   - alpha string ("USD")     → lookup by `tblcurrencies.name`,
     *                                case-insensitive
     *   - empty / unmatched        → the row marked `isdefault = 1`
     *
     * Cached for the request so a 50-line invoice doesn't hit the
     * currencies table 50 times.
     */
    private function resolveCurrencyId(mixed $value): int
    {
        static $cache = [];

        $key = is_string($value) ? strtoupper(trim($value)) : (string) $value;
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        if ($value !== null && is_numeric($value) && (int) $value > 0) {
            return $cache[$key] = (int) $value;
        }

        if (is_string($value) && trim($value) !== '') {
            // tblcurrencies.name stores the ISO code uppercased (USD, EUR,
            // GBP) per Perfex's seed data and the admin UI's enforcement.
            // Woo also emits the code uppercase, so a plain equality on
            // the already-uppercased $key matches without needing a
            // SQL-side UPPER() wrapper (which would mean escape=false on
            // the where clause and risk SQLi).
            $row = $this->db
                ->select('id')
                ->where('name', $key)
                ->limit(1)
                ->get($this->tablePrefix . 'currencies')
                ->row_array();
            if (is_array($row) && ! empty($row['id'])) {
                return $cache[$key] = (int) $row['id'];
            }
        }

        // Fall back to the tenant's default currency.
        $defaultRow = $this->db
            ->select('id')
            ->where('isdefault', 1)
            ->limit(1)
            ->get($this->tablePrefix . 'currencies')
            ->row_array();

        return $cache[$key] = is_array($defaultRow) && ! empty($defaultRow['id'])
            ? (int) $defaultRow['id']
            : 1;
    }
}
