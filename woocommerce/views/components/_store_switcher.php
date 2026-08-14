<?php
/**
 * Active-store switcher.
 *
 * Usage:
 *   $this->load->view('components/_store_switcher', [
 *       'stores'         => $stores,            // list<StoreDTO>
 *       'active_store_id' => $activeStoreId,    // int|null
 *   ]);
 *
 * - Zero stores → renders nothing (caller decides what empty state to show).
 * - One store   → static badge with the store name (no dropdown).
 * - Many stores → form with a select; submitting POSTs to the
 *                 woocommerce/stores/switch_active endpoint.
 *
 * @var list<\WooCommerce\Repositories\StoreDTO> $stores
 * @var ?int $active_store_id
 */
defined('BASEPATH') or exit('No direct script access allowed');

$stores         = isset($stores) && is_array($stores) ? $stores : [];
$active_store_id = $active_store_id ?? null;

if ($stores === []) {
    return;
}

if (count($stores) === 1) {
    /** @var \WooCommerce\Repositories\StoreDTO $only */
    $only = $stores[0];
    ?>
    <span class="woo-badge woo-store-switcher__single" title="<?php echo html_escape($only->url); ?>">
        <i class="fa fa-shopping-bag mright5" aria-hidden="true"></i>
        <?php echo html_escape($only->name); ?>
    </span>
    <?php
    return;
}
?>
<form class="woo-store-switcher" method="post" action="<?php echo admin_url('woocommerce/stores/switch_active'); ?>">
    <?php woo_csrf_input(); ?>
    <label for="woo-store-switcher-select" class="woo-store-switcher__label">
        <?php echo html_escape(_l('woocommerce_active_store')); ?>
    </label>
    <select id="woo-store-switcher-select" name="store_id" class="form-control input-sm woo-store-switcher__select"
            onchange="this.form.submit()">
        <?php foreach ($stores as $store):
            $isActive = $active_store_id !== null && (int) $store->storeId === (int) $active_store_id;
        ?>
            <option value="<?php echo (int) $store->storeId; ?>" <?php echo $isActive ? 'selected' : ''; ?>>
                <?php echo html_escape($store->name); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <noscript>
        <button type="submit" class="btn btn-default btn-sm"><?php echo html_escape(_l('woocommerce_switch')); ?></button>
    </noscript>
</form>
