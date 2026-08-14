/*!
 * Products list + product edit modal (T6.7 list, T6.8 modal).
 *
 * - Wires "Edit" buttons in the products list to fetch the modal
 *   markup from `Woocommerce::product_modal` and inject + show it.
 * - Inside the modal: live image-URL preview, manage-stock toggle
 *   reveals quantity/status fields, and inline validation runs on
 *   each input + on submit.
 * - On successful save the modal stays open with a success toast,
 *   then closes after 2 s and reloads the list to pick up the new
 *   row.
 */
(function () {
    'use strict';

    var injectionWrap = document.getElementById('woo-product-modal-host');
    if (!injectionWrap) {
        // Host is appended on demand so a missing div doesn't break
        // the products list page.
        injectionWrap = document.createElement('div');
        injectionWrap.id = 'woo-product-modal-host';
        document.body.appendChild(injectionWrap);
    }

    // jQuery delegation on body — survives DataTable row redraws and
    // matches Perfex's own JS pattern (see assets/js/projects.js).
    // Native document.addEventListener works but kept failing in some
    // tenant configs where Perfex's other delegated handlers swallow
    // the event before bubbling reaches `document`.
    var $ = window.jQuery;
    if (!$) {
        return; // No jQuery → Perfex admin chrome isn't loaded; nothing to wire.
    }

    $('body').on('click', '[data-woo-edit-product]', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-woo-edit-product');
        if (!url) return;
        window.WooFetch.get(url)
            .then(function (r) { return r.text(); })
            .then(function (html) {
                injectionWrap.innerHTML = html;
                bindModal();
                $('#wooProductModal').modal('show');
            });
    });

    // Manual product → Perfex item linking (T6.7 step 3 / T5.x gap).
    $('body').on('click', '[data-woo-link-product]', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var linkUrl = $btn.attr('data-woo-link-product');
        if (!linkUrl) return;

        $btn.prop('disabled', true);
        window.WooFetch.post(linkUrl, {})
            .then(function (r) { return r.json(); })
            .then(function (json) {
                $btn.prop('disabled', false);
                if (json && json.success) {
                    if (window.WooToast) {
                        window.WooToast.success(
                            json.reused
                                ? 'Already linked to item #' + json.item_id
                                : 'Linked to new Perfex item #' + json.item_id
                        );
                    }
                    var $dt = $('.table-woo-products');
                    if ($dt.length && $.fn.DataTable && $.fn.DataTable.isDataTable($dt[0])) {
                        $dt.DataTable().ajax.reload(null, false);
                    } else {
                        window.location.reload();
                    }
                } else if (window.WooToast) {
                    window.WooToast.danger((json && json.error) || 'link_failed');
                } else if (window.alert_float) {
                    window.alert_float('warning', (json && json.error) || 'link_failed');
                }
            })
            .catch(function () {
                $btn.prop('disabled', false);
                if (window.WooToast) window.WooToast.danger('link_failed');
            });
    });

    function bindModal() {
        var form = document.getElementById('wooProductForm');
        if (!form) return;

        var imgInput   = form.querySelector('[name="image_url"]');
        var imgPreview = form.querySelector('#woo-prod-image-preview');
        if (imgInput && imgPreview) {
            imgInput.addEventListener('input', function () {
                imgPreview.src = imgInput.value;
                if (imgInput.value) {
                    imgPreview.removeAttribute('hidden');
                } else {
                    imgPreview.setAttribute('hidden', 'hidden');
                }
            });
        }

        var stockToggle  = form.querySelector('[data-toggle-stock]');
        var stockControls = form.querySelector('.woo-stock-controls');
        if (stockToggle && stockControls) {
            var sync = function () {
                stockControls.classList.toggle('tw-hidden', !stockToggle.checked);
            };
            stockToggle.addEventListener('change', sync);
            sync();
        }

        // ---- inline validation -----------------------------------------
        function setError(name, msg) {
            var el = form.querySelector('[data-error-for="' + name + '"]');
            if (!el) return;
            el.textContent = msg || '';
            el.classList.toggle('tw-hidden', !msg);
        }
        function validate() {
            var ok = true;
            var name = form.querySelector('[name="name"]');
            if (!name.value.trim()) { setError('name', 'required'); ok = false; }
            else setError('name', '');
            var sku = form.querySelector('[name="sku"]');
            if (!sku.value.trim()) { setError('sku', 'required'); ok = false; }
            else setError('sku', '');
            var reg = form.querySelector('[name="regular_price"]');
            var sale = form.querySelector('[name="sale_price"]');
            if (reg.value !== '' && isNaN(parseFloat(reg.value))) {
                setError('regular_price', 'must be a number'); ok = false;
            } else setError('regular_price', '');
            if (sale.value !== '') {
                if (isNaN(parseFloat(sale.value))) {
                    setError('sale_price', 'must be a number'); ok = false;
                } else if (reg.value !== '' && parseFloat(sale.value) >= parseFloat(reg.value)) {
                    setError('sale_price', 'must be less than regular price'); ok = false;
                } else setError('sale_price', '');
            } else setError('sale_price', '');
            return ok;
        }
        form.addEventListener('input', validate);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validate()) return;

            var submit   = document.getElementById('wooProductSubmit');
            var feedback = document.getElementById('wooProductFeedback');
            if (submit) submit.disabled = true;
            if (feedback) { feedback.textContent = '…'; feedback.className = 'tw-text-sm tw-mr-2 tw-text-slate-500'; }

            window.WooFetch.post(form.getAttribute('data-action'), new FormData(form))
                .then(function (r) { return r.json(); })
                .then(function (json) {
                    if (submit) submit.disabled = false;
                    if (json && json.success) {
                        if (feedback) {
                            feedback.textContent = '✓ ' + (feedback.dataset.success || 'Saved');
                            feedback.className = 'tw-text-sm tw-mr-2 tw-text-green-600';
                        }
                        setTimeout(function () {
                            if (window.jQuery) window.jQuery('#wooProductModal').modal('hide');
                            window.location.reload();
                        }, 2000);
                        return;
                    }
                    if (feedback) {
                        feedback.textContent = (json && json.error) || 'error';
                        feedback.className = 'tw-text-sm tw-mr-2 tw-text-red-600';
                    }
                })
                .catch(function () {
                    if (submit) submit.disabled = false;
                    if (feedback) {
                        feedback.textContent = 'error';
                        feedback.className = 'tw-text-sm tw-mr-2 tw-text-red-600';
                    }
                });
        });
    }
})();
