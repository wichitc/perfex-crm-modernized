<?php
/**
 * Logs view (T6.11) — App_table + Vue <app-filters>.
 *
 * Filterable union over `tblwoocommerce_log` + `tblwoocommerce_webhook_log`.
 *
 * @var App_table $table
 */
defined('BASEPATH') or exit('No direct script access allowed');

init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_logs'),
            'subtitle' => _l('woocommerce_logs_subtitle'),
            'icon'     => 'fa fa-list',
            'crumbs'   => [
                ['label' => _l('woocommerce'),      'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_logs'), 'url' => null],
            ],
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

                <?php render_datatable(
                    [
                        _l('date'),
                        _l('woocommerce_level'),
                        _l('woocommerce_event'),
                        _l('woocommerce_event'),
                        _l('woocommerce_store'),
                        _l('woocommerce_correlation_id'),
                        ['name' => _l('options'), 'th_attrs' => ['style' => 'width:64px;']],
                    ],
                    'woo-logs',
                    ['table-logs']
                ); ?>
            </div>
        </div>

        <!-- Context modal — opened on click of the row's "view" button. -->
        <div id="wooLogModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= e(_l('woocommerce_log_context')); ?></h4>
                    </div>
                    <div class="modal-body">
                        <pre id="wooLogContextOut" class="tw-text-xs tw-bg-slate-50 tw-p-3 tw-rounded tw-max-h-96 tw-overflow-auto"></pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= e(_l('close')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable(
            '.table-woo-logs',
            admin_url + 'woocommerce/logs/table',
            // Non-searchable: source pill (1), store_id (4), actions (6).
            [1, 4, 6],
            [1, 4, 6],
            undefined,
            // Newest first.
            [0, 'desc']
        );

        // Open the context modal with the full row JSON.
        $('body').on('click', '.woo-log-row__more', function () {
            var src = $(this).attr('data-source');
            var id  = $(this).attr('data-id');
            var url = admin_url + 'woocommerce/logs/context?source='
                + encodeURIComponent(src) + '&id=' + encodeURIComponent(id);
            window.WooFetch.get(url)
                .then(function (r) { return r.json(); })
                .then(function (json) {
                    document.getElementById('wooLogContextOut').textContent =
                        JSON.stringify(json.row || json, null, 2);
                    $('#wooLogModal').modal('show');
                });
        });

        // Click-to-copy correlation_id.
        $('body').on('click', '.woo-cid', function (e) {
            e.preventDefault();
            var t = $(this).attr('data-clipboard-text');
            if (t && navigator.clipboard) navigator.clipboard.writeText(t);
        });
    });
</script>
</body>
</html>
