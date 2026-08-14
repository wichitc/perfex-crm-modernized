<?php
/**
 * Page header — title + subtitle on the left, primary action on the
 * right. Renders the breadcrumb + store switcher above the title row
 * if either is provided.
 *
 * Usage:
 *   $this->load->view('components/_page_header', [
 *       'title'     => _l('woocommerce_orders'),
 *       'subtitle'  => _l('woocommerce_orders_subtitle'),
 *       'icon'      => 'fa fa-shopping-bag',
 *       'crumbs'    => [...],          // optional, see _breadcrumbs.php
 *       'stores'    => $stores,        // optional
 *       'active_store_id' => $active,  // optional
 *       'primary_action' => ['label' => 'New', 'attrs' => ['id' => 'newWooStore', 'class' => 'btn btn-primary']],
 *   ]);
 *
 * @var string $title
 * @var ?string $subtitle
 * @var ?string $icon
 * @var ?array<int, array{label:string, url:?string}> $crumbs
 * @var ?list<\WooCommerce\Repositories\StoreDTO> $stores
 * @var ?int $active_store_id
 * @var ?array{label:string, attrs?:array<string,string>} $primary_action
 */
defined('BASEPATH') or exit('No direct script access allowed');

$title = (string) ($title ?? '');
$subtitle = $subtitle ?? null;
$icon = $icon ?? null;
$crumbs = $crumbs ?? null;
$stores = $stores ?? null;
$active_store_id = $active_store_id ?? null;
$primary_action = $primary_action ?? null;
?>
<div class="woo-page-header">
    <?php if (is_array($crumbs) && $crumbs !== []): ?>
        <?php $this->load->view('components/_breadcrumbs', ['crumbs' => $crumbs]); ?>
    <?php endif; ?>

    <div class="woo-page-header__row">
        <div class="woo-page-header__main">
            <h2 class="woo-page-header__title">
                <?php if ($icon): ?>
                    <i class="<?php echo html_escape($icon); ?> mright5" aria-hidden="true"></i>
                <?php endif; ?>
                <?php echo html_escape($title); ?>
            </h2>
            <?php if (is_string($subtitle) && $subtitle !== ''): ?>
                <p class="woo-page-header__subtitle text-muted"><?php echo html_escape($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="woo-page-header__aside">
            <?php if (is_array($stores)): ?>
                <?php $this->load->view('components/_store_switcher', [
                    'stores'          => $stores,
                    'active_store_id' => $active_store_id,
                ]); ?>
            <?php endif; ?>

            <?php if (is_array($primary_action) && isset($primary_action['label'])):
                $attrsArr = $primary_action['attrs'] ?? [];
                // Renders as <a> when an `href` attr is supplied (link
                // semantics — navigates), <button> otherwise (JS hook).
                $useLink  = isset($attrsArr['href']);
                $attrs    = '';
                foreach ($attrsArr as $name => $value) {
                    $attrs .= ' ' . html_escape((string) $name) . '="' . html_escape((string) $value) . '"';
                }
            ?>
                <?php if ($useLink): ?>
                    <a<?php echo $attrs; ?>>
                        <?php echo html_escape((string) $primary_action['label']); ?>
                    </a>
                <?php else: ?>
                    <button type="button"<?php echo $attrs; ?>>
                        <?php echo html_escape((string) $primary_action['label']); ?>
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
