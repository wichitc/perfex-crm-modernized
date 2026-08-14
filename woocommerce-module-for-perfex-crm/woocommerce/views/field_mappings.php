<?php
/**
 * Field Mappings editor (T6.4).
 *
 * Tabbed view (Customer, Product, Order) — each tab is a table of the
 * resolved mappings (predefined + override + custom merged) plus four
 * actions: Add Mapping, Load Preset, Reset Tab, Pre-flight Check.
 *
 * Inline edit happens via the row's `<select>` widgets (the typeahead
 * is provided by Bootstrap selectpicker, already on every Perfex
 * admin page). Save is per-row — clicking the row's "Save" button
 * persists the change as a custom row or an override depending on the
 * row's origin.
 *
 * Tailwind classes (`tw-*`) handle layout; the only module-specific
 * classes are status pills inherited from the design tokens.
 *
 * @var \WooCommerce\Repositories\StoreDTO $store
 * @var array{customer:list<array<string,mixed>>, product:list<array<string,mixed>>, order:list<array<string,mixed>>} $mappings
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Origin pill for each row. Predefined rows live in the preset config,
 * overrides are predefined rows the admin replaced, customs are
 * admin-added pairs.
 *
 * @param array<string,mixed> $row
 */
$rowOrigin = static function (array $row): array {
    if ((int) ($row['is_overridden'] ?? 0) === 1) {
        return ['key' => 'overridden', 'variant' => 'on-hold',  'label' => _l('woocommerce_mapping_overridden')];
    }
    if ((int) ($row['is_predefined'] ?? 0) === 1) {
        return ['key' => 'predefined', 'variant' => 'completed', 'label' => _l('woocommerce_mapping_predefined')];
    }
    return ['key' => 'custom', 'variant' => 'failed', 'label' => _l('woocommerce_mapping_custom')];
};

$tabs = [
    'customer' => _l('woocommerce_tab_customer'),
    'product'  => _l('woocommerce_tab_product'),
    'order'    => _l('woocommerce_tab_order'),
];

init_head();
?>
<div id="wrapper">
    <div class="content">
        <?php $this->load->view('components/_page_header', [
            'title'    => _l('woocommerce_field_mappings'),
            'subtitle' => _l('woocommerce_field_mappings_subtitle', $store->name),
            'icon'     => 'fa fa-random',
            'crumbs'   => [
                ['label' => _l('woocommerce'),        'url' => admin_url('woocommerce')],
                ['label' => _l('woocommerce_stores'), 'url' => admin_url('woocommerce/stores')],
                ['label' => $store->name,             'url' => admin_url('woocommerce/stores/edit/' . (int) $store->storeId)],
                ['label' => _l('woocommerce_field_mappings'), 'url' => null],
            ],
        ]); ?>

        <div class="woo-card woo-mapping-editor"
             data-store-id="<?= (int) $store->storeId; ?>">

            <!-- Tabs -->
            <ul class="nav nav-tabs tw-mb-4" role="tablist">
                <?php $first = true; foreach ($tabs as $entity => $label): ?>
                    <li role="presentation" class="<?= $first ? 'active' : ''; ?>">
                        <a href="#tab-<?= html_escape($entity); ?>"
                           role="tab" data-toggle="tab"
                           data-entity="<?= html_escape($entity); ?>">
                            <?= html_escape($label); ?>
                        </a>
                    </li>
                <?php $first = false; endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php $first = true; foreach ($tabs as $entity => $label):
                    $rows = $mappings[$entity] ?? [];
                ?>
                    <div role="tabpanel"
                         class="tab-pane <?= $first ? 'active' : ''; ?>"
                         id="tab-<?= html_escape($entity); ?>"
                         data-entity="<?= html_escape($entity); ?>">

                        <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2 tw-mb-3">
                            <button type="button"
                                    class="btn btn-default btn-sm"
                                    data-action="add-row"
                                    data-entity="<?= html_escape($entity); ?>">
                                <i class="fa fa-plus mright3" aria-hidden="true"></i><?= html_escape(_l('woocommerce_mapping_add')); ?>
                            </button>
                            <button type="button"
                                    class="btn btn-default btn-sm"
                                    data-action="load-preset"
                                    data-entity="<?= html_escape($entity); ?>">
                                <i class="fa fa-magic mright3" aria-hidden="true"></i><?= html_escape(_l('woocommerce_mapping_load_preset')); ?>
                            </button>
                            <button type="button"
                                    class="btn btn-default btn-sm"
                                    data-action="preflight"
                                    data-entity="<?= html_escape($entity); ?>">
                                <i class="fa fa-stethoscope mright3" aria-hidden="true"></i><?= html_escape(_l('woocommerce_mapping_preflight')); ?>
                            </button>
                            <button type="button"
                                    class="btn btn-link btn-sm tw-text-red-600"
                                    data-action="reset-tab"
                                    data-entity="<?= html_escape($entity); ?>">
                                <i class="fa fa-undo mright3" aria-hidden="true"></i><?= html_escape(_l('woocommerce_mapping_reset_tab')); ?>
                            </button>
                            <span class="woo-mapping-editor__feedback tw-ml-auto tw-text-sm" aria-live="polite"></span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover woo-mapping-table">
                                <thead>
                                    <tr>
                                        <th><?= html_escape(_l('woocommerce_wc_field')); ?></th>
                                        <th><?= html_escape(_l('woocommerce_perfex_field')); ?></th>
                                        <th class="tw-w-24"><?= html_escape(_l('woocommerce_required')); ?></th>
                                        <th><?= html_escape(_l('woocommerce_default_value')); ?></th>
                                        <th class="tw-w-32"><?= html_escape(_l('status')); ?></th>
                                        <th class="tw-w-36"><?= html_escape(_l('options')); ?></th>
                                    </tr>
                                </thead>
                                <tbody data-rows>
                                    <?php if (empty($rows)): ?>
                                        <tr><td colspan="6" class="text-center text-muted tw-py-6">
                                            <?= html_escape(_l('woocommerce_mapping_empty')); ?>
                                        </td></tr>
                                    <?php else: foreach ($rows as $row):
                                        $origin = $rowOrigin($row);
                                        $rowId  = (int) ($row['id'] ?? 0);
                                    ?>
                                        <tr data-row
                                            data-id="<?= $rowId; ?>"
                                            data-origin="<?= html_escape($origin['key']); ?>"
                                            data-original-wc="<?= html_escape((string) ($row['original_wc_field']     ?? $row['wc_field']     ?? '')); ?>"
                                            data-original-perfex="<?= html_escape((string) ($row['original_perfex_field'] ?? $row['perfex_field'] ?? '')); ?>">
                                            <td>
                                                <?php $this->load->view('components/_mapping_field_select', [
                                                    'entity'   => $entity,
                                                    'side'     => 'wc',
                                                    'value'    => (string) ($row['wc_field'] ?? ''),
                                                ]); ?>
                                            </td>
                                            <td>
                                                <?php $this->load->view('components/_mapping_field_select', [
                                                    'entity'   => $entity,
                                                    'side'     => 'perfex',
                                                    'value'    => (string) ($row['perfex_field'] ?? ''),
                                                ]); ?>
                                            </td>
                                            <td class="tw-text-center">
                                                <input type="checkbox"
                                                       data-field="is_required"
                                                       <?= ((int) ($row['is_required'] ?? 0)) ? 'checked' : ''; ?>>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control input-sm"
                                                       data-field="default_value"
                                                       value="<?= html_escape((string) ($row['default_value'] ?? '')); ?>">
                                            </td>
                                            <td>
                                                <span class="woo-status-pill woo-status-pill--<?= html_escape($origin['variant']); ?>">
                                                    <?= html_escape($origin['label']); ?>
                                                </span>
                                            </td>
                                            <td class="tw-whitespace-nowrap">
                                                <button type="button" class="btn btn-primary btn-xs" data-action="save-row"
                                                        title="<?= e(_l('save')); ?>">
                                                    <i class="fa fa-save mright3" aria-hidden="true"></i><?= e(_l('save')); ?>
                                                </button>
                                                <?php if ($origin['key'] === 'overridden'): ?>
                                                    <button type="button" class="btn btn-default btn-xs" data-action="revert-row"
                                                            title="<?= e(_l('woocommerce_mapping_revert')); ?>">
                                                        <i class="fa fa-undo mright3" aria-hidden="true"></i><?= e(_l('woocommerce_mapping_revert')); ?>
                                                    </button>
                                                <?php elseif ($origin['key'] === 'custom'): ?>
                                                    <button type="button" class="btn btn-link btn-xs tw-text-red-600" data-action="delete-row"
                                                            title="<?= e(_l('delete')); ?>">
                                                        <i class="fa fa-trash mright3" aria-hidden="true"></i><?= e(_l('delete')); ?>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pre-flight modal -->
<div class="modal fade" id="wooMappingPreflightModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= html_escape(_l('woocommerce_mapping_preflight')); ?></h4>
            </div>
            <div class="modal-body">
                <pre id="wooMappingPreflightOut" class="tw-text-xs tw-bg-slate-50 tw-p-3 tw-rounded tw-max-h-96 tw-overflow-auto">…</pre>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?= module_dir_url('woocommerce', 'assets/js/field_mappings.js'); ?>?v=<?= WOOCOMMERCE_MODULE_VERSION; ?>"></script>
</body>
</html>
