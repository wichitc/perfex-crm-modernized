<?php
/**
 * Breadcrumb partial.
 *
 * Usage:
 *   $this->load->view('components/_breadcrumbs', [
 *       'crumbs' => [
 *           ['label' => _l('woocommerce'),         'url' => admin_url('woocommerce')],
 *           ['label' => _l('woocommerce_stores'),  'url' => admin_url('woocommerce/stores')],
 *           ['label' => $store->name,              'url' => null], // current page → no url
 *       ],
 *   ]);
 *
 * Last crumb is always treated as current (non-link) regardless of the
 * url being set, so a copy-pasted set with a trailing url still
 * renders correctly.
 *
 * @var list<array{label:string, url:?string}> $crumbs
 */
defined('BASEPATH') or exit('No direct script access allowed');

$crumbs = isset($crumbs) && is_array($crumbs) ? $crumbs : [];
if ($crumbs === []) {
    return;
}

$lastIndex = count($crumbs) - 1;
?>
<nav class="woo-breadcrumbs" aria-label="<?php echo html_escape(_l('woocommerce_breadcrumbs')); ?>">
    <ol>
        <?php foreach ($crumbs as $i => $crumb):
            $label = (string) ($crumb['label'] ?? '');
            $url   = $crumb['url'] ?? null;
            $isLast = $i === $lastIndex;
        ?>
            <li class="<?php echo $isLast ? 'is-current' : ''; ?>">
                <?php if (! $isLast && is_string($url) && $url !== ''): ?>
                    <a href="<?php echo html_escape($url); ?>"><?php echo html_escape($label); ?></a>
                <?php else: ?>
                    <span aria-current="<?php echo $isLast ? 'page' : 'false'; ?>"><?php echo html_escape($label); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
