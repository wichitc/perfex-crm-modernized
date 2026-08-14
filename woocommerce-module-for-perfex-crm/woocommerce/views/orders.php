<?php
/**
 * Orders list (T6.5) — App_table + Vue <app-filters>.
 *
 * @var list<\WooCommerce\Repositories\StoreDTO> $stores
 * @var ?int $active_store_id
 * @var App_table $table
 */
defined('BASEPATH') or exit('No direct script access allowed');

init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_orders'),
            'subtitle' => _l('woocommerce_orders_subtitle'),
            'icon'     => 'fa fa-list-alt',
            'crumbs'   => [
                ['label' => _l('woocommerce'),        'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_orders'), 'url' => null],
            ],
            'stores'          => $stores,
            'active_store_id' => $active_store_id,
        ]); ?>

        <div class="panel_s">
            <div class="panel-body">
                <div class="tw-mb-3 clearfix">
                    <div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5 rtl:tw-mr-1.5 rtl:tw-ml-0">
                        <app-filters
                            id="<?= $table->id(); ?>"
                            view="<?= $table->viewName(); ?>"
                            :saved-filters="<?= $table->filtersJs(); ?>"
                            :available-rules="<?= $table->rulesJs(); ?>">
                        </app-filters>
                    </div>
                </div>

                <div id="wooBulkBar"
                     class="tw-flex tw-flex-wrap tw-items-center tw-gap-2 tw-mb-3 tw-p-2 tw-rounded tw-bg-blue-50 tw-border tw-border-blue-200"
                     style="display:none;">
                    <span class="tw-font-medium">
                        <span id="wooBulkCount">0</span>
                        <?= html_escape(_l('woocommerce_bulk_selected')); ?>
                    </span>
                    <span class="tw-flex-1"></span>
                    <button type="button" id="wooBulkConvert"
                            class="btn btn-primary btn-sm">
                        <i class="fa fa-magic mright3" aria-hidden="true"></i>
                        <?= html_escape(_l('woocommerce_bulk_convert')); ?>
                    </button>
                    <button type="button" id="wooBulkComplete"
                            class="btn btn-success btn-sm">
                        <i class="fa fa-check mright3" aria-hidden="true"></i>
                        <?= html_escape(_l('woocommerce_bulk_mark_completed')); ?>
                    </button>
                    <button type="button" id="wooBulkClear"
                            class="btn btn-default btn-sm">
                        <?= html_escape(_l('woocommerce_bulk_clear')); ?>
                    </button>
                </div>

                <?php render_datatable(
                    [
                        [
                            'name'     => '<div class="checkbox checkbox-primary" style="margin:0;">'
                                . '<input type="checkbox" id="wooBulkCheckAll">'
                                . '<label for="wooBulkCheckAll"></label>'
                                . '</div>',
                            'th_attrs' => ['style' => 'width:32px;', 'class' => 'not-export'],
                        ],
                        _l('woocommerce_order_number'),
                        _l('woocommerce_customer'),
                        _l('status'),
                        _l('woocommerce_currency', 'Currency'),
                        ['name' => _l('woocommerce_total'), 'th_attrs' => ['class' => 'text-right']],
                        _l('woocommerce_invoice'),
                        _l('date'),
                        ['name' => _l('options'), 'th_attrs' => ['style' => 'width:64px;']],
                    ],
                    'woo-orders',
                    ['table-orders']
                ); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable(
            '.table-woo-orders',
            admin_url + 'woocommerce/woocommerce/orders_table',
            // Non-searchable / non-sortable: checkbox (0), invoice cell (6), actions (8).
            [0, 6, 8],
            [0, 6, 8],
            undefined,
            // Default newest-first by date_created (col 7).
            [7, 'desc']
        );
    });
</script>
<script src="<?= module_dir_url('woocommerce', 'assets/js/orders_bulk.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
</body>
</html>
