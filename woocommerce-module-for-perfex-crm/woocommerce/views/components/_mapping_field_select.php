<?php
/**
 * Mapping field selector — populates the typeahead dropdown for the
 * field-mappings editor (T6.4). Reads the dictionary via the helper
 * functions ported from the legacy `*_field_mapping_model::get_default_*`.
 *
 * Renders a Bootstrap `selectpicker` (already part of every Perfex
 * admin page) so we get autocomplete + filter + accessible labels for
 * free.
 *
 * @var string $entity   customer | contact | product | order
 * @var string $side     wc | perfex
 * @var string $value    pre-selected key
 */
defined('BASEPATH') or exit('No direct script access allowed');

$dict = $side === 'wc'
    ? woo_default_wc_fields($entity)
    : woo_default_perfex_fields($entity);

// If the value isn't in the dictionary (e.g., a custom field added
// via predefined config or after a Perfex upgrade), append it so the
// admin sees the row and can re-pick a known key without losing data.
if ($value !== '' && ! isset($dict[$value])) {
    $dict[$value] = $value . ' (?)';
}
?>
<select class="selectpicker form-control input-sm"
        data-field="<?= html_escape($side === 'wc' ? 'wc_field' : 'perfex_field'); ?>"
        data-live-search="true"
        data-width="100%"
        data-size="10">
    <?php foreach ($dict as $key => $label): ?>
        <option value="<?= html_escape((string) $key); ?>"
            <?= $key === $value ? 'selected' : ''; ?>>
            <?= html_escape((string) $label); ?>
        </option>
    <?php endforeach; ?>
</select>
