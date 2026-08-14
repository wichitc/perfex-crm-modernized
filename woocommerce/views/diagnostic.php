<?php
/**
 * Diagnostic snapshot (T6.12) — what support pastes into a ticket.
 *
 * @var array<string, mixed> $snapshot
 */
defined('BASEPATH') or exit('No direct script access allowed');

init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_diagnostic'),
            'subtitle' => _l('woocommerce_diagnostic_subtitle'),
            'icon'     => 'fa fa-stethoscope',
            'crumbs'   => [
                ['label' => _l('woocommerce'),            'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_diagnostic'), 'url' => null],
            ],
            'primary_action' => [
                'label' => '📋 ' . _l('woocommerce_copy_as_text'),
                'attrs' => ['id' => 'wooDiagCopy', 'class' => 'btn btn-primary'],
            ],
        ]); ?>

        <div class="row">
            <div class="col-md-8">
                <div class="woo-card">
                    <div class="woo-card__header">
                        <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_environment')); ?></h3>
                    </div>
                    <div class="woo-meta-grid">
                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_php')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo html_escape((string) $snapshot['php_version']); ?></div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_perfex')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo html_escape((string) $snapshot['perfex_version']); ?></div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_module_version')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo html_escape((string) $snapshot['module_version']); ?></div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_app_enc_key')); ?></div>
                        <div class="woo-meta-grid__val">
                            <?php if ($snapshot['app_enc_key_set']): ?>
                                <i class="fa fa-check-circle text-success" aria-hidden="true"></i> <?php echo html_escape(_l('woocommerce_set')); ?>
                            <?php else: ?>
                                <i class="fa fa-times-circle text-danger" aria-hidden="true"></i> <?php echo html_escape(_l('woocommerce_not_set')); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="woo-card">
                    <div class="woo-card__header">
                        <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_data_volumes')); ?></h3>
                    </div>
                    <div class="woo-meta-grid">
                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_stores')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo (int) $snapshot['store_count']; ?></div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_jobs_pending')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo (int) $snapshot['jobs_pending']; ?></div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_jobs_quarantined')); ?></div>
                        <div class="woo-meta-grid__val">
                            <?php if ((int) $snapshot['jobs_quarantined'] > 0): ?>
                                <span class="text-danger"><?php echo (int) $snapshot['jobs_quarantined']; ?></span>
                            <?php else: ?>
                                <?php echo (int) $snapshot['jobs_quarantined']; ?>
                            <?php endif; ?>
                        </div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_log_rows')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo (int) $snapshot['log_rows']; ?></div>

                        <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_webhook_log_rows')); ?></div>
                        <div class="woo-meta-grid__val"><?php echo (int) $snapshot['webhook_log_rows']; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="woo-card">
                    <div class="woo-card__header">
                        <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_what_support_will_see')); ?></h3>
                    </div>
                    <p class="text-muted small">
                        <?php echo html_escape(_l('woocommerce_diag_explainer')); ?>
                    </p>
                </div>
            </div>
        </div>

        <?php foreach ($snapshot['stores'] as $store): ?>
            <div class="woo-card">
                <div class="woo-card__header">
                    <h3 class="woo-card__title">
                        <?php echo html_escape((string) $store['name']); ?>
                        <span class="text-muted small">— <?php echo html_escape((string) $store['url_host']); ?></span>
                    </h3>
                    <?php $this->load->view('components/_status_pill', [
                        'status' => $store['is_active'] ? 'completed' : 'failed',
                        'label'  => $store['is_active'] ? _l('woocommerce_active') : _l('woocommerce_inactive'),
                    ]); ?>
                </div>

                <div class="woo-meta-grid">
                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_store_id')); ?></div>
                    <div class="woo-meta-grid__val">#<?php echo (int) $store['store_id']; ?></div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_verify_ssl')); ?></div>
                    <div class="woo-meta-grid__val">
                        <?php if ($store['verify_ssl']): ?>
                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                        <?php else: ?>
                            <i class="fa fa-times text-warning" aria-hidden="true"></i>
                            <span class="text-muted small">(<?php echo html_escape(_l('woocommerce_disabled_for_self_signed')); ?>)</span>
                        <?php endif; ?>
                    </div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_pages_per_tick')); ?></div>
                    <div class="woo-meta-grid__val"><?php echo (int) $store['pages_per_tick']; ?></div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_last_cron_tick')); ?></div>
                    <div class="woo-meta-grid__val">
                        <?php echo $store['last_cron_tick'] !== null
                            ? html_escape((string) $store['last_cron_tick']) . ' (' . html_escape(time_ago((string) $store['last_cron_tick'])) . ')'
                            : '<span class="text-muted">—</span>'; ?>
                    </div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('total_orders')); ?></div>
                    <div class="woo-meta-grid__val"><?php echo (int) $store['order_count']; ?></div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('all_products')); ?></div>
                    <div class="woo-meta-grid__val"><?php echo (int) $store['product_count']; ?></div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_customers')); ?></div>
                    <div class="woo-meta-grid__val"><?php echo (int) $store['customer_count']; ?></div>
                </div>

                <hr>

                <h4 class="text-muted small mtop15"><?php echo html_escape(_l('woocommerce_webhook_health_7d')); ?></h4>
                <?php $h = $store['webhook_health']; ?>
                <div class="woo-meta-grid">
                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_received')); ?></div>
                    <div class="woo-meta-grid__val"><?php echo (int) $h['total_received']; ?></div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_signature_ok')); ?></div>
                    <div class="woo-meta-grid__val">
                        <span class="text-success"><?php echo (int) $h['signature_ok']; ?></span>
                        <?php if ((int) $h['signature_failed'] > 0): ?>
                            / <span class="text-danger"><?php echo (int) $h['signature_failed']; ?> <?php echo html_escape(_l('woocommerce_failed')); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_processed_ok')); ?></div>
                    <div class="woo-meta-grid__val">
                        <span class="text-success"><?php echo (int) $h['processed']; ?></span>
                        <?php if ((int) $h['processed_failed'] > 0): ?>
                            / <span class="text-danger"><?php echo (int) $h['processed_failed']; ?> <?php echo html_escape(_l('woocommerce_failed')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <p class="text-muted small">
            <?php echo html_escape(_l('woocommerce_generated_at')); ?>: <?php echo html_escape((string) $snapshot['generated_at']); ?>
        </p>

        <script>
            (function () {
                var btn = document.getElementById('wooDiagCopy');
                if (!btn) return;
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    fetch('<?php echo admin_url('woocommerce/diagnostic/snapshot'); ?>', { credentials: 'same-origin' })
                        .then(function (r) { return r.text(); })
                        .then(function (txt) {
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(txt).then(function () {
                                    btn.textContent = '✓ <?php echo addslashes(_l('woocommerce_copied')); ?>';
                                    setTimeout(function () { btn.innerHTML = '📋 <?php echo addslashes(_l('woocommerce_copy_as_text')); ?>'; }, 2000);
                                });
                            } else {
                                window.prompt('<?php echo addslashes(_l('woocommerce_copy_manually')); ?>', txt);
                            }
                        });
                });
            })();
        </script>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
