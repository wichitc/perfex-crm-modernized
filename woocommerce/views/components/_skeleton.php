<?php
/**
 * Skeleton loading-placeholder. Shown for >200 ms data fetches per
 * spec §10.2.
 *
 * Usage:
 *   $this->load->view('components/_skeleton', [
 *       'variant' => 'card', // or 'row' | 'title' | 'avatar' | 'card'
 *       'rows'    => 3,      // optional, repeats variant
 *   ]);
 *
 * @var string $variant
 * @var int $rows
 */
defined('BASEPATH') or exit('No direct script access allowed');

$variant = (string) ($variant ?? 'row');
$allowed = ['row', 'title', 'avatar', 'card'];
if (! in_array($variant, $allowed, true)) {
    $variant = 'row';
}
$rows = max(1, (int) ($rows ?? 1));
?>
<div class="woo-skeleton-stack" aria-hidden="true">
    <?php for ($i = 0; $i < $rows; $i++): ?>
        <div class="woo-skeleton woo-skeleton--<?php echo html_escape($variant); ?>"></div>
    <?php endfor; ?>
</div>
