<?php
/**
 * Store wizard partial (T6.3). Shared by new_store.php and edit_store.php.
 *
 * Multi-step form rendered as four `<section data-step>` panels; the
 * sidebar lists steps and the active panel is toggled by stores.js.
 * The whole thing is one form so a partially-completed wizard can be
 * back/forward'd through without losing values.
 *
 * @var \WooCommerce\Repositories\StoreDTO|null $store
 */
defined('BASEPATH') or exit('No direct script access allowed');

$staffOptions      = $staffOptions     ?? [];
$assignedStaffIds  = $assignedStaffIds ?? [];
$assignedStaffIds  = array_map('intval', $assignedStaffIds);

$isEdit       = $store !== null;
$storeId      = $isEdit ? (int) $store->storeId : 0;
$nameVal      = $isEdit ? html_escape($store->name) : '';
$urlVal       = $isEdit ? html_escape($store->url) : '';
$keyVal       = $isEdit ? html_escape($store->key) : '';
$secretVal    = $isEdit ? html_escape($store->secret) : '';
$verifySsl    = $isEdit ? $store->verifySsl : true;
$isActive     = $isEdit ? $store->isActive : true;
$pagesPerTick = $isEdit ? $store->pagesPerTick : 3;
$autoCust     = $isEdit && $store->autoConvertCustomer;
$autoProd     = $isEdit && $store->autoConvertProduct;
$autoOrd      = $isEdit && $store->autoConvertOrder;
$autoStatuses = $isEdit && $store->autoInvoiceStatuses !== null
    ? array_filter(array_map('trim', explode(',', $store->autoInvoiceStatuses)))
    : [];

/* Woo's canonical order statuses — the autocomplete + multiselect both
 * pull from this list. Source: WooCommerce REST API docs. */
$wooStatuses = [
    'pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed',
];

init_head();
?>
<div id="wrapper">
    <div class="content">
        <?php $this->load->view('components/_page_header', [
            'title'    => $isEdit ? _l('woocommerce_edit_store') : _l('woocommerce_new_store'),
            'subtitle' => _l('woocommerce_store_wizard_subtitle'),
            'icon'     => 'fa fa-shopping-bag',
            'crumbs'   => [
                ['label' => _l('woocommerce'),        'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_stores'), 'url' => admin_url('woocommerce/stores')],
                ['label' => $isEdit ? _l('edit') : _l('woocommerce_new_store'), 'url' => null],
            ],
        ]); ?>

        <form id="wooStoreForm" class="woo-wizard"
              method="post"
              action="<?= admin_url('woocommerce/stores/save'); ?>">
            <?= form_hidden('store_id', (string) $storeId); ?>

            <aside class="woo-wizard__nav" aria-label="<?= html_escape(_l('woocommerce_wizard_steps')); ?>">
                <ol>
                    <li><button type="button" class="woo-wizard__step is-current" data-goto="1">
                        <span class="woo-wizard__num">1</span>
                        <span class="woo-wizard__label"><?= html_escape(_l('woocommerce_wizard_basics')); ?></span>
                    </button></li>
                    <li><button type="button" class="woo-wizard__step" data-goto="2">
                        <span class="woo-wizard__num">2</span>
                        <span class="woo-wizard__label"><?= html_escape(_l('woocommerce_wizard_credentials')); ?></span>
                    </button></li>
                    <li><button type="button" class="woo-wizard__step" data-goto="3">
                        <span class="woo-wizard__num">3</span>
                        <span class="woo-wizard__label"><?= html_escape(_l('woocommerce_wizard_sync_options')); ?></span>
                    </button></li>
                    <li><button type="button" class="woo-wizard__step" data-goto="4">
                        <span class="woo-wizard__num">4</span>
                        <span class="woo-wizard__label"><?= html_escape(_l('woocommerce_wizard_webhooks')); ?></span>
                    </button></li>
                </ol>
            </aside>

            <div class="woo-wizard__panels">

                <section class="woo-wizard__panel is-current" data-step="1" aria-labelledby="step1-title">
                    <h3 id="step1-title"><?= html_escape(_l('woocommerce_wizard_basics')); ?></h3>
                    <p class="text-muted"><?= html_escape(_l('woocommerce_wizard_basics_help')); ?></p>

                    <div class="form-group">
                        <label for="name" class="control-label">
                            <?= html_escape(_l('woocommerce_store_name')); ?> <span aria-hidden="true">*</span>
                        </label>
                        <input id="name" name="name" type="text" class="form-control" required
                               aria-required="true" maxlength="190"
                               value="<?= $nameVal; ?>">
                    </div>

                    <div class="form-group">
                        <label for="url" class="control-label">
                            <?= html_escape(_l('woocommerce_store_url')); ?> <span aria-hidden="true">*</span>
                        </label>
                        <input id="url" name="url" type="url" class="form-control" required
                               aria-required="true" placeholder="https://example.com"
                               value="<?= $urlVal; ?>">
                        <p class="help-block"><?= html_escape(_l('woocommerce_store_url_help')); ?></p>
                    </div>

                    <div class="checkbox checkbox-primary">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                               <?= $isActive ? 'checked' : ''; ?>>
                        <label for="is_active"><?= html_escape(_l('active')); ?></label>
                    </div>

                    <div class="woo-wizard__nav-buttons">
                        <button type="button" class="btn btn-primary" data-next="2">
                            <?= html_escape(_l('woocommerce_wizard_next')); ?> <i class="fa fa-arrow-right mleft5" aria-hidden="true"></i>
                        </button>
                    </div>
                </section>

                <section class="woo-wizard__panel" data-step="2" aria-labelledby="step2-title" hidden>
                    <h3 id="step2-title"><?= html_escape(_l('woocommerce_wizard_credentials')); ?></h3>
                    <p class="text-muted"><?= html_escape(_l('woocommerce_wizard_credentials_help')); ?></p>

                    <div class="form-group">
                        <label for="consumer_key" class="control-label">
                            <?= html_escape(_l('woocommerce_consumer_key')); ?> <span aria-hidden="true">*</span>
                        </label>
                        <input id="consumer_key" name="consumer_key" type="text" class="form-control"
                               required aria-required="true" autocomplete="off"
                               value="<?= $keyVal; ?>">
                    </div>

                    <div class="form-group">
                        <label for="consumer_secret" class="control-label">
                            <?= html_escape(_l('woocommerce_consumer_secret')); ?> <span aria-hidden="true">*</span>
                        </label>
                        <input id="consumer_secret" name="consumer_secret" type="password" class="form-control"
                               required aria-required="true" autocomplete="off"
                               value="<?= $secretVal; ?>">
                    </div>

                    <div class="checkbox checkbox-primary">
                        <input id="verify_ssl" name="verify_ssl" type="checkbox" value="1"
                               <?= $verifySsl ? 'checked' : ''; ?>>
                        <label for="verify_ssl"><?= html_escape(_l('woocommerce_verify_ssl')); ?></label>
                    </div>

                    <div class="checkbox checkbox-primary">
                        <input id="query_auth" name="query_auth" type="checkbox" value="1"
                               <?= ($isEdit && $store->queryAuth) ? 'checked' : ''; ?>>
                        <label for="query_auth"><?= html_escape(_l('woocommerce_query_auth')); ?></label>
                    </div>

                    <div class="woo-wizard__test-row">
                        <button type="button" id="wooTestConnection" class="btn btn-default">
                            <i class="fa fa-plug mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_test_connection')); ?>
                        </button>
                        <span id="wooTestResult" class="woo-test-result" aria-live="polite"></span>
                    </div>

                    <div class="woo-wizard__nav-buttons">
                        <button type="button" class="btn btn-default" data-prev="1">
                            <i class="fa fa-arrow-left mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_wizard_back')); ?>
                        </button>
                        <button type="button" class="btn btn-primary" data-next="3">
                            <?= html_escape(_l('woocommerce_wizard_next')); ?> <i class="fa fa-arrow-right mleft5" aria-hidden="true"></i>
                        </button>
                    </div>
                </section>

                <section class="woo-wizard__panel" data-step="3" aria-labelledby="step3-title" hidden>
                    <h3 id="step3-title"><?= html_escape(_l('woocommerce_wizard_sync_options')); ?></h3>
                    <p class="text-muted"><?= html_escape(_l('woocommerce_wizard_sync_options_help')); ?></p>

                    <div class="form-group">
                        <label for="pages_per_tick" class="control-label">
                            <?= html_escape(_l('woocommerce_pages_per_tick')); ?>
                        </label>
                        <input id="pages_per_tick" name="pages_per_tick" type="number"
                               class="form-control" min="1" max="50"
                               value="<?= (int) $pagesPerTick; ?>">
                        <p class="help-block"><?= html_escape(_l('woocommerce_pages_per_tick_help')); ?></p>
                    </div>

                    <fieldset class="woo-fieldset">
                        <legend><?= html_escape(_l('woocommerce_auto_convert')); ?></legend>

                        <div class="checkbox checkbox-primary">
                            <input id="auto_convert_customer" name="auto_convert_customer" type="checkbox" value="1"
                                   <?= $autoCust ? 'checked' : ''; ?>>
                            <label for="auto_convert_customer"><?= html_escape(_l('woocommerce_auto_convert_customer')); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input id="auto_convert_product" name="auto_convert_product" type="checkbox" value="1"
                                   <?= $autoProd ? 'checked' : ''; ?>>
                            <label for="auto_convert_product"><?= html_escape(_l('woocommerce_auto_convert_product')); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input id="auto_convert_order" name="auto_convert_order" type="checkbox" value="1"
                                   <?= $autoOrd ? 'checked' : ''; ?>>
                            <label for="auto_convert_order"><?= html_escape(_l('woocommerce_auto_convert_order')); ?></label>
                        </div>
                    </fieldset>

                    <div class="form-group">
                        <label for="auto_invoice_statuses" class="control-label">
                            <?= html_escape(_l('woocommerce_auto_invoice_statuses')); ?>
                        </label>
                        <select id="auto_invoice_statuses" name="auto_invoice_statuses[]" class="form-control selectpicker" multiple
                                data-live-search="true" data-actions-box="true">
                            <?php foreach ($wooStatuses as $status): ?>
                                <option value="<?= html_escape($status); ?>"
                                    <?= in_array($status, $autoStatuses, true) ? 'selected' : ''; ?>>
                                    <?= html_escape(_l('woocommerce_status_' . $status, $status)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="help-block"><?= html_escape(_l('woocommerce_auto_invoice_statuses_help')); ?></p>
                    </div>

                    <div class="form-group">
                        <label for="assigned_staff" class="control-label">
                            <?= html_escape(_l('woocommerce_assigned_staff')); ?>
                        </label>
                        <select id="assigned_staff" name="assigned_staff[]"
                                class="form-control selectpicker" multiple
                                data-live-search="true" data-actions-box="true"
                                data-none-selected-text="<?= html_escape(_l('woocommerce_assigned_staff_none')); ?>">
                            <?php foreach ($staffOptions as $opt): ?>
                                <option value="<?= (int) $opt['id']; ?>"
                                    <?= in_array((int) $opt['id'], $assignedStaffIds, true) ? 'selected' : ''; ?>>
                                    <?= html_escape($opt['name']); ?>
                                    <?php if ($opt['email'] !== '' && $opt['email'] !== $opt['name']): ?>
                                        &lt;<?= html_escape($opt['email']); ?>&gt;
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="help-block"><?= html_escape(_l('woocommerce_assigned_staff_help')); ?></p>
                    </div>

                    <div class="woo-wizard__nav-buttons">
                        <button type="button" class="btn btn-default" data-prev="2">
                            <i class="fa fa-arrow-left mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_wizard_back')); ?>
                        </button>
                        <button type="button" class="btn btn-primary" data-next="4">
                            <?= html_escape(_l('woocommerce_wizard_next')); ?> <i class="fa fa-arrow-right mleft5" aria-hidden="true"></i>
                        </button>
                    </div>
                </section>

                <section class="woo-wizard__panel" data-step="4" aria-labelledby="step4-title" hidden>
                    <h3 id="step4-title"><?= html_escape(_l('woocommerce_wizard_webhooks')); ?></h3>
                    <p class="text-muted"><?= html_escape(_l('woocommerce_wizard_webhooks_help')); ?></p>

                    <?php if ($isEdit): ?>
                        <?php $this->load->view('components/_webhook_panel', [
                            'store' => $store,
                            'embedded' => true,
                        ]); ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <?= html_escape(_l('woocommerce_wizard_webhooks_after_save')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="woo-wizard__nav-buttons">
                        <button type="button" class="btn btn-default" data-prev="3">
                            <i class="fa fa-arrow-left mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_wizard_back')); ?>
                        </button>
                        <button type="submit" class="btn btn-primary" id="wooStoreSubmit">
                            <i class="fa fa-check mright5" aria-hidden="true"></i>
                            <?= html_escape($isEdit ? _l('save') : _l('woocommerce_wizard_create_store')); ?>
                        </button>
                    </div>
                </section>

            </div>
        </form>

    </div>
</div>

<?php init_tail(); ?>
<script src="<?= module_dir_url('woocommerce', 'assets/js/stores.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
<?php if ($isEdit): ?>
    <script src="<?= module_dir_url('woocommerce', 'assets/js/webhooks.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
<?php endif; ?>
</body>
</html>
