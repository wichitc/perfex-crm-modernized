<?php
/**
 * Customer detail (T6.9).
 *
 * @var int $store_id
 * @var ?array<string, mixed> $cache_row
 * @var list<array<string, mixed>> $recent_orders
 * @var ?array<string, mixed> $linked_client
 */
defined('BASEPATH') or exit('No direct script access allowed');

$row = is_array($cache_row) ? $cache_row : [];
$first  = (string) ($row['first_name'] ?? '');
$last   = (string) ($row['last_name']  ?? '');
$name   = trim("$first $last");
$email  = (string) ($row['email'] ?? '');
$role   = (string) ($row['role']  ?? '');
$avatar = (string) ($row['avatar_url'] ?? '');
$wooId  = (int) ($row['woo_customer_id'] ?? 0);
$isGuest = $role === 'guest' || $wooId === 0;
$linkedId = (int) ($row['userid'] ?? 0);

init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php $this->load->view('components/_page_header', [
            'title'    => $name !== '' ? $name : ($email !== '' ? $email : '#' . $wooId),
            'subtitle' => null,
            'icon'     => 'fa fa-user',
            'crumbs'   => [
                ['label' => _l('woocommerce'),           'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_customers'), 'url' => admin_url('woocommerce/customers')],
                ['label' => $name !== '' ? $name : ('#' . $wooId), 'url' => null],
            ],
        ]); ?>

        <?php if ($cache_row === null): ?>
            <?php $this->load->view('components/_empty_state', [
                'icon'  => 'fa fa-user',
                'title' => _l('woocommerce_customer_not_found'),
                'body'  => _l('woocommerce_customer_not_found_body'),
            ]); ?>
        <?php else: ?>

            <?php if ($isGuest): ?>
                <?php $this->load->view('components/_banner', [
                    'severity' => 'info',
                    'icon'     => 'fa fa-user-circle-o',
                    'title'    => _l('woocommerce_guest_customer') . '.',
                    'body'     => _l('woocommerce_guest_customer_explainer'),
                ]); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <!-- Customer card -->
                    <div class="woo-card">
                        <div class="woo-card__header">
                            <div style="display:flex; align-items:center; gap: var(--woo-space-3);">
                                <?php if ($avatar !== ''): ?>
                                    <img src="<?php echo html_escape($avatar); ?>" alt="" loading="lazy"
                                         style="width:64px; height:64px; border-radius:50%;">
                                <?php endif; ?>
                                <div>
                                    <h3 class="woo-card__title">
                                        <?php echo html_escape($name); ?>
                                        <?php if ($isGuest): ?>
                                            <?php $this->load->view('components/_status_pill', ['status' => 'guest']); ?>
                                        <?php endif; ?>
                                    </h3>
                                    <?php if ($email !== ''): ?>
                                        <a href="mailto:<?php echo html_escape($email); ?>"><?php echo html_escape($email); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="woo-meta-grid">
                            <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_woo_id')); ?></div>
                            <div class="woo-meta-grid__val">#<?php echo $wooId; ?></div>

                            <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_role')); ?></div>
                            <div class="woo-meta-grid__val"><?php echo html_escape($role !== '' ? ucfirst($role) : '—'); ?></div>

                            <?php if (! empty($row['username'])): ?>
                                <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_username')); ?></div>
                                <div class="woo-meta-grid__val"><?php echo html_escape((string) $row['username']); ?></div>
                            <?php endif; ?>

                            <?php if (! empty($row['phone'])): ?>
                                <div class="woo-meta-grid__key"><?php echo html_escape(_l('woocommerce_phone')); ?></div>
                                <div class="woo-meta-grid__val"><?php echo html_escape((string) $row['phone']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent orders -->
                    <div class="woo-card">
                        <div class="woo-card__header">
                            <h3 class="woo-card__title"><?php echo html_escape(_l('woocommerce_recent_orders')); ?></h3>
                        </div>

                        <?php if ($recent_orders === []): ?>
                            <p class="text-muted"><?php echo html_escape(_l('woocommerce_no_orders_for_customer')); ?></p>
                        <?php else: ?>
                            <ul class="woo-recent-orders">
                                <?php foreach ($recent_orders as $o):
                                    $oid = (int) ($o['order_id'] ?? 0);
                                ?>
                                    <li>
                                        <a href="<?php echo admin_url('woocommerce/order/' . $store_id . '/' . $oid); ?>">
                                            <?php echo html_escape((string) ($o['order_number'] ?? '#' . $oid)); ?>
                                        </a>
                                        <?php $this->load->view('components/_status_pill', ['status' => (string) ($o['status'] ?? '')]); ?>
                                        <span class="text-muted">
                                            <?php echo html_escape((string) ($o['total'] ?? '0')); ?>
                                            <?php echo html_escape((string) ($o['currency'] ?? '')); ?>
                                            ·
                                            <?php echo html_escape(! empty($o['date_created']) ? time_ago((string) $o['date_created']) : '—'); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action drawer -->
                <div class="col-md-4">
                    <div class="woo-action-drawer">
                        <div class="woo-action-drawer__group">
                            <h4 class="woo-action-drawer__heading"><?php echo html_escape(_l('woocommerce_perfex_client')); ?></h4>
                            <?php if ($linkedId > 0): ?>
                                <a class="btn btn-success woo-action-drawer__action"
                                   href="<?php echo admin_url('clients/client/' . $linkedId); ?>">
                                    <i class="fa fa-external-link mright5" aria-hidden="true"></i>
                                    <?php echo html_escape(_l('woocommerce_open_perfex_client')); ?> #<?php echo $linkedId; ?>
                                </a>
                                <?php if (is_array($linked_client)): ?>
                                    <p class="text-muted small mtop10">
                                        <?php echo html_escape((string) ($linked_client['company'] ?? '')); ?>
                                    </p>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (staff_can('create', 'woocommerce')): ?>
                                    <form method="post"
                                          action="<?php echo admin_url('woocommerce/woocommerce_invoice/import_customer/' . $store_id . '/' . $wooId); ?>">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                               value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-primary woo-action-drawer__action">
                                            <i class="fa fa-download mright5" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_import_to_perfex')); ?>
                                        </button>
                                    </form>
                                    <p class="text-muted small mtop10">
                                        <?php echo html_escape(_l('woocommerce_import_explainer')); ?>
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
