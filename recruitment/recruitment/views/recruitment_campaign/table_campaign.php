<?php

defined('BASEPATH') or exit('No direct script access allowed');
$get_base_currency =  get_base_currency();
if($get_base_currency){
    $base_currency = $get_base_currency->id;
}else{
    $base_currency = 0;
}

$aColumns = [
	'campaign_name',
	db_prefix() . 'rec_campaign.company_id',
	'cp_position',
	'cp_form_work',
	'cp_department',
	'cp_from_date',
	'cp_amount_recruiment',
	'rec_channel_form_id',
	'cp_manager',
	'cp_status',
];
$sIndexColumn = 'cp_id';
$sTable = db_prefix() . 'rec_campaign';
$join = [
	'LEFT JOIN ' . db_prefix() . 'rec_job_position on ' . db_prefix() . 'rec_job_position.position_id = ' . db_prefix() . 'rec_campaign.cp_position',
	'LEFT JOIN ' . db_prefix() . 'departments on ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . 'rec_campaign.cp_department',
	'LEFT JOIN ' . db_prefix() . 'rec_campaign_form_web on ' . db_prefix() . 'rec_campaign_form_web.id = ' . db_prefix() . 'rec_campaign.rec_channel_form_id',
];
$where = [];

if ($this->ci->input->post('posiotion_ft')) {
	$posiotion_ft = $this->ci->input->post('posiotion_ft');
	$where_posiotion_ft = '';
	foreach ($posiotion_ft as $y) {
		if ($y != '') {
			if ($where_posiotion_ft == '') {
				$where_posiotion_ft .= 'AND (tblrec_campaign.cp_position = "' . $y . '"';
			} else {
				$where_posiotion_ft .= ' or tblrec_campaign.cp_position = "' . $y . '"';
			}
		}
	}
	if ($where_posiotion_ft != '') {
		$where_posiotion_ft .= ')';
		array_push($where, $where_posiotion_ft);
	}
}
if ($this->ci->input->post('dpm')) {
	$dpm = $this->ci->input->post('dpm');
	$where_dpm = '';
	foreach ($dpm as $y) {
		if ($y != '') {
			if ($where_dpm == '') {
				$where_dpm .= 'AND (tblrec_campaign.cp_department = "' . $y . '"';
			} else {
				$where_dpm .= ' or tblrec_campaign.cp_department = "' . $y . '"';
			}
		}
	}
	if ($where_dpm != '') {
		$where_dpm .= ')';
		array_push($where, $where_dpm);
	}
}
if ($this->ci->input->post('status')) {
	$status = $this->ci->input->post('status');
	$where_status = '';
	foreach ($status as $y) {
		if ($y != '') {
			if ($where_status == '') {
				$where_status .= 'AND (tblrec_campaign.cp_status = "' . $y . '"';
			} else {
				$where_status .= ' or tblrec_campaign.cp_status = "' . $y . '"';
			}
		}
	}
	if ($where_status != '') {
		$where_status .= ')';
		array_push($where, $where_status);
	}
}
if ($this->ci->input->post('company_filter')) {
	$company_filter = $this->ci->input->post('company_filter');
	$where_company_filter = '';
	foreach ($company_filter as $y) {
		if ($y != '') {
			if ($where_company_filter == '') {
				$where_company_filter .= 'AND ('.db_prefix().'rec_campaign.company_id = "' . $y . '"';
			} else {
				$where_company_filter .= ' or '.db_prefix().'rec_campaign.company_id = "' . $y . '"';
			}
		}
	}
	if ($where_company_filter != '') {
		$where_company_filter .= ')';
		array_push($where, $where_company_filter);
	}
}

if ($this->ci->input->post('cp_from_date_filter')) {
	array_push($where, "AND date_format(cp_from_date, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('cp_from_date_filter')))) . "'");
}
if ($this->ci->input->post('cp_to_date_filter')) {
	array_push($where, "AND date_format(cp_to_date, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('cp_to_date_filter')))) . "'");
}

if ($this->ci->input->post('rec_channel_form_id_filter')) {
	$rec_channel_form_id_filter = $this->ci->input->post('rec_channel_form_id_filter');
	$where_rec_channel_form_id_filter = '';
	foreach ($rec_channel_form_id_filter as $y) {
		if ($y != '') {
			if ($where_rec_channel_form_id_filter == '') {
				$where_rec_channel_form_id_filter .= 'AND ('.db_prefix().'rec_campaign.rec_channel_form_id = "' . $y . '"';
			} else {
				$where_rec_channel_form_id_filter .= ' or '.db_prefix().'rec_campaign.rec_channel_form_id = "' . $y . '"';
			}
		}
	}
	if ($where_rec_channel_form_id_filter != '') {
		$where_rec_channel_form_id_filter .= ')';
		array_push($where, $where_rec_channel_form_id_filter);
	}
}

if ($this->ci->input->post('cp_manager_filter')) {
	$cp_manager_filter = $this->ci->input->post('cp_manager_filter');

	$where_cp_manager_filter = '';
	foreach ($cp_manager_filter as $y) {
		if ($y != '') {
			if ($where_cp_manager_filter == '') {
				$where_cp_manager_filter .= 'AND (FIND_IN_SET('.$y.', '.db_prefix().'rec_campaign.cp_manager)';
			} else {
				$where_cp_manager_filter .= ' or FIND_IN_SET('.$y.', '.db_prefix().'rec_campaign.cp_manager)';
			}
		}
	}

	if ($where_cp_manager_filter != '') {
		$where_cp_manager_filter .= ')';
		array_push($where, $where_cp_manager_filter);
	}
}

$custom_fields = get_custom_fields('campaign', [
    'show_on_table' => 1,
]);

$y            = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $y;
    if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
        $select_as = 'date_picker_cvalue_' . $y;
    }
    array_push($aColumns, 'ctable_' . $y . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $y . ' ON '.db_prefix().'rec_campaign.cp_id = ctable_' . $y . '.relid AND ctable_' . $y . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $y . '.fieldid=' . $field['id']);
    $y++;
}
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['campaign_code', 'cp_id', 'position_name', db_prefix() . 'departments.name as dpm_name', 'cp_workplace', 'cp_salary_from', 'cp_salary_to', 'cp_from_date', 'cp_to_date', 'cp_ages_to', 'cp_ages_from', 'cp_height', 'cp_weight', 'cp_job_description', 'cp_reason_recruitment', 'cp_manager', 'cp_follower', 'cp_gender', 'cp_experience', 'cp_literacy', 'cp_proposal','rec_channel_form_id','display_salary',db_prefix() . 'rec_campaign.company_id', 'cp_to_date', 'r_form_name','currency']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	if($aRow['currency'] == 0){
        $aRow['currency'] = $base_currency;
    }
	$row = [];
	for ($i = 0; $i < count($aColumns); $i++) {
		if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        
		if ($aColumns[$i] == 'campaign_name') {

			$name = '<a href="' . admin_url('recruitment/recruitment_campaign/' . $aRow['cp_id']) . '" onclick="init_recruitment_campaign(' . $aRow['cp_id'] . '); return false;">' . $aRow['campaign_name'] . '</a>';

			$name .= '<div class="row-options">';

			$name .= '<a href="' . admin_url('recruitment/recruitment_campaign/' . $aRow['cp_id']) . '" onclick="init_recruitment_campaign(' . $aRow['cp_id'] . '); return false;">' . _l('view') . '</a>';

			if (has_permission('recruitment', '', 'edit') || is_admin()) {
				$name .= ' | <a href="#" onclick=' . '"' . 'edit_campaign(this,' . $aRow['cp_id'] . '); return false;' . '"' . ' data-campaign_code="' . $aRow['campaign_code'] . '" data-campaign_name="' . $aRow['campaign_name'] . '" data-position="' . $aRow['cp_position'] . '" data-form_work="' . $aRow['cp_form_work'] . '" data-department="' . $aRow['cp_department'] . '" data-amount_recruiment="' . $aRow['cp_amount_recruiment'] . '" data-workplace="' . $aRow['cp_workplace'] . '" data-salary_from="' . app_format_money($aRow['cp_salary_from'], '') . '" data-salary_to="' . app_format_money($aRow['cp_salary_to'], '') . '" data-from_date="' . _d($aRow['cp_from_date']) . '" data-to_date="' . _d($aRow['cp_to_date']) . '" data-ages_to="' . $aRow['cp_ages_to'] . '" data-ages_from="' . $aRow['cp_ages_from'] . '" data-height="' . $aRow['cp_height'] . '" data-weight="' . $aRow['cp_weight'] . '" data-reason_recruitment="' . $aRow['cp_reason_recruitment'] . '" data-gender="' . $aRow['cp_gender'] . '" data-literacy="' . $aRow['cp_literacy'] . '" data-experience="' . $aRow['cp_experience'] . '" data-proposal="' . $aRow['cp_proposal'] . '" data-manager="' . $aRow['cp_manager'] . '" data-follower="' . $aRow['cp_follower'] . '" data-rec_channel_form_id="' . $aRow['rec_channel_form_id'] . '" data-display_salary="' . $aRow['display_salary'] . '" data-company_id="' . $aRow['company_id']. '" data-cp_form_work="' . $aRow['cp_form_work'] . '"  data-currency="'.$aRow['currency'].'" >' . _l('edit') . '</a>';
			}

			if (has_permission('recruitment', '', 'delete') || is_admin()) {
				$name .= ' | <a href="' . admin_url('recruitment/delete_recruitment_campaign/' . $aRow['cp_id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
			}

			$name .= '</div>';

			$_data = $name;
		}elseif($aColumns[$i] == db_prefix() . 'rec_campaign.company_id'){
			$_data = get_rec_company_name($aRow[db_prefix() . 'rec_campaign.company_id']);

		} elseif ($aColumns[$i] == 'cp_form_work') {
			$_data = _l($aRow['cp_form_work']);
		} elseif ($aColumns[$i] == 'cp_position') {
			$_data = $aRow['position_name'];
		} elseif ($aColumns[$i] == 'cp_department') {
			$_data = $aRow['dpm_name'];
		} elseif ($aColumns[$i] == 'cp_status') {
			if ($aRow['cp_status'] == 1) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-planning-style"> ' . _l('planning') . ' </span>';
			} elseif ($aRow['cp_status'] == 3) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-progress-style"> ' . _l('in_progress') . ' </span>';
			} elseif ($aRow['cp_status'] == 4) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-finish-style"> ' . _l('finish') . ' </span>';
			} elseif ($aRow['cp_status'] == 5) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-cancel-style"> ' . _l('cancel') . ' </span>';
			}
		} elseif ($aColumns[$i] == 'rec_channel_form_id'){
			$_data = $aRow['r_form_name'];
		} elseif ($aColumns[$i] == 'cp_manager'){
			$manager_data = '';
			$manager = new_explode(',', $aRow['cp_manager']);

			foreach ($manager as $f) {
				if(is_numeric($f)){
					$manager_data .= '<a href="'.admin_url('profile/' . $f).'">
					'. staff_profile_image($f, [
						'staff-profile-image-small mright5',
					], 'small', [
						'data-toggle' => 'tooltip',
						'data-title' => get_staff_full_name($f),
					]) .'
					</a>';
				}
			}
			$_data = $manager_data;

		} elseif($aColumns[$i] == 'cp_from_date'){
			$_data = _d($aRow['cp_from_date']).' - '. _d($aRow['cp_to_date']);
		}

		$row[] = $_data;

	}
	$output['aaData'][] = $row;

}
