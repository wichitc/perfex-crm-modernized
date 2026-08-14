<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Orders DataTable (T6.5) — App_table + Vue <app-filters>.
 *
 * Cache table: `tblwoocommerce_orders`. Filter rules: store, status,
 * paid (invoice_id present + payment recorded), date range. Linked
 * column shows whether a Perfex invoice was created.
 */

$rules = [
    App_table_filter::new('store_id', 'SelectRule')
        ->label(_l('woocommerce_store'))
        ->column(db_prefix() . 'woocommerce_orders.store_id')
        ->options(function ($ci) {
            $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
            $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
            $repo   = new \WooCommerce\Repositories\StoresRepository($ci->db, $cipher);
            return collect($repo->listStores())
                ->map(fn($s) => ['value' => (int) $s->storeId, 'label' => $s->name])
                ->all();
        }),

    App_table_filter::new('status', 'MultiSelectRule')
        ->label(_l('status'))
        ->options([
            ['value' => 'pending',    'label' => 'Pending'],
            ['value' => 'processing', 'label' => 'Processing'],
            ['value' => 'on-hold',    'label' => 'On hold'],
            ['value' => 'completed',  'label' => 'Completed'],
            ['value' => 'cancelled',  'label' => 'Cancelled'],
            ['value' => 'refunded',   'label' => 'Refunded'],
            ['value' => 'failed',     'label' => 'Failed'],
        ]),

    App_table_filter::new('currency', 'TextRule')
        ->label(_l('woocommerce_total') . ' ' . _l('woocommerce_currency', 'currency'))
        ->column('currency'),

    App_table_filter::new('date_created', 'DateRule')
        ->label(_l('date'))
        ->column('date_created'),

    App_table_filter::new('linked_only', 'BooleanRule')
        ->label(_l('woocommerce_linked'))
        ->raw(function ($value) {
            return $value === '1' ? 'invoice_id IS NOT NULL' : 'invoice_id IS NULL';
        }),
];

return App_table::find('woo_orders')
    ->outputUsing(function ($params) {
        $aColumns = [
            'id',               // 0 — bulk-action checkbox cell
            'order_number',     // 1
            'customer_id',      // 2 — replaced with name+phone in formatter
            'status',           // 3
            'currency',         // 4
            'total',            // 5
            'invoice_id',       // 6 — formatter renders link or "Convert" CTA
            'date_created',     // 7
            'id',               // 8 — placeholder for actions cell
        ];

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'woocommerce_orders';

        $where = [];
        if ($filtersWhere = $this->getWhereFromRules()) {
            $where[] = $filtersWhere;
        }

        $result = data_tables_init(
            $aColumns,
            $sIndexColumn,
            $sTable,
            /* joins */ [],
            $where,
            /* additionalSelect */ [
                'order_id',
                'store_id',
                'address',
                'phone',
                'is_deleted',
            ]
        );

        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $row = [];
            $cacheId = (int) ($aRow['id'] ?? 0);
            $storeId = (int) ($aRow['store_id'] ?? 0);
            $wooId   = (int) ($aRow['order_id'] ?? 0);

            // col 0 — bulk-action checkbox. Carries the cache row id so
            // the bulk endpoints can look up store_id + woo_id once and
            // re-use the existing single-order conversion path.
            $row[] = '<div class="checkbox checkbox-primary"'
                . ' style="margin:0;">'
                . '<input type="checkbox" class="woo-bulk-check"'
                . ' id="wooBulk_' . $cacheId . '"'
                . ' data-id="' . $cacheId . '"'
                . ' data-store-id="' . $storeId . '"'
                . ' data-woo-id="' . $wooId . '"'
                . ' data-status="' . e((string) ($aRow['status'] ?? '')) . '"'
                . ' data-invoice-id="' . (int) ($aRow['invoice_id'] ?? 0) . '">'
                . '<label for="wooBulk_' . $cacheId . '"></label>'
                . '</div>';

            // col 1 — order number, linked to the detail page.
            $detailUrl = admin_url('woocommerce/woocommerce/order/' . $storeId . '/' . $wooId);
            $row[] = '<a href="' . e($detailUrl) . '">#' . e((string) ($aRow['order_number'] ?? '')) . '</a>';

            // col 2 — customer line. Cache only carries customer_id +
            // address + phone (no name); show the most useful summary.
            $custLine = '';
            if (! empty($aRow['phone'])) {
                $custLine = e((string) $aRow['phone']);
            } elseif (! empty($aRow['customer_id'])) {
                $custLine = '#' . (int) $aRow['customer_id'];
            } else {
                $custLine = '<span class="text-muted">' . e(_l('woocommerce_guest')) . '</span>';
            }
            $row[] = $custLine;

            // col 3 — status pill.
            $statusKey = (string) ($aRow['status'] ?? '');
            $row[] = '<span class="woo-status-pill woo-status-pill--' . e($statusKey) . '">'
                . e($statusKey ?: '—') . '</span>';

            // col 4 — currency.
            $row[] = e((string) ($aRow['currency'] ?? ''));

            // col 5 — total (right-aligned in heading).
            $row[] = e((string) ($aRow['total'] ?? ''));

            // col 6 — converted pill + invoice link OR convert CTA.
            // Distinct visuals for the two states so admins can spot
            // unconverted orders at a glance: green "✓ #invoice" pill
            // when linked, neutral outline button when not.
            $invoiceId = (int) ($aRow['invoice_id'] ?? 0);
            if ($invoiceId > 0) {
                $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $invoiceId) . '" '
                    . 'class="woo-status-pill woo-status-pill--completed" '
                    . 'title="' . e(_l('woocommerce_view_invoice')) . '">'
                    . '<i class="fa fa-check mright3" aria-hidden="true"></i>#' . $invoiceId
                    . '</a>';
            } else {
                $row[] = '<a href="' . e($detailUrl) . '" class="btn btn-default btn-xs">'
                    . '<i class="fa fa-magic mright3" aria-hidden="true"></i>'
                    . e(_l('woocommerce_convert')) . '</a>';
            }

            // col 7 — date_created (rendered ISO; use DateRule for filter).
            $row[] = e((string) ($aRow['date_created'] ?? ''));

            // col 8 — actions (view).
            $row[] = '<a href="' . e($detailUrl) . '" class="btn btn-default btn-sm" '
                . 'aria-label="' . e(_l('view')) . '">'
                . '<i class="fa fa-eye" aria-hidden="true"></i></a>';

            $output['aaData'][] = $row;
        }

        return $output;
    })
    ->setRules($rules);
