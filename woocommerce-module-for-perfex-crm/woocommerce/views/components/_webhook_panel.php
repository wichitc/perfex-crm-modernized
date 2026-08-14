<?php
/**
 * Webhook generation + validation panel (T6.10).
 *
 * Embedded inside the store wizard's step 4 *and* rendered as its own
 * page (`Stores::webhooks`). The two contexts share the same DOM; the
 * `embedded` flag just suppresses the page header so the wizard keeps
 * a single H2.
 *
 * Lives entirely client-side past first render: the generate / refresh
 * / delete actions all hit AJAX endpoints on Stores controller and the
 * status table re-renders from the response.
 *
 * @var \WooCommerce\Repositories\StoreDTO $store
 * @var bool                                $embedded
 */
defined('BASEPATH') or exit('No direct script access allowed');

$embedded = (bool) ($embedded ?? false);
$storeId  = (int) $store->storeId;
?>
<div class="woo-webhook-panel"
     data-store-id="<?= $storeId; ?>"
     data-status-url="<?= admin_url('woocommerce/stores/webhooks_status/' . $storeId); ?>"
     data-generate-url="<?= admin_url('woocommerce/stores/webhooks_generate/' . $storeId); ?>"
     data-delete-url="<?= admin_url('woocommerce/stores/webhooks_delete/' . $storeId); ?>">

    <?php if (! $embedded): ?>
        <h3 class="woo-webhook-panel__title">
            <i class="fa fa-bolt mright5" aria-hidden="true"></i>
            <?= html_escape(_l('woocommerce_webhooks_for_store', $store->name)); ?>
        </h3>
    <?php endif; ?>

    <p class="text-muted">
        <?= html_escape(_l('woocommerce_webhook_panel_help')); ?>
    </p>

    <fieldset class="woo-fieldset">
        <legend><?= html_escape(_l('woocommerce_webhook_topics')); ?></legend>

        <div class="checkbox checkbox-primary">
            <input id="woo-wh-orders" type="checkbox" data-topic="orders" checked>
            <label for="woo-wh-orders">
                <?= html_escape(_l('woocommerce_webhook_topic_orders')); ?>
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input id="woo-wh-products" type="checkbox" data-topic="products" checked>
            <label for="woo-wh-products">
                <?= html_escape(_l('woocommerce_webhook_topic_products')); ?>
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input id="woo-wh-customers" type="checkbox" data-topic="customers" checked>
            <label for="woo-wh-customers">
                <?= html_escape(_l('woocommerce_webhook_topic_customers')); ?>
            </label>
        </div>
    </fieldset>

    <div class="woo-webhook-panel__actions">
        <button type="button" class="btn btn-primary" data-action="generate">
            <i class="fa fa-bolt mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_generate_webhooks')); ?>
        </button>
        <button type="button" class="btn btn-default" data-action="validate">
            <i class="fa fa-refresh mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_validate_webhooks')); ?>
        </button>
        <span class="woo-webhook-panel__feedback" aria-live="polite"></span>
    </div>

    <div class="table-responsive woo-webhook-panel__table-wrap">
        <table class="table woo-webhook-panel__table">
            <thead>
                <tr>
                    <th><?= html_escape(_l('woocommerce_webhook_topic')); ?></th>
                    <th><?= html_escape(_l('status')); ?></th>
                    <th><?= html_escape(_l('woocommerce_webhook_deliveries')); ?></th>
                    <th><?= html_escape(_l('woocommerce_webhook_last_delivery')); ?></th>
                    <th><?= html_escape(_l('woocommerce_webhook_sig_ok_fail')); ?></th>
                    <th><?= html_escape(_l('options')); ?></th>
                </tr>
            </thead>
            <tbody data-rows>
                <tr data-empty>
                    <td colspan="6" class="text-center text-muted">
                        <?= html_escape(_l('woocommerce_webhook_panel_empty')); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php /* webhooks.js is loaded by the parent view AFTER init_tail() so
        it can rely on `window.WooFetch` (set up in woomodule.js).
        Emitting it here would race ahead of woomodule.js. */ ?>
