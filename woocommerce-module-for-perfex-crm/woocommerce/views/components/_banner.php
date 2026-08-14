<?php
/**
 * Inline contextual banner (§10.2). Used on detail pages to explain
 * non-obvious behaviour: guest customer, WooCommerce payment mode,
 * tax adjustment, etc.
 *
 * Usage:
 *   $this->load->view('components/_banner', [
 *       'severity' => 'info', // info | warn | danger | success
 *       'icon'     => 'fa fa-info-circle',
 *       'title'    => null,    // optional bold prefix
 *       'body'     => _l('woocommerce_guest_customer_explainer'),
 *   ]);
 *
 * @var string $severity
 * @var ?string $icon
 * @var ?string $title
 * @var string $body
 */
defined('BASEPATH') or exit('No direct script access allowed');

$severity = (string) ($severity ?? 'info');
$allowed  = ['info', 'warn', 'danger', 'success'];
if (! in_array($severity, $allowed, true)) {
    $severity = 'info';
}

$icon  = $icon  ?? null;
$title = $title ?? null;
$body  = (string) ($body ?? '');
?>
<div class="woo-banner woo-banner--<?php echo html_escape($severity); ?>" role="status">
    <?php if (is_string($icon) && $icon !== ''): ?>
        <span class="woo-banner__icon" aria-hidden="true"><i class="<?php echo html_escape($icon); ?>"></i></span>
    <?php endif; ?>
    <div class="woo-banner__body">
        <?php if (is_string($title) && $title !== ''): ?>
            <strong><?php echo html_escape($title); ?></strong>
        <?php endif; ?>
        <?php echo html_escape($body); ?>
    </div>
</div>
