<?php

use Google\Service\AdSensePlatform\Site;

defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <?php echo form_open_multipart($this->uri->uri_string('main'), array('id' => 'perfex_multi_theme_setup',)); ?>
            <?php $enabled = get_option('perfex_multi_theme_clients'); ?>
            <?php $dashboard_image = get_option('dashboard_bg_image'); ?>
            <?php $login_image = get_option('login_bg_image'); ?>
            <div class="form-group">
              <label for="perfex_multi_theme_clients" class="control-label clearfix">
                <?= _l('perfex_multi_theme_settings'); ?>
              </label>
              <hr>
              <div class="radio radio-primary radio-inline">
                <input type="radio" id="y_opt_1_perfex_multi_theme_clients_enabled" name="is_mt_client" value="1" <?= ($enabled == '1') ? ' checked' : '' ?>>
                <label for="y_opt_1_perfex_multi_theme_clients_enabled">
                  <?= _l('settings_yes'); ?>
                </label>
              </div>
              <div class="radio radio-primary radio-inline">
                <input type="radio" id="y_opt_2_admin-multi_theme_enabled" name="is_mt_client" value="0" <?= ($enabled == '0') ? ' checked' : '' ?>>
                <label for="y_opt_2_admin-multi_theme_enabled">
                  <?= _l('settings_no'); ?>
                </label>
              </div>
            </div>
            <hr />
            <?php if ($login_image != '') { ?>
              <div class="row">
                <div class="col-md-4">
                  <?php echo _l('Login Background Image'); ?> <br /> <br />
                  <img src="<?php echo base_url('uploads/company/' . $login_image); ?>" class="img img-responsive" height="300" width="300">
                </div>
                <?php if (has_permission('settings', '', 'delete')) { ?>
                  <div class="col-md-8 text-left">
                    <a href="<?php echo base_url('perfex_multi_theme/main/remove_login_bg_image'); ?>" data-toggle="tooltip" title="<?php echo _l('remove_login_tooltip'); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
                  </div>
                <?php } ?>
              </div>
              <div class="clearfix"></div>
            <?php } else { ?>
              <div class="form-group">
                <label for="company_logo" class="control-label"><?php echo _l('Login Background Image'); ?></label>
                <input type="file" name="login_bg_image" class="form-control" value="" data-toggle="tooltip" title="<?php echo _l('settings_general_company_logo_tooltip'); ?>">
              </div>
            <?php } ?>
            <hr />
            <?php if ($dashboard_image != '') { ?>
              <div class="row">
                <div class="col-md-4">
                  <?php echo _l('Dashboard Background Image'); ?> <br /> <br />
                  <img src="<?php echo base_url('uploads/company/' . $dashboard_image); ?>" class="img img-responsive" height="300" width="300">
                </div>
                <?php if (has_permission('settings', '', 'delete')) { ?>
                  <div class="col-md-8 text-left">
                    <a href="<?php echo base_url('perfex_multi_theme/main/remove_dashboard_bg_image'); ?>" data-toggle="tooltip" title="<?php echo _l('remove_dashboard_tooltip'); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
                  </div>
                <?php } ?>
              </div>
              <div class="clearfix"></div>
            <?php } else { ?>
              <div class="form-group">
                <label for="company_logo" class="control-label"><?php echo _l('Dashboard Background Image dafault'); ?></label>
                <input type="file" name="dashboard_bg_image" class="form-control" value="" data-toggle="tooltip" title="<?php echo _l('settings_general_company_logo_tooltip'); ?>">
              </div>
            <?php } ?>

            <div class="panel-body panel-table-full">
              <?php if (count($themes) > 0) { ?>
                <table class="table dt-table table-striped" data-order-col="0" data-order-type="asc">
                  <thead>
                    <th><?= _l('id'); ?>
                    </th>
                    <th><?= _l('pmt_theme_name'); ?>
                    <th><?= _l('pmt_theme_color'); ?>
                    <th><?= _l('pmt_theme_back_img'); ?>
                    </th>
                    <th><?= _l('pmt_theme_options'); ?>
                    </th>
                  </thead>
                  <tbody class="tbl-clr-data-cl">
                    <?php foreach ($themes as $status) { ?>
                      <tr>
                        <td>
                          <?= ($status['id']); ?>
                        </td>
                        <td class="wrap-icon-text-cl">
                          <a href="#" class="tw-font-medium"
                            onclick="edit_theme(this,<?= e($status['id']); ?>);return false;"
                            data-theme_color="<?= e($status['theme_color']); ?>"
                            data-theme_name="<?= e($status['theme_name']); ?>"
                            data-order=""><?= e($status['theme_name']); ?> </a> <?= $status['is_default'] == 1 ? '<span class="active-check-icon">Active</span>' : '';  ?><br />
                        </td>
                        <td>
                          <span style="color: <?= ($status['theme_color']); ?>"> <?= ($status['theme_color']); ?> </span>
                        </td>
                        <td class="bg-img-up">
                          <?php if (empty($status['bakground_image'])) { ?>
                            <div class="tw-flex tw-items-center tw-space-x-2">
                              <a href="#"
                                onclick="edit_theme(this,<?= e($status['id']); ?>);return false;"
                                data-theme_color="<?= e($status['theme_color']); ?>"
                                data-theme_name="<?= e($status['theme_name']); ?>"
                                data-order=""
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                <i class="fa-regular fa-image"></i>
                              </a>
                            </div>
                          <?php } else { ?>
                            <a href="#"
                              onclick="edit_theme(this,<?= e($status['id']); ?>);return false;"
                              data-theme_color="<?= e($status['theme_color']); ?>"
                              data-theme_name="<?= e($status['theme_name']); ?>"
                              data-order=""
                              class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                              <img src="<?php echo site_url('uploads/' . PERFEX_MULTI_THEME_MODULE_NAME . '/'.$status['bakground_image']) ?>" height="20px" width="30px"><a class="x-btn-cl" style="color: red;" href="<?php echo admin_url('perfex_multi_theme/main/remove_theme_img/'.$status['id']); ?>">x</a>
                            </a>
                          <?php } ?>
                        </td>
                        <td>
                          <div class="tw-flex tw-items-center tw-space-x-2">
                            <a href="#"
                              onclick="edit_theme(this,<?= e($status['id']); ?>);return false;"
                              data-theme_color="<?= e($status['theme_color']); ?>"
                              data-theme_name="<?= e($status['theme_name']); ?>"
                              data-order=""
                              class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                              <i class="fa-regular fa-pen-to-square fa-lg"></i>
                            </a>

                          </div>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              <?php } else { ?>
                <p class="no-margin">
                  <?= _l('themes_not_found'); ?>
                </p>
              <?php } ?>
            </div>

            <div class="btn-bottom-toolbar text-right">
              <button type="submit" class="btn btn-info">Save</button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once APP_MODULES_PATH . PERFEX_MULTI_THEME_MODULE_NAME . '/views/multi_theme.php'; ?>
<?php init_tail(); ?>
<script>

</script>