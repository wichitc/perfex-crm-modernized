<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Logs DataTable (T6.11) — App_table + Vue <app-filters>.
 *
 * Two backing tables:
 *   tblwoocommerce_log         — generic structured log (level, event,
 *                                 context_json, correlation_id)
 *   tblwoocommerce_webhook_log — inbound webhook receipts (topic,
 *                                 signature_ok, processed, error)
 *
 * They expose different shapes, so we project both onto a common
 * column set via a UNION ALL subquery and feed that to
 * `data_tables_init` as `$sTable`. The `source` column tells the
 * formatter which "view context" link to render.
 */

$rules = [
    App_table_filter::new('store_id', 'SelectRule')
        ->label(_l('woocommerce_store'))
        ->column('store_id')
        ->options(function ($ci) {
            $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
            $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
            $repo   = new \WooCommerce\Repositories\StoresRepository($ci->db, $cipher);
            return collect($repo->listStores())
                ->map(fn($s) => ['value' => (int) $s->storeId, 'label' => $s->name])
                ->all();
        }),

    App_table_filter::new('level', 'MultiSelectRule')
        ->label(_l('woocommerce_level'))
        ->column('level')
        ->options([
            ['value' => 'info', 'label' => 'info'],
            ['value' => 'warn', 'label' => 'warn'],
            ['value' => 'error','label' => 'error'],
        ]),

    App_table_filter::new('source', 'SelectRule')
        ->label(_l('woocommerce_event'))
        ->column('source')
        ->options([
            ['value' => 'log',     'label' => 'Structured log'],
            ['value' => 'webhook', 'label' => 'Webhook receipt'],
        ]),

    App_table_filter::new('event', 'TextRule')
        ->label(_l('woocommerce_event'))
        ->column('event'),

    App_table_filter::new('correlation_id', 'TextRule')
        ->label(_l('woocommerce_correlation_id'))
        ->column('correlation_id'),

    App_table_filter::new('ts', 'DateRule')
        ->label(_l('date'))
        ->column('ts'),
];

return App_table::find('woo_logs')
    ->outputUsing(function ($params) {
        // Project both tables onto a shared schema via UNION ALL.
        // Wrapped in a subselect so $sTable is a valid FROM clause for
        // data_tables_init. Keep column order identical to $aColumns.
        $prefix = db_prefix();
        $unionSql = '('
            . 'SELECT '
            . "'log' AS source, "
            . 'id, store_id, level, event, '
            . 'created_at AS ts, '
            . 'correlation_id, '
            . 'context_json AS detail '
            . "FROM {$prefix}woocommerce_log "
            . 'UNION ALL '
            . 'SELECT '
            . "'webhook' AS source, "
            . 'id, store_id, '
            . "CASE WHEN signature_ok = 0 OR processed = 0 THEN 'warn' ELSE 'info' END AS level, "
            . "CONCAT(resource, '.', topic) AS event, "
            . 'received_at AS ts, '
            . 'NULL AS correlation_id, '
            . 'COALESCE(error, payload_hash) AS detail '
            . "FROM {$prefix}woocommerce_webhook_log"
            . ') AS combined';

        $aColumns = [
            'ts',             // 0
            'level',          // 1
            'source',         // 2
            'event',          // 3
            'store_id',       // 4
            'correlation_id', // 5
            'id',             // 6 — placeholder for actions cell
        ];

        $sIndexColumn = 'id';

        $where = [];
        if ($filtersWhere = $this->getWhereFromRules()) {
            $where[] = $filtersWhere;
        }

        $result = data_tables_init(
            $aColumns,
            $sIndexColumn,
            $unionSql,
            /* joins */ [],
            $where,
            /* additionalSelect */ ['detail']
        );

        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $row = [];

            // col 0 — timestamp.
            $row[] = e((string) ($aRow['ts'] ?? ''));

            // col 1 — level pill (info / warn / error).
            $level = (string) ($aRow['level'] ?? 'info');
            $variant = match ($level) {
                'error' => 'failed',
                'warn'  => 'on-hold',
                default => 'completed',
            };
            $row[] = '<span class="woo-status-pill woo-status-pill--' . e($variant) . '">'
                . e($level) . '</span>';

            // col 2 — source (log vs webhook).
            $row[] = e((string) ($aRow['source'] ?? ''));

            // col 3 — event name (with copy-to-clipboard correlation_id).
            $event = e((string) ($aRow['event'] ?? ''));
            $row[] = '<code class="tw-text-xs">' . $event . '</code>';

            // col 4 — store id.
            $storeId = (int) ($aRow['store_id'] ?? 0);
            $row[] = $storeId > 0 ? '#' . $storeId : '<span class="text-muted">—</span>';

            // col 5 — correlation id (click-to-copy).
            $cid = (string) ($aRow['correlation_id'] ?? '');
            if ($cid !== '') {
                $row[] = '<a href="#" class="woo-cid" data-clipboard-text="' . e($cid) . '" '
                    . 'title="' . e(_l('woocommerce_click_to_copy')) . '">'
                    . '<code class="tw-text-xs">' . e(substr($cid, 0, 8)) . '…</code></a>';
            } else {
                $row[] = '<span class="text-muted">—</span>';
            }

            // col 6 — view context button.
            $source = (string) ($aRow['source'] ?? 'log');
            $rowId  = (int) ($aRow['id'] ?? 0);
            $row[] = '<button type="button" class="btn btn-default btn-xs woo-log-row__more" '
                . 'data-source="' . e($source) . '" data-id="' . $rowId . '" '
                . 'aria-label="' . e(_l('woocommerce_view_context')) . '">'
                . '<i class="fa fa-eye" aria-hidden="true"></i></button>';

            $output['aaData'][] = $row;
        }

        return $output;
    })
    ->setRules($rules);
