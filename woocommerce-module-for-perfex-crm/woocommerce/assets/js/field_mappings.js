/*!
 * Field Mappings editor (T6.4).
 *
 * Wires per-tab actions (add row, load preset, reset tab, preflight)
 * and per-row save / revert / delete. AJAX endpoints all live on
 * Stores controller — see modules/woocommerce/controllers/Stores.php.
 *
 * Routing: data-action="add-row|save-row|revert-row|delete-row|load-preset|reset-tab|preflight"
 *          data-entity on the tab + data-id on the row identify the target.
 */
(function () {
    'use strict';

    var editor = document.querySelector('.woo-mapping-editor');
    if (!editor) return;

    var storeId = parseInt(editor.getAttribute('data-store-id'), 10) || 0;
    var feedbackEls = editor.querySelectorAll('.woo-mapping-editor__feedback');

    function setFeedback(scope, msg, kind) {
        var el = (scope || editor).querySelector('.woo-mapping-editor__feedback');
        if (!el) return;
        el.textContent = msg || '';
        el.className = 'woo-mapping-editor__feedback tw-ml-auto tw-text-sm' +
            (kind ? ' tw-text-' + kind : '');
    }

    function postForm(url, data) {
        return window.WooFetch.post(url, data || {})
            .then(function (r) { return r.json().catch(function () { return null; }); });
    }

    function rowValues(row) {
        var sel = row.querySelectorAll('select[data-field]');
        var out = {};
        sel.forEach(function (s) { out[s.getAttribute('data-field')] = s.value; });
        var def = row.querySelector('[data-field="default_value"]');
        var req = row.querySelector('[data-field="is_required"]');
        out.default_value = def ? def.value : '';
        out.is_required = req && req.checked ? 1 : 0;
        return out;
    }

    function addEmptyRow(entity) {
        var tab = editor.querySelector('[data-entity="' + entity + '"][role="tabpanel"]');
        if (!tab) return;
        var tbody = tab.querySelector('[data-rows]');
        if (!tbody) return;
        // Strip empty-state row if present.
        var emptyTd = tbody.querySelector('td[colspan]');
        if (emptyTd) tbody.innerHTML = '';

        // Clone the first existing row's structure (if any) — but this
        // is the empty-tab path, so we ask the server to insert with
        // blank values then re-render.
        postForm(window.admin_url + 'woocommerce/stores/add_mapping/' + entity + '/' + storeId, {
            wc_field: '__placeholder__',
            perfex_field: '__placeholder__',
            is_required: 0,
            default_value: ''
        }).then(function (json) {
            if (json && json.success) {
                window.location.reload();
            } else {
                setFeedback(tab, (json && json.error) || 'error', 'red-600');
            }
        });
    }

    editor.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action]');
        if (!btn) return;
        var action = btn.getAttribute('data-action');
        var entity = btn.getAttribute('data-entity') ||
            (btn.closest('[data-entity]') && btn.closest('[data-entity]').getAttribute('data-entity'));
        var tab = btn.closest('[role="tabpanel"]');
        if (!entity) return;

        if (action === 'add-row') {
            addEmptyRow(entity);
            return;
        }
        if (action === 'load-preset') {
            postForm(window.admin_url + 'woocommerce/stores/load_preset/' + entity + '/' + storeId, {})
                .then(function (json) {
                    if (json && json.success) window.location.reload();
                    else setFeedback(tab, (json && json.error) || 'error', 'red-600');
                });
            return;
        }
        if (action === 'reset-tab') {
            if (!window.confirm(btn.dataset.confirm || 'Reset this tab to predefined?')) return;
            postForm(window.admin_url + 'woocommerce/stores/reset_tab/' + entity + '/' + storeId, {})
                .then(function (json) {
                    if (json && json.success) window.location.reload();
                    else setFeedback(tab, (json && json.error) || 'error', 'red-600');
                });
            return;
        }
        if (action === 'preflight') {
            postForm(window.admin_url + 'woocommerce/stores/preflight/' + entity + '/' + storeId, {})
                .then(function (json) {
                    var out = document.getElementById('wooMappingPreflightOut');
                    if (out) out.textContent = JSON.stringify((json && json.report) || json, null, 2);
                    if (window.jQuery) window.jQuery('#wooMappingPreflightModal').modal('show');
                });
            return;
        }

        var row = btn.closest('[data-row]');
        if (!row) return;
        var origin = row.getAttribute('data-origin');
        var values = rowValues(row);

        if (action === 'save-row') {
            // Predefined → override; override → update override; custom → update custom row.
            if (origin === 'predefined') {
                postForm(window.admin_url + 'woocommerce/stores/override_mapping/' + entity + '/' + storeId, {
                    wc_field:        row.getAttribute('data-original-wc'),
                    perfex_field:    row.getAttribute('data-original-perfex'),
                    new_wc_field:    values.wc_field,
                    new_perfex_field: values.perfex_field,
                    is_required:     values.is_required,
                    default_value:   values.default_value
                }).then(function (json) {
                    if (json && json.success) window.location.reload();
                    else setFeedback(tab, (json && json.error) || 'save_failed', 'red-600');
                });
            } else {
                // override or custom — persist via add_mapping (replace) flow.
                // Simpler path: delete then add. But to avoid a flicker we
                // rely on the override path; for custom rows we need a
                // dedicated update endpoint. v1 ships save-then-reload.
                postForm(window.admin_url + 'woocommerce/stores/delete_mapping/' + entity + '/' + storeId, {
                    id: row.getAttribute('data-id')
                }).then(function () {
                    return postForm(window.admin_url + 'woocommerce/stores/add_mapping/' + entity + '/' + storeId, values);
                }).then(function (json) {
                    if (json && json.success) window.location.reload();
                    else setFeedback(tab, (json && json.error) || 'save_failed', 'red-600');
                });
            }
            return;
        }

        if (action === 'revert-row') {
            postForm(window.admin_url + 'woocommerce/stores/revert_mapping/' + entity + '/' + storeId, {
                wc_field:     row.getAttribute('data-original-wc'),
                perfex_field: row.getAttribute('data-original-perfex')
            }).then(function (json) {
                if (json && json.success) window.location.reload();
                else setFeedback(tab, (json && json.error) || 'error', 'red-600');
            });
            return;
        }

        if (action === 'delete-row') {
            if (!window.confirm(btn.dataset.confirm || 'Delete this mapping?')) return;
            postForm(window.admin_url + 'woocommerce/stores/delete_mapping/' + entity + '/' + storeId, {
                id: row.getAttribute('data-id')
            }).then(function (json) {
                if (json && json.success) window.location.reload();
                else setFeedback(tab, (json && json.error) || 'error', 'red-600');
            });
        }
    });
})();
