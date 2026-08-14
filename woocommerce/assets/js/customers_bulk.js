/**
 * Customers bulk-import toolbar.
 *
 * Mirrors the orders-bulk pattern: the per-row checkboxes carry the
 * cache-row id, the toolbar appears once ≥1 row is selected, and the
 * Import button POSTs one row at a time to the
 * woocommerce/bulk_import_customer endpoint. Already-linked rows render
 * the checkbox `disabled` server-side so the user can't queue a
 * re-import.
 */
(function () {
    'use strict';

    var $bar      = $('#wooBulkBar');
    var $count    = $('#wooBulkCount');
    var $checkAll = $('#wooBulkCheckAll');
    var $table    = $('.table-woo-customers');

    function checkedRows() {
        return $table.find('.woo-bulk-check:checked');
    }

    function refreshBar() {
        var n = checkedRows().length;
        $count.text(String(n));
        $bar.toggle(n > 0);
        var $rows = $table.find('.woo-bulk-check:not(:disabled)');
        $checkAll.prop('checked', $rows.length > 0 && $rows.length === n);
    }

    $table.on('change', '.woo-bulk-check', refreshBar);

    $checkAll.on('change', function () {
        var on = $(this).prop('checked');
        $table.find('.woo-bulk-check:not(:disabled)').prop('checked', on);
        refreshBar();
    });

    $('#wooBulkClear').on('click', function () {
        $table.find('.woo-bulk-check').prop('checked', false);
        $checkAll.prop('checked', false);
        refreshBar();
    });

    function reloadTable() {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('.table-woo-customers')) {
            $table.DataTable().ajax.reload(null, false);
        } else {
            window.location.reload();
        }
    }

    $('#wooBulkImport').on('click', function () {
        var $boxes = checkedRows();
        if ($boxes.length === 0) {
            if (window.WooToast) window.WooToast.warning('No unlinked customers selected.');
            return;
        }
        if (!window.confirm('Import ' + $boxes.length + ' customer(s) into Perfex?')) {
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
                        window.WooToast.success('Imported ' + ok + ' customer(s) successfully.');
                    } else {
                        window.WooToast.warning(
                            'Imported ' + ok + ', ' + fail + ' failed — see logs.'
                        );
                    }
                }
                reloadTable();
                return;
            }
            window.WooFetch.post(
                admin_url + 'woocommerce/woocommerce/bulk_import_customer',
                { id: ids[i] }
            )
                .then(function (r) { return r.json().catch(function () { return {}; }); })
                .then(function (j) { if (j && j.success) ok++; else fail++; })
                .catch(function () { fail++; })
                .then(function () { next(i + 1); });
        }
        next(0);
    });
})();
