<?php
/**
 * Order detail (T6.6) — split-pane layout: data on the left, actions
 * on the right. Live order data comes from the WC REST API so line
 * items are visible (the cache table only carries the summary).
 *
 * @var \WooCommerce\Repositories\StoreDTO $store
 * @var ?array<string, mixed>              $cache_row
 * @var ?array<string, mixed>              $live
 * @var ?string                            $api_error
 */
defined('BASEPATH') or exit('No direct script access allowed');

if (! function_exists('woo_money')) {
    function woo_money(mixed $amount, string $currency = ''): string
    {
        $val = is_string($amount) ? trim($amount) : (string) $amount;
        if ($val === '' || ! is_numeric($val)) { return '—'; }
        return $val . ($currency !== '' ? ' ' . $currency : '');
    }
}

$wooId    = (int) ($live['id'] ?? $cache_row['order_id'] ?? 0);
$number   = (string) ($live['number'] ?? $cache_row['order_number'] ?? ('#' . $wooId));
$status   = (string) ($live['status'] ?? $cache_row['status'] ?? '');
$currency = (string) ($live['currency'] ?? $cache_row['currency'] ?? '');
$total    = (string) ($live['total'] ?? $cache_row['total'] ?? '0');

$billing  = is_array($live['billing']  ?? null) ? $live['billing']  : [];
$shipping = is_array($live['shipping'] ?? null) ? $live['shipping'] : [];

$lineItems    = is_array($live['line_items']     ?? null) ? $live['line_items']     : [];
$shippingLines = is_array($live['shipping_lines'] ?? null) ? $live['shipping_lines'] : [];
$taxLines      = is_array($live['tax_lines']     ?? null) ? $live['tax_lines']      : [];
$feeLines      = is_array($live['fee_lines']     ?? null) ? $live['fee_lines']      : [];

$customerId  = (int) ($live['customer_id'] ?? $cache_row['customer_id'] ?? 0);
$isGuest     = $customerId === 0;
$invoiceId   = (int) ($cache_row['invoice_id'] ?? 0);
$isPaid      = $invoiceId > 0;

init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php $this->load->view('components/_page_header', [
            'title'    => sprintf(_l('woocommerce_order_n'), $number),
            'icon'     => 'fa fa-shopping-cart',
            'crumbs'   => [
                ['label' => _l('woocommerce'),        'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_orders'), 'url' => admin_url('woocommerce/orders')],
                ['label' => $number, 'url' => null],
            ],
        ]); ?>

        <?php if ($api_error !== null): ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'warn',
                'icon'     => 'fa fa-exclamation-triangle',
                'title'    => _l('woocommerce_order_live_unavailable') . ':',
                'body'     => $api_error,
            ]); ?>
        <?php endif; ?>

        <?php if ($isGuest): ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'info',
                'icon'     => 'fa fa-user-circle-o',
                'title'    => _l('woocommerce_guest_customer') . '.',
                'body'     => _l('woocommerce_guest_customer_explainer'),
            ]); ?>
        <?php endif; ?>

        <?php if ($isPaid): ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'success',
                'icon'     => 'fa fa-check-circle',
                'title'    => sprintf(_l('woocommerce_order_already_converted'), '#' . $invoiceId) . '.',
                'body'     => _l('woocommerce_order_already_converted_body'),
            ]); ?>
        <?php endif; ?>

        <?php if (! $isPaid && in_array($status, ['processing', 'completed'], true)): ?>
            <?php $this->load->view('components/_banner', [
                'severity' => 'info',
                'icon'     => 'fa fa-credit-card',
                'title'    => _l('woocommerce_payment_mode_marker') . ':',
                'body'     => _l('woocommerce_payment_mode_marker_explainer'),
            ]); ?>
        <?php endif; ?>

        <div class="row">
            <!-- ============================== LEFT: data pane -->
            <div class="col-md-8">

                <!-- Line items -->
                <div class="woo-card">
                    <div class="woo-card__header">
                        <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_line_items')); ?></h3>
                        <?php $this->load->view('components/_status_pill', ['status' => $status]); ?>
                    </div>

                    <?php if ($lineItems === [] && $live === null): ?>
                        <?php $this->load->view('components/_skeleton', ['variant' => 'row', 'rows' => 3]); ?>
                    <?php elseif ($lineItems === []): ?>
                        <p class="text-muted"><?php echo html_escape(_l('woocommerce_no_line_items')); ?></p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table woo-line-items">
                                <thead>
                                    <tr>
                                        <th class="woo-line-items__thumb"></th>
                                        <th><?php echo html_escape(_l('woocommerce_item')); ?></th>
                                        <th class="text-right"><?php echo html_escape(_l('woocommerce_qty')); ?></th>
                                        <th class="text-right"><?php echo html_escape(_l('woocommerce_total')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lineItems as $item):
                                        $img = is_array($item['image'] ?? null) ? ($item['image']['src'] ?? '') : '';
                                        $name = (string) ($item['name'] ?? '');
                                        $qty  = (int)    ($item['quantity'] ?? 0);
                                        $tot  = (string) ($item['total']    ?? '0');
                                        $sku  = (string) ($item['sku'] ?? '');
                                    ?>
                                        <tr>
                                            <td>
                                                <?php if ($img !== ''): ?>
                                                    <img src="<?php echo html_escape($img); ?>" alt="" loading="lazy"
                                                         class="woo-line-items__thumb-img">
                                                <?php else: ?>
                                                    <div class="woo-line-items__thumb-placeholder" aria-hidden="true">
                                                        <i class="fa fa-cube" aria-hidden="true"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo html_escape($name); ?></strong>
                                                <?php if ($sku !== ''): ?>
                                                    <div class="text-muted small"><?php echo html_escape(_l('woocommerce_product_sku')); ?>: <?php echo html_escape($sku); ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right"><?php echo $qty; ?></td>
                                            <td class="text-right"><?php echo html_escape(woo_money($tot, $currency)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($shippingLines as $sl): ?>
                                        <tr>
                                            <td><i class="fa fa-truck text-muted" aria-hidden="true"></i></td>
                                            <td>
                                                <strong><?php echo html_escape((string) ($sl['method_title'] ?? _l('woocommerce_shipping'))); ?></strong>
                                                <div class="text-muted small"><?php echo html_escape(_l('woocommerce_shipping')); ?></div>
                                            </td>
                                            <td class="text-right">1</td>
                                            <td class="text-right"><?php echo html_escape(woo_money((string) ($sl['total'] ?? '0'), $currency)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($feeLines as $fl): ?>
                                        <tr>
                                            <td><i class="fa fa-plus-circle text-muted" aria-hidden="true"></i></td>
                                            <td>
                                                <strong><?php echo html_escape((string) ($fl['name'] ?? _l('woocommerce_fee'))); ?></strong>
                                                <div class="text-muted small"><?php echo html_escape(_l('woocommerce_fee')); ?></div>
                                            </td>
                                            <td class="text-right">1</td>
                                            <td class="text-right"><?php echo html_escape(woo_money((string) ($fl['total'] ?? '0'), $currency)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <?php $totalTax = is_array($taxLines) ? array_sum(array_map(static fn($t) => (float) ($t['tax_total'] ?? 0), $taxLines)) : (float) ($live['total_tax'] ?? 0); ?>
                                    <?php if ($totalTax > 0): ?>
                                        <tr><th colspan="3" class="text-right text-muted"><?php echo html_escape(_l('woocommerce_tax_total')); ?></th>
                                            <td class="text-right text-muted"><?php echo html_escape(woo_money(number_format($totalTax, 2, '.', ''), $currency)); ?></td></tr>
                                    <?php endif; ?>
                                    <tr><th colspan="3" class="text-right"><?php echo html_escape(_l('woocommerce_grand_total')); ?></th>
                                        <td class="text-right"><strong><?php echo html_escape(woo_money($total, $currency)); ?></strong></td></tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Customer card -->
                <div class="woo-card">
                    <div class="woo-card__header">
                        <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_customer')); ?></h3>
                        <?php if ($isGuest): ?>
                            <?php $this->load->view('components/_status_pill', ['status' => 'guest']); ?>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-muted small"><?php echo html_escape(_l('woocommerce_billing')); ?></h4>
                            <address>
                                <strong><?php echo html_escape(trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? ''))); ?></strong><br>
                                <?php if (! empty($billing['company'])): ?><?php echo html_escape($billing['company']); ?><br><?php endif; ?>
                                <?php echo html_escape((string) ($billing['address_1'] ?? '')); ?><br>
                                <?php if (! empty($billing['address_2'])): ?><?php echo html_escape($billing['address_2']); ?><br><?php endif; ?>
                                <?php echo html_escape((string) ($billing['city'] ?? '')); ?>,
                                <?php echo html_escape((string) ($billing['state'] ?? '')); ?>
                                <?php echo html_escape((string) ($billing['postcode'] ?? '')); ?><br>
                                <?php echo html_escape((string) ($billing['country'] ?? '')); ?>
                                <?php if (! empty($billing['email'])): ?>
                                    <br><i class="fa fa-envelope-o mright3" aria-hidden="true"></i><a href="mailto:<?php echo html_escape($billing['email']); ?>"><?php echo html_escape($billing['email']); ?></a>
                                <?php endif; ?>
                                <?php if (! empty($billing['phone'])): ?>
                                    <br><i class="fa fa-phone mright3" aria-hidden="true"></i><?php echo html_escape($billing['phone']); ?>
                                <?php endif; ?>
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-muted small"><?php echo html_escape(_l('woocommerce_shipping')); ?></h4>
                            <address>
                                <?php if (! empty($shipping['address_1'])): ?>
                                    <strong><?php echo html_escape(trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? ''))); ?></strong><br>
                                    <?php echo html_escape((string) ($shipping['address_1'] ?? '')); ?><br>
                                    <?php echo html_escape((string) ($shipping['city'] ?? '')); ?>,
                                    <?php echo html_escape((string) ($shipping['state'] ?? '')); ?>
                                    <?php echo html_escape((string) ($shipping['postcode'] ?? '')); ?><br>
                                    <?php echo html_escape((string) ($shipping['country'] ?? '')); ?>
                                <?php else: ?>
                                    <span class="text-muted"><?php echo html_escape(_l('woocommerce_same_as_billing')); ?></span>
                                <?php endif; ?>
                            </address>
                        </div>
                    </div>
                </div>

                <!-- Dates timeline -->
                <div class="woo-card">
                    <div class="woo-card__header">
                        <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_timeline')); ?></h3>
                    </div>
                    <div class="woo-meta-grid">
                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_created')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo html_escape((string) ($live['date_created'] ?? $cache_row['date_created'] ?? '—')); ?></div>

                        <?php if (! empty($live['date_paid'])): ?>
                            <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_paid')); ?></div>
                            <div class="woo-meta-grid__val"><?php echo html_escape((string) $live['date_paid']); ?></div>
                        <?php endif; ?>

                        <?php if (! empty($live['date_completed'])): ?>
                            <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_completed')); ?></div>
                            <div class="woo-meta-grid__val"><?php echo html_escape((string) $live['date_completed']); ?></div>
                        <?php endif; ?>

                        <?php if (! empty($live['payment_method_title'])): ?>
                            <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_payment_method')); ?></div>
                            <div class="woo-meta-grid__val">
                                <?php echo html_escape((string) $live['payment_method_title']); ?>
                                <?php if (! empty($live['transaction_id'])): ?>
                                    <span class="text-muted small">— <?php echo html_escape((string) $live['transaction_id']); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ============================== RIGHT: action drawer -->
            <div class="col-md-4">
                <div class="woo-action-drawer">
                    <?php if ($isPaid): ?>
                        <div class="woo-action-drawer__group">
                            <h4 class="woo-action-drawer__heading"><?php echo html_escape(_l('woocommerce_invoice')); ?></h4>
                            <a class="btn btn-success woo-action-drawer__action"
                               href="<?php echo admin_url('invoices/list_invoices/' . $invoiceId); ?>">
                                <i class="fa fa-file-text-o mright5" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_view_invoice')); ?> #<?php echo $invoiceId; ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="woo-action-drawer__group">
                            <h4 class="woo-action-drawer__heading"><?php echo html_escape(_l('woocommerce_conversion')); ?></h4>
                            <?php if (staff_can('create', 'woocommerce')): ?>
                                <button type="button" class="btn btn-primary woo-action-drawer__action"
                                        data-toggle="modal" data-target="#wooConvertModal"
                                        data-preview-url="<?php echo admin_url('woocommerce/woocommerce_invoice/preview/' . (int) $store->storeId . '/' . $wooId); ?>">
                                    <i class="fa fa-magic mright5" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_convert_to_invoice')); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (staff_can('edit', 'woocommerce')): ?>
                        <div class="woo-action-drawer__group">
                            <h4 class="woo-action-drawer__heading"><?php echo html_escape(_l('status')); ?></h4>
                            <form method="post" action="<?php echo admin_url('woocommerce/update_woo/' . (int) $store->storeId); ?>">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="order_id" value="<?php echo $wooId; ?>">
                                <select name="status" class="form-control" aria-label="<?php echo html_escape(_l('status')); ?>">
                                    <?php foreach (['pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed'] as $opt): ?>
                                        <option value="<?php echo html_escape($opt); ?>" <?php echo $opt === $status ? 'selected' : ''; ?>>
                                            <?php echo html_escape(ucfirst(str_replace('-', ' ', $opt))); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-default woo-action-drawer__action mtop10">
                                    <?php echo html_escape(_l('woocommerce_update_status')); ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div class="woo-action-drawer__group">
                        <h4 class="woo-action-drawer__heading"><?php echo html_escape(_l('woocommerce_other')); ?></h4>
                        <a class="btn btn-default woo-action-drawer__action"
                           href="<?php echo admin_url('woocommerce/order/' . (int) $store->storeId . '/' . $wooId); ?>">
                            <i class="fa fa-refresh mright5" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_refresh')); ?>
                        </a>
                    </div>

                    <?php if (staff_can('delete', 'woocommerce')): ?>
                        <div class="woo-action-drawer__group">
                            <h4 class="woo-action-drawer__heading"><?php echo html_escape(_l('woocommerce_danger_zone')); ?></h4>
                            <form method="post" action="<?php echo admin_url('woocommerce/delete/' . (int) $store->storeId); ?>"
                                  onsubmit="return confirm('<?php echo html_escape(_l('woocommerce_confirm_delete_order')); ?>');">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="order_id" value="<?php echo $wooId; ?>">
                                <button class="btn btn-danger woo-action-drawer__action" type="submit">
                                    <i class="fa fa-trash mright5" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_delete_on_woo')); ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Convert-to-invoice confirm modal (T6.6 / T5.5). Loads the preview
     payload via AJAX, then POSTs to create_invoice on confirm. -->
<?php if (! $isPaid && staff_can('create', 'woocommerce')): ?>
    <div class="modal fade" id="wooConvertModal" tabindex="-1" role="dialog" aria-labelledby="wooConvertTitle">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post"
                  action="<?php echo admin_url('woocommerce/woocommerce_invoice/create_invoice/' . (int) $store->storeId . '/' . $wooId); ?>"
                  class="modal-content">
                <?php woo_csrf_input(); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo html_escape(_l('close')); ?>"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="wooConvertTitle">
                        <i class="fa fa-magic mright5" aria-hidden="true"></i>
                        <?php echo html_escape(_l('woocommerce_convert_to_invoice')); ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        <?php echo html_escape(_l('woocommerce_convert_modal_help')); ?>
                    </p>

                    <div id="wooConvertPreview" class="tw-text-sm">
                        <p class="text-muted">
                            <i class="fa fa-spinner fa-spin mright5" aria-hidden="true"></i>
                            <?php echo html_escape(_l('woocommerce_loading_preview')); ?>
                        </p>
                    </div>

                    <details class="tw-mt-3">
                        <summary class="text-muted tw-text-xs tw-cursor-pointer">
                            <?php echo html_escape(_l('woocommerce_convert_show_raw')); ?>
                        </summary>
                        <pre id="wooConvertPreviewRaw"
                             class="tw-text-xs tw-bg-slate-50 tw-p-3 tw-rounded tw-max-h-64 tw-overflow-auto tw-mt-2">…</pre>
                    </details>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo html_escape(_l('cancel')); ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check mright5" aria-hidden="true"></i>
                        <?php echo html_escape(_l('woocommerce_convert_confirm')); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php init_tail(); ?>
<script>
    /* Order detail Convert flow (T6.6 step 2):
       - Fetch the previewConvert JSON from Woocommerce_invoice::preview
       - Render a human-readable summary plus a collapsed raw-JSON view
         (useful when the support team has to debug a mapping)
       - The form's submit button POSTs to create_invoice */
    $(function () {
        function escapeHtml(s) {
            return $('<div>').text(s == null ? '' : String(s)).html();
        }
        function fmtMoney(n, currency) {
            if (n == null || n === '') return '—';
            var num = parseFloat(n);
            if (isNaN(num)) return escapeHtml(n);
            return num.toFixed(2) + (currency ? ' ' + escapeHtml(currency) : '');
        }

        function renderPreview(json) {
            var container = $('#wooConvertPreview');
            if (!json || typeof json !== 'object') {
                container.html('<p class="text-muted">' + escapeHtml('No preview available.') + '</p>');
                return;
            }

            // Already converted — short-circuit with a banner.
            if (json.existing_invoice_id) {
                container.html(
                    '<div class="alert alert-info tw-mb-0">' +
                    '<i class="fa fa-info-circle mright5" aria-hidden="true"></i>' +
                    'This order was already converted to invoice <strong>#' +
                    parseInt(json.existing_invoice_id, 10) + '</strong>. Confirming will link instead of creating a new invoice.' +
                    '</div>'
                );
                return;
            }

            var header     = json.header || {};
            var lineItems  = Array.isArray(json.line_items) ? json.line_items : [];
            var clientId   = parseInt(json.client_id, 10) || 0;
            var isGuest    = !!json.guest;
            var modeId     = parseInt(json.payment_mode_id, 10) || 0;
            var currency   = header.currency || header.currency_code || '';

            var html = '';

            // Outcome summary card.
            html += '<div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-3 tw-mb-4">';
            html += '<div><strong>Client</strong><br>';
            if (isGuest) {
                html += '<span class="woo-status-pill woo-status-pill--guest">Guest</span> ';
                html += clientId > 0
                    ? '— existing client #' + clientId
                    : '— a placeholder client will be created on confirm';
            } else {
                html += clientId > 0
                    ? 'Existing Perfex client #' + clientId
                    : '<span class="text-warning">No matching Perfex client — will be created from billing details</span>';
            }
            html += '</div>';

            html += '<div><strong>Payment mode</strong><br>';
            html += '<span class="woo-status-pill woo-status-pill--guest">WooCommerce</span> ';
            html += '— mode id #' + modeId + ' (system-managed)';
            html += '</div>';

            // Header summary (date, due, total).
            ['date', 'duedate', 'total', 'subtotal'].forEach(function (k) {
                if (header[k] === undefined || header[k] === null || header[k] === '') return;
                var label = k === 'duedate' ? 'Due date'
                    : k.charAt(0).toUpperCase() + k.slice(1);
                var val = (k === 'total' || k === 'subtotal')
                    ? fmtMoney(header[k], currency)
                    : escapeHtml(header[k]);
                html += '<div><strong>' + escapeHtml(label) + '</strong><br>' + val + '</div>';
            });
            html += '</div>';

            // Line items table.
            if (lineItems.length > 0) {
                html += '<div class="tw-mb-2"><strong>Line items (' + lineItems.length + ')</strong></div>';
                html += '<div class="table-responsive"><table class="table table-condensed">';
                html += '<thead><tr>'
                    + '<th>Description</th>'
                    + '<th class="text-right">Qty</th>'
                    + '<th class="text-right">Rate</th>'
                    + '<th class="text-right">Total</th>'
                    + '</tr></thead><tbody>';
                lineItems.forEach(function (li) {
                    var qty  = li.qty != null ? li.qty : (li.quantity || 1);
                    var rate = li.rate != null ? li.rate : (li.price || 0);
                    var tot  = li.total != null ? li.total : (parseFloat(qty) * parseFloat(rate));
                    html += '<tr>'
                        + '<td>' + escapeHtml(li.description || li.name || li.long_description || '') + '</td>'
                        + '<td class="text-right">' + escapeHtml(qty) + '</td>'
                        + '<td class="text-right">' + fmtMoney(rate, currency) + '</td>'
                        + '<td class="text-right">' + fmtMoney(tot, currency) + '</td>'
                        + '</tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<p class="text-muted">No line items in the projected invoice.</p>';
            }

            container.html(html);
        }

        $('#wooConvertModal').on('show.bs.modal', function (e) {
            var trigger = e.relatedTarget;
            var url = trigger && trigger.getAttribute('data-preview-url');
            if (!url) return;
            var $preview = $('#wooConvertPreview');
            var $raw     = $('#wooConvertPreviewRaw');
            $preview.html('<p class="text-muted"><i class="fa fa-spinner fa-spin mright5" aria-hidden="true"></i><?php echo addslashes(_l('woocommerce_loading_preview')); ?></p>');
            $raw.text('…');

            window.WooFetch.post(url, {})
                .then(function (r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(function (json) {
                    $raw.text(JSON.stringify(json, null, 2));
                    renderPreview(json);
                })
                .catch(function (err) {
                    $preview.html(
                        '<div class="alert alert-warning tw-mb-0">' +
                        '<i class="fa fa-exclamation-triangle mright5" aria-hidden="true"></i>' +
                        '<?php echo addslashes(_l('woocommerce_preview_unavailable')); ?>' +
                        '</div>'
                    );
                    $raw.text(err && err.message ? err.message : 'preview fetch failed');
                });
        });
    });
</script>
</body>
</html>
