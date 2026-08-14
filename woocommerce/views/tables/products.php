<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Server-side data source for the products list (T6.7) — Perfex CRM
 * native `App_table` pipeline with the Vue `<app-filters>` UI.
 *
 * Registration happens in `helpers/woocommerce_helper.php`
 * (`woo_register_tables()` on `admin_init`). The view at
 * `views/products.php` reads `App_table::find('woo_products')` and
 * renders the `<app-filters>` component bound to the rules below;
 * the rule values are extracted by `$this->getWhereFromRules()`
 * inside `outputUsing()` and pushed into the SQL WHERE.
 *
 * Note: the cache table `tblwoocommerce_products` does NOT track
 * stock data (no `stock_status` / `stock_quantity` columns in the
 * v3 schema). The stock indicator column shows "—" until a future
 * migration adds those columns and the sync service populates them.
 */

$rules = [
    App_table_filter::new('store_id', 'SelectRule')
        ->label(_l('woocommerce_store'))
        ->column(db_prefix() . 'woocommerce_products.store_id')
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
            ['value' => 'publish', 'label' => 'Publish'],
            ['value' => 'draft',   'label' => 'Draft'],
            ['value' => 'pending', 'label' => 'Pending'],
            ['value' => 'private', 'label' => 'Private'],
        ]),

    App_table_filter::new('type', 'MultiSelectRule')
        ->label(_l('woocommerce_type'))
        ->options([
            ['value' => 'simple',       'label' => 'Simple'],
            ['value' => 'variable',     'label' => 'Variable'],
            ['value' => 'grouped',      'label' => 'Grouped'],
            ['value' => 'external',     'label' => 'External'],
            ['value' => 'subscription', 'label' => 'Subscription'],
        ]),

    App_table_filter::new('linked_only', 'BooleanRule')
        ->label(_l('woocommerce_linked_only'))
        ->raw(function ($value) {
            return $value === '1' ? 'itemid IS NOT NULL' : 'itemid IS NULL';
        }),
];

return App_table::find('woo_products')
    ->outputUsing(function ($params) {
        // Cache table doesn't carry stock columns; aColumns intentionally
        // mirrors the live schema. See file header note.
        $aColumns = [
            'picture',                            // 0 — thumbnail
            'name',                               // 1
            'sku',                                // 2
            'type',                               // 3
            'status',                             // 4
            db_prefix() . 'woocommerce_products.id as _row_stockslot', // 5 — placeholder for stock col
            'price',                              // 6
            'sales',                              // 7
            'itemid',                             // 8
        ];

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'woocommerce_products';

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
            /* additionalSelect — values returned but not part of $aColumns */ [
                'id',
                'product_id',
                'permalink',
                'store_id',
            ]
        );

        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $row = [];

            // col 0 — thumbnail (40×40 lazy-loaded). `picture` is TEXT
            // and may carry a URL or a comma-separated list; show the
            // first URL only.
            $picture = (string) ($aRow['picture'] ?? '');
            $firstUrl = $picture !== '' ? trim(explode(',', $picture)[0]) : '';
            if ($firstUrl !== '') {
                $row[] = '<img src="' . e($firstUrl) . '" alt="" loading="lazy" '
                    . 'style="width:40px;height:40px;object-fit:cover;border-radius:4px;">';
            } else {
                $row[] = '<span class="text-muted"><i class="fa fa-image" aria-hidden="true"></i></span>';
            }

            // col 1 — product name (linked to woo permalink in a new tab).
            $name = e((string) ($aRow['name'] ?? ''));
            if (! empty($aRow['permalink'])) {
                $row[] = '<a href="' . e((string) $aRow['permalink']) . '" target="_blank" rel="noopener">'
                    . $name . '</a>';
            } else {
                $row[] = $name;
            }

            // col 2 — SKU.
            $row[] = e((string) ($aRow['sku'] ?? ''));

            // col 3 — Woo product type.
            $row[] = e((string) ($aRow['type'] ?? ''));

            // col 4 — status pill (variant key matches .woo-status-pill--*).
            $statusKey = (string) ($aRow['status'] ?? '');
            $row[] = '<span class="woo-status-pill woo-status-pill--' . e($statusKey) . '">'
                . e($statusKey ?: '—') . '</span>';

            // col 5 — stock indicator placeholder. v3 schema doesn't carry
            // stock_status / stock_quantity; this column is here to keep
            // the heading row count == data row count for DataTables.
            $row[] = '<span class="text-muted">—</span>';

            // col 6 — price (right-aligned in HTML).
            $row[] = e((string) ($aRow['price'] ?? ''));

            // col 7 — sales count.
            $row[] = e((string) ($aRow['sales'] ?? '0'));

            // col 8 — Perfex linkage indicator + edit / add-as-item actions.
            $rowStoreId = (int) ($aRow['store_id'] ?? 0);
            $rowWooId   = (int) ($aRow['product_id'] ?? 0);
            $linked     = ! empty($aRow['itemid']);

            if ($linked) {
                $itemId   = (int) $aRow['itemid'];
                $linkCell = '<a href="' . admin_url('invoice_items') . '#item_id=' . $itemId . '" '
                    . 'title="' . e(_l('woocommerce_linked_to_item')) . '">'
                    . '<i class="fa fa-check text-success" aria-hidden="true"></i> #' . $itemId
                    . '</a>';
            } else {
                // Unlinked → manual conversion CTA. Posts via WooFetch
                // from products.js; see the data-woo-link-product hook.
                $linkUrl = admin_url('woocommerce/woocommerce/link_product/'
                    . $rowStoreId . '/' . $rowWooId);
                $linkCell = '<button type="button" class="btn btn-link btn-xs woo-link-product-btn" '
                    . 'data-woo-link-product="' . e($linkUrl) . '" '
                    . 'title="' . e(_l('woocommerce_link_to_perfex_item')) . '">'
                    . '<i class="fa fa-plus mright3" aria-hidden="true"></i>'
                    . e(_l('woocommerce_add_as_item'))
                    . '</button>';
            }

            $editUrl = admin_url('woocommerce/woocommerce/product_modal/'
                . $rowStoreId . '/' . $rowWooId);
            $linkCell .= ' <a href="#" class="mleft10" data-woo-edit-product="' . e($editUrl) . '" '
                . 'aria-label="' . e(_l('edit')) . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

            $row[] = $linkCell;

            $output['aaData'][] = $row;
        }

        return $output;
    })
    ->setRules($rules);
