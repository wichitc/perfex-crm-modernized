<?php
/**
 * Products list (T6.7) — Perfex CRM native DataTable + Vue
 * `<app-filters>` widget.
 *
 * Server-side rules + filter UI are bound via `App_table` (registered
 * in `helpers/woocommerce_helper.php` on `admin_init`); rows come
 * over AJAX from `Woocommerce::products_table` which runs the file at
 * `views/tables/products.php` through `App_table::find->output()`.
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
            'title'    => _l('woocommerce_products'),
            'subtitle' => _l('woocommerce_products_subtitle'),
            'icon'     => 'fa fa-cubes',
            'crumbs'   => [
                ['label' => _l('woocommerce'),          'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_products'), 'url' => null],
            ],
            'stores'          => $stores,
            'active_store_id' => $active_store_id,
        ]); ?>

        <div class="panel_s">
            <div class="panel-body">

                <!-- Perfex's Vue-powered filter chip UI, right-aligned
                     above the table to mirror the expenses / projects
                     layout convention. Saved filters persist per-staff
                     in `tblsaved_table_filters`; the available rules
                     come from `App_table_filter::new(...)` calls in
                     `views/tables/products.php`. -->
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

                <?php render_datatable(
                    [
                        ['name' => '',                                'th_attrs' => ['style' => 'width:56px;']],
                        _l('woocommerce_name'),
                        _l('woocommerce_product_sku'),
                        _l('woocommerce_type'),
                        _l('status'),
                        _l('woocommerce_stock'),
                        ['name' => _l('woocommerce_price'), 'th_attrs' => ['class' => 'text-right']],
                        ['name' => _l('woocommerce_sales'), 'th_attrs' => ['class' => 'text-right']],
                        _l('woocommerce_linked'),
                    ],
                    'woo-products',
                    ['table-products']
                ); ?>
            </div>
        </div>
    </div>
</div>

<div id="woo-product-modal-host"></div>

<?php init_tail(); ?>
<script src="<?= module_dir_url('woocommerce', 'assets/js/products.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
<script>
    /* The `<app-filters>` widget pushes selected rule values into the
       AJAX payload automatically once the table is bound (id matches
       the `App_table` registration id). The 0/5/8 columns are
       thumbnail / stock / linked-action — neither searchable nor
       sortable. Default sort is the name column ascending. */
    $(function () {
        initDataTable(
            '.table-woo-products',
            admin_url + 'woocommerce/woocommerce/products_table',
            [0, 5, 8],
            [0, 5, 8],
            undefined,
            [1, 'asc']
        );
    });
</script>
</body>
</html>
