<?php
/**
 * Status pill emitter. Picks the right CSS variant from the input
 * status string and renders an accessible pill.
 *
 * Usage:
 *   $this->load->view('components/_status_pill', [
 *       'status' => 'completed', // any Woo order status, plus our own:
 *                                // 'guest', 'paid', 'draft'
 *       'label'  => null,         // optional override; defaults to humanised status
 *   ]);
 *
 * @var string $status
 * @var ?string $label
 */
defined('BASEPATH') or exit('No direct script access allowed');

$status = strtolower(trim((string) ($status ?? '')));
$label  = $label ?? null;

// Map a Woo status to one of our supported variants. Unknown status
// strings get a neutral 'default' pill so a custom POS status doesn't
// look broken.
$known = ['processing', 'completed', 'on-hold', 'failed', 'cancelled', 'refunded', 'guest', 'paid', 'draft'];
$variant = in_array($status, $known, true) ? $status : null;

if ($label === null || $label === '') {
    $label = $status === '' ? '—' : str_replace(['-', '_'], ' ', $status);
    $label = ucfirst($label);
}
?>
<span class="woo-status-pill<?php echo $variant ? ' woo-status-pill--' . html_escape($variant) : ''; ?>"
      title="<?php echo html_escape($label); ?>">
    <?php echo html_escape($label); ?>
</span>
