<?php
/**
 * Customers list (T6.9) — App_table + Vue <app-filters>.
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
            'title'    => _l('woocommerce_customers'),
            'subtitle' => _l('woocommerce_customers_subtitle'),
            'icon'     => 'fa fa-users',
            'crumbs'   => [
                ['label' => _l('woocommerce'),           'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_customers'), 'url' => null],
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
                    <button type="button" id="wooBulkImport"
                            class="btn btn-primary btn-sm">
                        <i class="fa fa-download mright3" aria-hidden="true"></i>
                        <?= html_escape(_l('woocommerce_bulk_import_customers')); ?>
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
                        ['name' => '', 'th_attrs' => ['style' => 'width:48px;']],
                        _l('woocommerce_name'),
                        _l('woocommerce_email'),
                        _l('woocommerce_role'),
                        _l('woocommerce_username'),
                        _l('woocommerce_perfex_client'),
                        ['name' => _l('options'), 'th_attrs' => ['style' => 'width:64px;']],
                    ],
                    'woo-customers',
                    ['table-customers']
                ); ?>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable(
            '.table-woo-customers',
            admin_url + 'woocommerce/woocommerce/customers_table',
            // Non-searchable / non-sortable: checkbox (0), avatar (1), perfex link (6), actions (7).
            [0, 1, 6, 7],
            [0, 1, 6, 7],
            undefined,
            // Default by name ascending (col 2).
            [2, 'asc']
        );
    });
</script>
<script src="<?= module_dir_url('woocommerce', 'assets/js/customers_bulk.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
</body>
</html>
