<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $currency_symbol = isset($currency) && $currency ? $currency->symbol : ''; ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />
                        
                        <?php echo form_open(current_url(), array('id' => 'project-budget-form')); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id" class="control-label"><span class="text-danger">* </span><?php echo _l('project'); ?></label>
                                    <select name="project_id" id="project_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required="true" <?php echo !empty($budget) ? 'disabled' : ''; ?>>
                                        <option value=""></option>
                                        <?php foreach ($projects as $project) { ?>
                                        <option value="<?php echo $project['id']; ?>" <?php echo (!empty($budget) && $budget->project_id == $project['id']) ? 'selected' : ''; ?>>
                                            <?php echo html_escape($project['name']); ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <?php if (!empty($budget)) { ?>
                                    <input type="hidden" name="project_id" value="<?php echo $budget->project_id; ?>">
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner_id" class="control-label"><span class="text-danger">* </span><?php echo _l('project_manager'); ?></label>
                                    <select name="owner_id" id="owner_id" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required="true">
                                        <option value=""></option>
                                        <?php foreach ($staff as $s) { ?>
                                        <option value="<?php echo $s['staffid']; ?>" <?php echo (!empty($budget) && $budget->owner_id == $s['staffid']) ? 'selected' : ''; ?>>
                                            <?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                    $start_date = (!empty($budget) && !empty($budget->start_date)) ? _d($budget->start_date) : '';
                                    echo render_date_input('start_date', '<span class="text-danger">* </span>' . _l('start_date'), $start_date, array('required' => true)); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $end_date = (!empty($budget) && !empty($budget->end_date)) ? _d($budget->end_date) : '';
                                    echo render_date_input('end_date', '<span class="text-danger">* </span>' . _l('end_date'), $end_date, array('required' => true)); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="row mbot20">
                            <div class="col-md-12">
                                <?php echo render_textarea('description', 'description', !empty($budget) ? $budget->description : ''); ?>
                            </div>
                        </div>
                        
                        <h4 class="bold mbot15"><?php echo _l('acc_budget_details_by_category'); ?></h4>
                        <div class="panel_s">
                            <div class="panel-body bg-light">
                                <?php foreach ($categories as $category) { 
                                    $val = isset($details[$category['id']]) ? $details[$category['id']] : 0;
                                ?>
                                <div class="row mbot10">
                                    <div class="col-md-6 budget-category-label">
                                        <strong><?php echo html_escape($category['name']); ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" name="details[<?php echo $category['id']; ?>]" class="form-control text-right" value="<?php echo $val; ?>" required="true">
                                            <span class="input-group-addon"><?php echo html_escape($currency_symbol); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="btn-bottom-toolbar text-right">
                            <a href="<?php echo admin_url('accounting/project_budgets'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
                        </div>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
