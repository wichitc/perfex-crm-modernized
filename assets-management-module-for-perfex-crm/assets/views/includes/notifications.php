<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-font-semibold tw-mb-4"><?php echo _l('notification_settings'); ?></h4>

<?php echo form_open(admin_url('assets/notification_settings'), ['id' => 'notificationSettingsForm']); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo _l('notification_type'); ?></th>
                <th class="text-center"><?php echo _l('enabled'); ?></th>
                <th class="text-center"><?php echo _l('email_enabled'); ?></th>
                <th style="width: 100px;"><?php echo _l('days_before'); ?></th>
                <th><?php echo _l('recipients'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $notification_types = [
                'warranty_expiry' => ['label' => _l('warranty_expiry'), 'icon' => 'fa-shield'],
                'insurance_expiry' => ['label' => _l('insurance_expiry'), 'icon' => 'fa-file-text'],
                'maintenance_due' => ['label' => _l('maintenance_due'), 'icon' => 'fa-wrench'],
                'checkout_overdue' => ['label' => _l('checkout_overdue'), 'icon' => 'fa-clock-o'],
                'reservation_reminder' => ['label' => _l('reservation_reminder'), 'icon' => 'fa-calendar'],
                'low_stock' => ['label' => _l('low_stock'), 'icon' => 'fa-warning'],
                'asset_end_of_life' => ['label' => _l('asset_end_of_life'), 'icon' => 'fa-hourglass-end'],
            ];
            
            foreach ($notification_types as $type_key => $type_info):
                $setting = null;
                if (!empty($notification_settings)) {
                    foreach ($notification_settings as $s) {
                        if ($s['notification_type'] == $type_key) {
                            $setting = $s;
                            break;
                        }
                    }
                }
            ?>
            <tr>
                <td>
                    <i class="fa <?php echo $type_info['icon']; ?> text-muted"></i>
                    <?php echo $type_info['label']; ?>
                </td>
                <td class="text-center">
                    <div class="onoffswitch">
                        <input type="checkbox" name="settings[<?php echo $type_key; ?>][enabled]" class="onoffswitch-checkbox" id="enabled_<?php echo $type_key; ?>" value="1" <?php echo (!$setting || $setting['enabled']) ? 'checked' : ''; ?>>
                        <label class="onoffswitch-label" for="enabled_<?php echo $type_key; ?>"></label>
                    </div>
                </td>
                <td class="text-center">
                    <div class="onoffswitch">
                        <input type="checkbox" name="settings[<?php echo $type_key; ?>][email_enabled]" class="onoffswitch-checkbox" id="email_<?php echo $type_key; ?>" value="1" <?php echo (!$setting || $setting['email_enabled']) ? 'checked' : ''; ?>>
                        <label class="onoffswitch-label" for="email_<?php echo $type_key; ?>"></label>
                    </div>
                </td>
                <td>
                    <input type="number" name="settings[<?php echo $type_key; ?>][days_before]" class="form-control input-sm" min="0" max="365" value="<?php echo $setting ? $setting['days_before'] : 7; ?>">
                </td>
                <td>
                    <select name="settings[<?php echo $type_key; ?>][recipients][]" class="selectpicker" data-width="100%" data-live-search="true" multiple data-actions-box="true">
                        <option value="admin" <?php echo ($setting && strpos($setting['recipients'] ?? '', 'admin') !== false) ? 'selected' : ''; ?>><?php echo _l('administrator'); ?></option>
                        <?php 
                        $CI =& get_instance();
                        $CI->load->model('staff_model');
                        $staff_members = $CI->staff_model->get();
                        foreach ($staff_members as $staff): 
                            $is_selected = $setting && strpos($setting['recipients'] ?? '', 'staff_' . $staff['staffid']) !== false;
                        ?>
                        <option value="staff_<?php echo $staff['staffid']; ?>" <?php echo $is_selected ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($staff['firstname'] . ' ' . $staff['lastname']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="tw-mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-save"></i> <?php echo _l('save'); ?>
    </button>
</div>
<?php echo form_close(); ?>

<hr>

<h5 class="tw-font-semibold tw-mb-3"><?php echo _l('notification_info'); ?></h5>
<div class="alert alert-info">
    <ul class="tw-mb-0">
        <li><?php echo _l('notification_info_1'); ?></li>
        <li><?php echo _l('notification_info_2'); ?></li>
        <li><?php echo _l('notification_info_3'); ?></li>
    </ul>
</div>
