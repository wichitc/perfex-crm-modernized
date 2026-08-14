<?php
/**
 * Empty-state component (§10.2). Used on every list page when the
 * filtered query returns zero rows.
 *
 * Usage:
 *   $this->load->view('components/_empty_state', [
 *       'icon'   => 'fa fa-shopping-bag',
 *       'title'  => _l('woocommerce_stores_empty_title'),
 *       'body'   => _l('woocommerce_stores_empty_body'),
 *       'action' => [
 *           'label' => _l('woocommerce_connect_first_store'),
 *           'attrs' => ['id' => 'newWooStore', 'class' => 'btn btn-primary'],
 *       ],
 *   ]);
 *
 * @var string $title
 * @var ?string $body
 * @var ?string $icon
 * @var ?array{label:string, attrs?:array<string,string>} $action
 */
defined('BASEPATH') or exit('No direct script access allowed');

$title  = (string) ($title ?? '');
$body   = $body   ?? null;
$icon   = $icon   ?? 'fa fa-inbox';
$action = $action ?? null;
?>
<div class="woo-empty-state" role="status">
    <div class="woo-empty-state__illustration" aria-hidden="true">
        <i class="<?php echo html_escape($icon); ?>" style="font-size: 96px; line-height: 96px; color: var(--woo-text-faint);"></i>
    </div>

    <?php if ($title !== ''): ?>
        <h3 class="woo-empty-state__title"><?php echo html_escape($title); ?></h3>
    <?php endif; ?>

    <?php if (is_string($body) && $body !== ''): ?>
        <p class="woo-empty-state__body"><?php echo html_escape($body); ?></p>
    <?php endif; ?>

    <?php if (is_array($action) && isset($action['label'])):
        $attrs = '';
        foreach ($action['attrs'] ?? [] as $name => $value) {
            $attrs .= ' ' . html_escape((string) $name) . '="' . html_escape((string) $value) . '"';
        }
    ?>
        <div class="woo-empty-state__action">
            <button type="button"<?php echo $attrs; ?>>
                <?php echo html_escape((string) $action['label']); ?>
            </button>
        </div>
    <?php endif; ?>
</div>
