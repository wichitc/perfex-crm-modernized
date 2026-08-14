<?php
/**
 * Standalone webhook page (T6.10) — accessed from the store card's
 * "Webhooks" action. Reuses the embedded panel component.
 *
 * @var \WooCommerce\Repositories\StoreDTO $store
 */
defined('BASEPATH') or exit('No direct script access allowed');

init_head();
?>
<div id="wrapper">
    <div class="content">
        <?php $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_webhooks_for_store', $store->name),
            'subtitle' => _l('woocommerce_webhook_panel_help'),
            'icon'     => 'fa fa-bolt',
            'crumbs'   => [
                ['label' => _l('woocommerce'),        'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_stores'), 'url' => admin_url('woocommerce/stores')],
                ['label' => $store->name,             'url' => admin_url('woocommerce/stores/edit/' . (int) $store->storeId)],
                ['label' => _l('woocommerce_webhooks'), 'url' => null],
            ],
        ]); ?>

        <div class="woo-card">
            <?php $this->load->view('components/_webhook_panel', [
                'store'    => $store,
                'embedded' => false,
            ]); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?= module_dir_url('woocommerce', 'assets/js/webhooks.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
</body>
</html>
