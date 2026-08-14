/**
 * Orders bulk-action toolbar.
 *
 * Watches the per-row .woo-bulk-check checkboxes that the orders DataTable
 * renders in column 0. When ≥1 row is checked, reveals #wooBulkBar and
 * wires Convert + Mark-Completed buttons to the matching controller
 * endpoints. Each button collects the selected cache-row ids, fires one
 * POST per id (sequenced so a partial failure doesn't take down the
 * whole batch), then surfaces an aggregate WooToast and reloads the
 * table.
 *
 * Backend endpoints (CSRF-excluded in config/csrf_exclude_uris.php):
 *   - POST woocommerce/woocommerce/bulk_convert
 *   - POST woocommerce/woocommerce/bulk_mark_completed
 */
(function () {
    'use strict';

    var $bar      = $('#wooBulkBar');
    var $count    = $('#wooBulkCount');
    var $checkAll = $('#wooBulkCheckAll');
    var $table    = $('.table-woo-orders');

    function checkedRows() {
        return $table.find('.woo-bulk-check:checked');
    }

    function refreshBar() {
        var n = checkedRows().length;
        $count.text(String(n));
        $bar.toggle(n > 0);
        // Sync the header check-all to "all visible rows checked".
        var $rows = $table.find('.woo-bulk-check');
        $checkAll.prop('checked', $rows.length > 0 && $rows.length === n);
    }

    // Live binding — DataTables redraws the tbody on filter / sort /
    // paginate, so we delegate the change handler to the table root.
    $table.on('change', '.woo-bulk-check', refreshBar);

    $checkAll.on('change', function () {
        var on = $(this).prop('checked');
        $table.find('.woo-bulk-check').prop('checked', on);
        refreshBar();
    });

    $('#wooBulkClear').on('click', function () {
        $table.find('.woo-bulk-check').prop('checked', false);
        $checkAll.prop('checked', false);
        refreshBar();
    });

    function reloadTable() {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('.table-woo-orders')) {
            $table.DataTable().ajax.reload(null, false);
        } else {
            window.location.reload();
        }
    }

    function runBulk(url, label, filterFn) {
        var $boxes = checkedRows();
        if (filterFn) {
            $boxes = $boxes.filter(function () { return filterFn($(this)); });
        }
        if ($boxes.length === 0) {
            if (window.WooToast) window.WooToast.warning(label.empty);
            return;
        }
        if (!window.confirm(label.confirm.replace('{n}', $boxes.length))) {
            return;
        }

        var ids = $boxes.map(function () { return $(this).data('id'); }).get();
        var ok = 0, fail = 0;

        $bar.find('button').prop('disabled', true);

        function next(i) {
            if (i >= ids.length) {
                $bar.find('button').prop('disabled', false);
                if (window.WooToast) {
                    if (fail === 0) {
                        window.WooToast.success(label.success.replace('{ok}', ok));
                    } else {
                        window.WooToast.warning(
                            label.partial
                                .replace('{ok}', ok)
                                .replace('{fail}', fail)
                        );
                    }
                }
                reloadTable();
                return;
            }
            window.WooFetch.post(url, { id: ids[i] })
                .then(function (r) { return r.json().catch(function () { return {}; }); })
                .then(function (j) {
                    if (j && j.success) ok++; else fail++;
                })
                .catch(function () { fail++; })
                .then(function () { next(i + 1); });
        }
        next(0);
    }

    $('#wooBulkConvert').on('click', function () {
        runBulk(
            admin_url + 'woocommerce/woocommerce/bulk_convert',
            {
                empty:   'Select unconverted orders first.',
                confirm: 'Convert {n} order(s) to invoices?',
                success: 'Converted {ok} order(s) successfully.',
                partial: 'Converted {ok}, {fail} failed — see logs.'
            },
            function ($cb) {
                // Skip rows that already have an invoice.
                return parseInt($cb.data('invoice-id'), 10) === 0;
            }
        );
    });

    $('#wooBulkComplete').on('click', function () {
        runBulk(
            admin_url + 'woocommerce/woocommerce/bulk_mark_completed',
            {
                empty:   'Select non-completed orders first.',
                confirm: 'Mark {n} order(s) as completed in WooCommerce?',
                success: 'Marked {ok} order(s) completed.',
                partial: 'Updated {ok}, {fail} failed — see logs.'
            },
            function ($cb) {
                return String($cb.data('status')) !== 'completed';
            }
        );
    });
})();
