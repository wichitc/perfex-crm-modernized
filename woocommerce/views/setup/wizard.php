<?php
/**
 * First-run setup wizard (T6.13).
 *
 * Single-page view that switches body content based on `$progress`.
 * Each step posts to `Setup::advance` to bump progress; "Skip" sets
 * the next step number, "Next/Action" runs the step's payload then
 * advances. Step 5 redirects to the stores list — wizard complete.
 *
 * @var int  $progress
 * @var list<\WooCommerce\Repositories\StoreDTO> $stores
 */
defined('BASEPATH') or exit('No direct script access allowed');

$steps = [
    1 => _l('woocommerce_setup_step_welcome'),
    2 => _l('woocommerce_setup_step_connect'),
    3 => _l('woocommerce_setup_step_mappings'),
    4 => _l('woocommerce_setup_step_webhooks'),
];

init_head();
?>
<div id="wrapper">
    <div class="content">
        <?php $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_setup_title'),
            'subtitle' => _l('woocommerce_setup_subtitle'),
            'icon'     => 'fa fa-magic',
            'crumbs'   => [
                ['label' => _l('woocommerce'),       'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_setup'), 'url' => null],
            ],
        ]); ?>

        <div class="woo-card tw-max-w-3xl tw-mx-auto">
            <ol class="tw-flex tw-flex-wrap tw-items-center tw-gap-2 tw-mb-6 tw-list-none tw-pl-0">
                <?php foreach ($steps as $n => $label):
                    $state = $n === $progress ? 'is-current' : ($n < $progress ? 'is-done' : '');
                ?>
                    <li class="woo-wizard__step <?= $state; ?> tw-flex-1">
                        <span class="woo-wizard__num"><?= $n; ?></span>
                        <span class="woo-wizard__label tw-text-sm"><?= html_escape($label); ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>

            <?php if ($progress === 1): ?>
                <h3><?= html_escape(_l('woocommerce_setup_welcome_title')); ?></h3>
                <p class="text-muted"><?= html_escape(_l('woocommerce_setup_welcome_body')); ?></p>
                <ul class="tw-list-disc tw-ml-6 tw-space-y-1 tw-mb-6">
                    <li><?= html_escape(_l('woocommerce_setup_welcome_b1')); ?></li>
                    <li><?= html_escape(_l('woocommerce_setup_welcome_b2')); ?></li>
                    <li><?= html_escape(_l('woocommerce_setup_welcome_b3')); ?></li>
                </ul>

                <div class="checkbox checkbox-primary tw-mb-4">
                    <input id="woo-telemetry-optin" name="telemetry_opt_in" type="checkbox" value="1"
                           form="woo-setup-welcome">
                    <label for="woo-telemetry-optin">
                        <?= html_escape(_l('woocommerce_telemetry_label')); ?>
                    </label>
                    <p class="help-block tw-text-xs tw-ml-6">
                        <?= html_escape(_l('woocommerce_telemetry_explainer')); ?>
                    </p>
                </div>

                <form id="woo-setup-welcome" method="post" action="<?= admin_url('woocommerce/setup/advance'); ?>" class="tw-flex tw-justify-end">
                    <?php woo_csrf_input(); ?>
                    <input type="hidden" name="step" value="2">
                    <button type="submit" class="btn btn-primary">
                        <?= html_escape(_l('woocommerce_setup_lets_start')); ?>
                        <i class="fa fa-arrow-right mleft5" aria-hidden="true"></i>
                    </button>
                </form>

            <?php elseif ($progress === 2): ?>
                <h3><?= html_escape(_l('woocommerce_setup_connect_title')); ?></h3>
                <p class="text-muted"><?= html_escape(_l('woocommerce_setup_connect_body')); ?></p>

                <?php if ($stores === []): ?>
                    <a href="<?= admin_url('woocommerce/stores/create'); ?>" class="btn btn-primary">
                        <i class="fa fa-plus mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_setup_open_store_wizard')); ?>
                    </a>
                <?php else: ?>
                    <p><?= html_escape(_l('woocommerce_setup_store_already_connected')); ?></p>
                <?php endif; ?>

                <form method="post" action="<?= admin_url('woocommerce/setup/advance'); ?>" class="tw-flex tw-justify-between tw-mt-6">
                    <?php woo_csrf_input(); ?>
                    <button type="submit" name="step" value="1" class="btn btn-link">
                        <i class="fa fa-arrow-left mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_wizard_back')); ?>
                    </button>
                    <div class="tw-flex tw-gap-2">
                        <button type="submit" name="step" value="3" class="btn btn-link">
                            <?= html_escape(_l('woocommerce_setup_skip')); ?>
                        </button>
                        <button type="submit" name="step" value="3" class="btn btn-primary">
                            <?= html_escape(_l('woocommerce_wizard_next')); ?>
                            <i class="fa fa-arrow-right mleft5" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>

            <?php elseif ($progress === 3): ?>
                <h3><?= html_escape(_l('woocommerce_setup_mappings_title')); ?></h3>
                <p class="text-muted"><?= html_escape(_l('woocommerce_setup_mappings_body')); ?></p>

                <form method="post" action="<?= admin_url('woocommerce/setup/load_presets'); ?>"
                      class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-4">
                    <?php woo_csrf_input(); ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-magic mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_setup_load_presets')); ?>
                    </button>
                </form>

                <form method="post" action="<?= admin_url('woocommerce/setup/advance'); ?>" class="tw-flex tw-justify-between">
                    <?php woo_csrf_input(); ?>
                    <button type="submit" name="step" value="2" class="btn btn-link">
                        <i class="fa fa-arrow-left mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_wizard_back')); ?>
                    </button>
                    <div class="tw-flex tw-gap-2">
                        <button type="submit" name="step" value="4" class="btn btn-link">
                            <?= html_escape(_l('woocommerce_setup_skip')); ?>
                        </button>
                        <button type="submit" name="step" value="4" class="btn btn-default">
                            <?= html_escape(_l('woocommerce_wizard_next')); ?>
                            <i class="fa fa-arrow-right mleft5" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>

            <?php elseif ($progress === 4): ?>
                <h3><?= html_escape(_l('woocommerce_setup_webhooks_title')); ?></h3>
                <p class="text-muted"><?= html_escape(_l('woocommerce_setup_webhooks_body')); ?></p>

                <?php if ($stores !== []):
                    $store = $stores[count($stores) - 1];
                ?>
                    <a href="<?= admin_url('woocommerce/stores/webhooks/' . (int) $store->storeId); ?>"
                       class="btn btn-primary">
                        <i class="fa fa-bolt mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_setup_open_webhook_panel')); ?>
                    </a>
                <?php endif; ?>

                <form method="post" action="<?= admin_url('woocommerce/setup/advance'); ?>" class="tw-flex tw-justify-between tw-mt-6">
                    <?php woo_csrf_input(); ?>
                    <button type="submit" name="step" value="3" class="btn btn-link">
                        <i class="fa fa-arrow-left mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_wizard_back')); ?>
                    </button>
                    <button type="submit" name="step" value="5" class="btn btn-primary">
                        <i class="fa fa-check mright5" aria-hidden="true"></i><?= html_escape(_l('woocommerce_setup_finish')); ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
