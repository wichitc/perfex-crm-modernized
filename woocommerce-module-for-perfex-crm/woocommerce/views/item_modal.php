<?php
/**
 * Product edit modal (T6.8).
 *
 * Returned by `Woocommerce::product_modal` as a fragment that the
 * products list injects into a Bootstrap modal. Inline validation lives
 * in the JS at assets/js/products.js — server validates the same rules
 * (defence-in-depth) and JSON-replies on save.
 *
 * Tailwind classes (`tw-*`) handle layout. Bootstrap remains the form
 * widget kit because Perfex's form-control + selectpicker + checkbox
 * styles already pair with admin theme.
 *
 * @var int                       $store_id
 * @var int                       $woo_id
 * @var array<string, mixed>      $product   cached row from woocommerce_products
 */
defined('BASEPATH') or exit('No direct script access allowed');

$status = (string) ($product['status'] ?? 'publish');
$picture = (string) ($product['picture'] ?? '');
?>
<div class="modal fade" id="wooProductModal" tabindex="-1" role="dialog" aria-labelledby="wooProductModalTitle">
    <div class="modal-dialog modal-lg" role="document">
        <form id="wooProductForm"
              class="modal-content"
              data-action="<?= admin_url('woocommerce/woocommerce/update_product/' . (int) $store_id . '/' . (int) $woo_id); ?>"
              novalidate>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= html_escape(_l('close')); ?>"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="wooProductModalTitle">
                    <i class="fa fa-cube mright5" aria-hidden="true"></i>
                    <?= html_escape(_l('woocommerce_edit_product')); ?>
                </h4>
            </div>

            <div class="modal-body">
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                    <div class="form-group">
                        <label for="woo-prod-name" class="control-label">
                            <?= html_escape(_l('woocommerce_product_name')); ?> <span aria-hidden="true">*</span>
                        </label>
                        <input id="woo-prod-name" name="name" type="text" class="form-control"
                               required aria-required="true" maxlength="500"
                               value="<?= html_escape((string) ($product['name'] ?? '')); ?>">
                        <p class="help-block tw-text-red-600 tw-hidden" data-error-for="name"></p>
                    </div>

                    <div class="form-group">
                        <label for="woo-prod-sku" class="control-label">
                            <?= html_escape(_l('woocommerce_product_sku')); ?> <span aria-hidden="true">*</span>
                        </label>
                        <input id="woo-prod-sku" name="sku" type="text" class="form-control"
                               required aria-required="true" maxlength="50"
                               value="<?= html_escape((string) ($product['sku'] ?? '')); ?>">
                        <p class="help-block tw-text-red-600 tw-hidden" data-error-for="sku"></p>
                    </div>

                    <div class="form-group">
                        <label for="woo-prod-regular" class="control-label">
                            <?= html_escape(_l('woocommerce_regular_price')); ?>
                        </label>
                        <input id="woo-prod-regular" name="regular_price" type="number" step="0.01" min="0"
                               class="form-control"
                               value="<?= html_escape((string) ($product['price'] ?? '')); ?>">
                        <p class="help-block tw-text-red-600 tw-hidden" data-error-for="regular_price"></p>
                    </div>

                    <div class="form-group">
                        <label for="woo-prod-sale" class="control-label">
                            <?= html_escape(_l('woocommerce_sale_price')); ?>
                        </label>
                        <input id="woo-prod-sale" name="sale_price" type="number" step="0.01" min="0"
                               class="form-control"
                               value="<?= html_escape((string) ($product['sale_price'] ?? '')); ?>">
                        <p class="help-block tw-text-red-600 tw-hidden" data-error-for="sale_price"></p>
                    </div>

                    <div class="form-group">
                        <label for="woo-prod-status" class="control-label">
                            <?= html_escape(_l('status')); ?>
                        </label>
                        <select id="woo-prod-status" name="status" class="selectpicker form-control" data-width="100%">
                            <?php foreach (['publish', 'draft', 'private', 'pending'] as $opt): ?>
                                <option value="<?= html_escape($opt); ?>" <?= $opt === $status ? 'selected' : ''; ?>>
                                    <?= html_escape(_l('woocommerce_product_status_' . $opt, $opt)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="woo-prod-image" class="control-label">
                            <?= html_escape(_l('woocommerce_image_url')); ?>
                        </label>
                        <input id="woo-prod-image" name="image_url" type="url" class="form-control"
                               value="<?= html_escape($picture); ?>"
                               placeholder="https://...">
                        <div class="woo-image-preview-wrap">
                            <img id="woo-prod-image-preview"
                                 class="woo-image-preview"
                                 src="<?= html_escape($picture); ?>"
                                 alt=""
                                 <?= $picture === '' ? 'hidden' : ''; ?>>
                        </div>
                    </div>
                </div>

                <fieldset class="woo-fieldset">
                    <legend><?= html_escape(_l('woocommerce_stock')); ?></legend>

                    <?php $manageStock = (bool) ($product['manage_stock'] ?? false); ?>
                    <div class="checkbox checkbox-primary">
                        <input id="woo-prod-managestock" name="manage_stock" type="checkbox" value="1"
                               data-toggle-stock <?= $manageStock ? 'checked' : ''; ?>>
                        <label for="woo-prod-managestock">
                            <?= html_escape(_l('woocommerce_manage_stock')); ?>
                        </label>
                    </div>

                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4 woo-stock-controls <?= $manageStock ? '' : 'tw-hidden'; ?>">
                        <div class="form-group">
                            <label for="woo-prod-qty" class="control-label">
                                <?= html_escape(_l('woocommerce_stock_quantity')); ?>
                            </label>
                            <input id="woo-prod-qty" name="stock_quantity" type="number" min="0"
                                   class="form-control"
                                   value="<?= html_escape((string) ($product['stock_quantity'] ?? '')); ?>">
                        </div>

                        <div class="form-group">
                            <?php $currentStockStatus = (string) ($product['stock_status'] ?? 'instock'); ?>
                            <label for="woo-prod-stockstatus" class="control-label">
                                <?= html_escape(_l('woocommerce_stock_status')); ?>
                            </label>
                            <select id="woo-prod-stockstatus" name="stock_status" class="selectpicker form-control" data-width="100%">
                                <?php foreach (['instock', 'outofstock', 'onbackorder'] as $opt): ?>
                                    <option value="<?= html_escape($opt); ?>" <?= $opt === $currentStockStatus ? 'selected' : ''; ?>>
                                        <?= html_escape(_l('woocommerce_stock_' . $opt, $opt)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group">
                    <label for="woo-prod-desc" class="control-label">
                        <?= html_escape(_l('description')); ?>
                    </label>
                    <textarea id="woo-prod-desc" name="description" class="form-control" rows="3"><?= html_escape((string) ($product['description'] ?? '')); ?></textarea>
                </div>

                <p class="text-muted tw-text-xs">
                    <i class="fa fa-info-circle mright3" aria-hidden="true"></i>
                    <?= html_escape(_l('woocommerce_product_modal_remote_note')); ?>
                </p>
            </div>

            <div class="modal-footer">
                <span id="wooProductFeedback" class="tw-text-sm tw-mr-2" aria-live="polite"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= html_escape(_l('cancel')); ?>
                </button>
                <button type="submit" id="wooProductSubmit" class="btn btn-primary">
                    <i class="fa fa-floppy-o mright5" aria-hidden="true"></i><?= html_escape(_l('save')); ?>
                </button>
            </div>
        </form>
    </div>
</div>
