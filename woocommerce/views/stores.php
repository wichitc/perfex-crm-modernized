<?php
/**
 * Stores list — card grid (T6.2).
 *
 * @var list<\WooCommerce\Repositories\StoreDTO> $stores
 * @var array<int, array<string, mixed>>          $stats_by_store
 * @var ?int                                       $active_store_id
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Mask a store URL for the card body — keep scheme + host, drop the
 * path. The full URL stays in title= so a hover reveals the original.
 */
if (! function_exists('woo_mask_store_url')) {
    function woo_mask_store_url(string $url): string
    {
        $parsed = parse_url($url);
        if (! is_array($parsed) || ! isset($parsed['host'])) {
            return $url;
        }
        $scheme = $parsed['scheme'] ?? 'https';
        return $scheme . '://' . $parsed['host'];
    }
}

init_head();
?>
<div id="wrapper">
    <div class="content">

        <?php
        $primary_action = staff_can('create', 'woocommerce') ? [
            'label' => '+ ' . _l('woocommerce_new_store'),
            'attrs' => [
                'id'    => 'newWooStore',
                'class' => 'btn btn-primary',
                'href'  => admin_url('woocommerce/stores/create'),
            ],
        ] : null;

        $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_stores'),
            'subtitle' => _l('woocommerce_stores_subtitle'),
            'icon'     => 'fa fa-shopping-bag',
            'crumbs'   => [
                ['label' => _l('woocommerce'),        'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_stores'), 'url' => null],
            ],
            'stores'          => $stores,
            'active_store_id' => $active_store_id,
            'primary_action'  => $primary_action,
        ]);
        ?>

        <?php if ($stores === []): ?>
            <?php $this->load->view('components/_empty_state', [
                'icon'   => 'fa fa-shopping-bag',
                'title'  => _l('woocommerce_stores_empty_title'),
                'body'   => _l('woocommerce_stores_empty_body'),
                'action' => $primary_action,
            ]); ?>
        <?php else: ?>
            <div class="woo-store-grid">
                <?php foreach ($stores as $store):
                    $sid = (int) $store->storeId;
                    $stats = $stats_by_store[$sid] ?? [];
                    $isActive = $active_store_id !== null && $sid === (int) $active_store_id;
                    $statusVariant = $store->isActive
                        ? ($stats['webhooks_active'] ?? 0) > 0 ? 'completed' : 'on-hold'
                        : 'failed';
                    $statusLabel = $store->isActive
                        ? (($stats['webhooks_active'] ?? 0) > 0 ? _l('woocommerce_store_status_healthy') : _l('woocommerce_store_status_no_webhooks'))
                        : _l('woocommerce_store_status_inactive');
                    $maskedUrl = woo_mask_store_url($store->url);
                ?>
                    <div class="woo-card woo-store-card <?php echo $isActive ? 'is-active' : ''; ?>"
                         data-store-id="<?php echo $sid; ?>">
                        <div class="woo-store-card__head">
                            <h3 class="woo-store-card__name">
                                <?php echo html_escape($store->name); ?>
                                <?php if ($isActive): ?>
                                    <span class="woo-badge"><?php echo html_escape(_l('woocommerce_active')); ?></span>
                                <?php endif; ?>
                            </h3>
                            <?php $this->load->view('components/_status_pill', [
                                'status' => $statusVariant,
                                'label'  => $statusLabel,
                            ]); ?>
                        </div>

                        <a class="woo-store-card__url" href="<?php echo html_escape($store->url); ?>" target="_blank" rel="noopener" title="<?php echo html_escape($store->url); ?>">
                            <i class="fa fa-external-link mright5" aria-hidden="true"></i><?php echo html_escape($maskedUrl); ?>
                        </a>

                        <dl class="woo-store-card__stats">
                            <div>
                                <dt><?php echo html_escape(_l('total_orders')); ?></dt>
                                <dd><?php echo (int) ($stats['order_count'] ?? 0); ?></dd>
                            </div>
                            <div>
                                <dt><?php echo html_escape(_l('all_products')); ?></dt>
                                <dd><?php echo (int) ($stats['product_count'] ?? 0); ?></dd>
                            </div>
                            <div>
                                <dt><?php echo html_escape(_l('woocommerce_customers')); ?></dt>
                                <dd><?php echo (int) ($stats['customer_count'] ?? 0); ?></dd>
                            </div>
                            <div>
                                <dt><?php echo html_escape(_l('woocommerce_assigned_staff')); ?></dt>
                                <dd><?php echo (int) ($stats['assigned_staff'] ?? 0); ?></dd>
                            </div>
                        </dl>

                        <?php if (! empty($stats['last_synced_at'])): ?>
                            <p class="woo-store-card__synced text-muted" title="<?php echo html_escape((string) $stats['last_synced_at']); ?>">
                                <i class="fa fa-clock-o mright5" aria-hidden="true"></i>
                                <?php echo html_escape(_l('woocommerce_last_synced')); ?>:
                                <?php echo html_escape(time_ago((string) $stats['last_synced_at'])); ?>
                            </p>
                        <?php else: ?>
                            <p class="woo-store-card__synced text-muted">
                                <i class="fa fa-clock-o mright5" aria-hidden="true"></i>
                                <?php echo html_escape(_l('woocommerce_never_synced')); ?>
                            </p>
                        <?php endif; ?>

                        <div class="woo-store-card__actions">
                            <a href="<?php echo admin_url('woocommerce/stores/edit/' . $sid); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-pencil mright3" aria-hidden="true"></i><?php echo html_escape(_l('edit')); ?>
                            </a>
                            <a href="<?php echo admin_url('woocommerce/stores/refresh/' . $sid); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-refresh mright3" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_refresh')); ?>
                            </a>
                            <a href="<?php echo admin_url('woocommerce/stores/mappings/' . $sid); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-random mright3" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_field_mappings')); ?>
                            </a>
                            <a href="<?php echo admin_url('woocommerce/stores/webhooks/' . $sid); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-bolt mright3" aria-hidden="true"></i><?php echo html_escape(_l('woocommerce_webhooks')); ?>
                            </a>
                            <?php if (staff_can('delete', 'woocommerce')): ?>
                                <a href="<?php echo admin_url('woocommerce/stores/delete/' . $sid); ?>"
                                   class="btn btn-link btn-sm text-danger _delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<div id="modal_wrapper"></div>
<?php init_tail(); ?>
</body>
</html>

