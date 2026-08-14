<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Customers DataTable (T6.9) — App_table + Vue <app-filters>.
 *
 * Cache table: `tblwoocommerce_customers`. Filters: store, role,
 * linked-only (Perfex client linkage), is_paying_customer.
 */

$rules = [
    App_table_filter::new('store_id', 'SelectRule')
        ->label(_l('woocommerce_store'))
        ->column(db_prefix() . 'woocommerce_customers.store_id')
        ->options(function ($ci) {
            $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
            $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
            $repo   = new \WooCommerce\Repositories\StoresRepository($ci->db, $cipher);
            return collect($repo->listStores())
                ->map(fn($s) => ['value' => (int) $s->storeId, 'label' => $s->name])
                ->all();
        }),

    App_table_filter::new('role', 'MultiSelectRule')
        ->label(_l('woocommerce_role'))
        ->options([
            ['value' => 'customer',     'label' => 'Customer'],
            ['value' => 'subscriber',   'label' => 'Subscriber'],
            ['value' => 'shop_manager', 'label' => 'Shop manager'],
            ['value' => 'administrator','label' => 'Administrator'],
        ]),

    App_table_filter::new('linked_only', 'BooleanRule')
        ->label(_l('woocommerce_linked'))
        ->raw(function ($value) {
            return $value === '1' ? 'userid IS NOT NULL' : 'userid IS NULL';
        }),
];

return App_table::find('woo_customers')
    ->outputUsing(function ($params) {
        $aColumns = [
            'id',             // 0 — bulk-import checkbox
            'avatar_url',     // 1 — thumbnail formatter
            'first_name',     // 2 — combined with last_name in formatter
            'email',          // 3
            'role',           // 4
            'username',       // 5
            'userid',         // 6 — linked indicator
            'id',             // 7 — actions placeholder
        ];

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'woocommerce_customers';

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
                'id',
                'woo_customer_id',
                'store_id',
                'last_name',
                'phone',
            ]
        );

        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $row     = [];
            $cacheId = (int) ($aRow['id'] ?? 0);
            $storeId = (int) ($aRow['store_id'] ?? 0);
            $wooId   = (int) ($aRow['woo_customer_id'] ?? 0);
            $detailUrl = admin_url('woocommerce/woocommerce/customer/' . $storeId . '/' . $wooId);

            // col 0 — bulk-import checkbox. Disabled when already linked
            // so a re-import can't double-post the same Woo customer.
            $linked = ! empty($aRow['userid']);
            $row[] = '<div class="checkbox checkbox-primary" style="margin:0;">'
                . '<input type="checkbox" class="woo-bulk-check"'
                . ' id="wooBulkC_' . $cacheId . '"'
                . ' data-id="' . $cacheId . '"'
                . ' data-store-id="' . $storeId . '"'
                . ' data-woo-id="' . $wooId . '"'
                . ' data-linked="' . ($linked ? '1' : '0') . '"'
                . ($linked ? ' disabled' : '') . '>'
                . '<label for="wooBulkC_' . $cacheId . '"></label>'
                . '</div>';

            // col 1 — avatar (32×32 round).
            $avatar = (string) ($aRow['avatar_url'] ?? '');
            if ($avatar !== '') {
                $row[] = '<img src="' . e($avatar) . '" alt="" loading="lazy" '
                    . 'style="width:32px;height:32px;object-fit:cover;border-radius:50%;">';
            } else {
                $row[] = '<span class="text-muted"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>';
            }

            // col 2 — full name (linked to detail).
            $name = trim(((string) ($aRow['first_name'] ?? '')) . ' ' . ((string) ($aRow['last_name'] ?? '')));
            if ($name === '') { $name = (string) ($aRow['email'] ?? ''); }
            if ($name === '') { $name = '#' . $wooId; }
            $row[] = '<a href="' . e($detailUrl) . '">' . e($name) . '</a>';

            // col 3 — email.
            $row[] = e((string) ($aRow['email'] ?? ''));

            // col 4 — role.
            $row[] = e((string) ($aRow['role'] ?? ''));

            // col 5 — username.
            $row[] = e((string) ($aRow['username'] ?? ''));

            // col 6 — Perfex client link indicator.
            if (! empty($aRow['userid'])) {
                $row[] = '<a href="' . admin_url('clients/client/' . (int) $aRow['userid']) . '">'
                    . '<i class="fa fa-check text-success mright3" aria-hidden="true"></i>#'
                    . (int) $aRow['userid'] . '</a>';
            } else {
                $row[] = '<i class="fa fa-times text-muted" aria-hidden="true" '
                    . 'title="' . e(_l('woocommerce_not_linked')) . '"></i>';
            }

            // col 7 — actions.
            $row[] = '<a href="' . e($detailUrl) . '" class="btn btn-default btn-sm" '
                . 'aria-label="' . e(_l('view')) . '">'
                . '<i class="fa fa-eye" aria-hidden="true"></i></a>';

            $output['aaData'][] = $row;
        }

        return $output;
    })
    ->setRules($rules);
