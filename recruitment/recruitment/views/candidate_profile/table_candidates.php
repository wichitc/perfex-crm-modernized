<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix().'rec_candidate.id',  
    'candidate_code',  
    'candidate_name',
    'rate',
    'skill',
    'status',
    'email',
    'phonenumber', 
    'birthday',
    'gender',
    'marital_status',
    'rec_campaign',
    ];

$sIndexColumn = 'id';
$sTable       = db_prefix().'rec_candidate';
$join         = [];
$where = [];
$string_query='';

$campaign_filter = $this->ci->input->post('campaign_filter');
$status_filter = $this->ci->input->post('status_filter');

$company_filter = $this->ci->input->post('company_filter');
$skill_filter = $this->ci->input->post('skill_filter');
$job_title_filter = $this->ci->input->post('job_title_filter');
$experience_filter = $this->ci->input->post('experience_filter');
$age_group_filter = $this->ci->input->post('age_group_filter');
$gender_filter = $this->ci->input->post('gender_filter');
$marital_status_filter = $this->ci->input->post('marital_status_filter');

if(isset($campaign_filter)&&($campaign_filter!='')){
  $campaign_filter=implode(',',$campaign_filter);
  $string_query.=" rec_campaign IN (". $campaign_filter.") AND";
}

if(isset($status_filter)&&($status_filter!='')){
  $status_filter=implode(',',$status_filter);
  $string_query.=" status IN (". $status_filter.") AND";
}

if(isset($company_filter)&&($company_filter!='')){
    $campaign_by_company = $this->ci->recruitment_model->get_recruitment_campaign_by_company($company_filter);
    if(count($campaign_by_company) > 0){
        $campaign_by_company_where = '';
        foreach ($campaign_by_company as $campaign_id) {
            if ($campaign_id != '') {
                if ($campaign_by_company_where == '') {
                    $campaign_by_company_where .= 'AND (rec_campaign = '.$campaign_id;
                } else {
                    $campaign_by_company_where .= ' OR rec_campaign = '.$campaign_id;
                }
            }
        }

        if ($campaign_by_company_where != '') {
            $campaign_by_company_where .= ')';
            $where[] = $campaign_by_company_where;
        }
    }else{
        $where[] = 'AND 1=2';
    }

}

if(isset($skill_filter)&&($skill_filter!='')){

    $skill_where = '';
    foreach ($skill_filter as $skill_id) {
        if ($skill_id != '') {
            if ($skill_where == '') {
                $skill_where .= 'AND (find_in_set(' . $skill_id . ', ' . db_prefix() . 'rec_candidate.skill) ';
            } else {
                $skill_where .= ' OR find_in_set(' . $skill_id . ', ' . db_prefix() . 'rec_candidate.skill) ';
            }
        }
    }

    if ($skill_where != '') {
        $skill_where .= ')';
        $where[] = $skill_where;
    }
}

if(isset($job_title_filter)&&($job_title_filter!='')){
    $campaign_by_job = $this->ci->recruitment_model->get_recruitment_campaign_by_job($job_title_filter);
    if(count($campaign_by_job) > 0){
        $campaign_by_job_where = '';
        foreach ($campaign_by_job as $campaign_id) {
            if ($campaign_id != '') {
                if ($campaign_by_job_where == '') {
                    $campaign_by_job_where .= 'AND (rec_campaign = '.$campaign_id;
                } else {
                    $campaign_by_job_where .= ' OR rec_campaign = '.$campaign_id;
                }
            }
        }

        if ($campaign_by_job_where != '') {
            $campaign_by_job_where .= ')';
            $where[] = $campaign_by_job_where;
        }
    }else{
        $where[] = 'AND 1=2';
    }
}
if(isset($experience_filter)&&($experience_filter!='')){
    $experience_where = '';
    foreach ($experience_filter as $experience_value) {
        if ($experience_value != '') {
            if ($experience_where == '') {
                $experience_where .= 'AND (find_in_set("' . $experience_value . '", ' . db_prefix() . 'rec_candidate.year_experience) ';
            } else {
                $experience_where .= ' OR find_in_set("' . $experience_value . '", ' . db_prefix() . 'rec_candidate.year_experience) ';
            }
        }
    }

    if ($experience_where != '') {
        $experience_where .= ')';
        $where[] = $experience_where;
    }
}
if(isset($age_group_filter)&&($age_group_filter!='')){
    $current_year = date('Y');
    if(new_strlen($age_group_filter) == 2){
        $start_year = (int)$current_year - (int)$age_group_filter;
        $start_year = $start_year.'-01-01';

        $where[] = 'AND birthday <= "'.$start_year.'"';
    }else{
        $arr_age = explode("/", $age_group_filter);
        $start_year = (int)$current_year - (int)$arr_age[0];
        $end_year = (int)$current_year - (int)$arr_age[1];
        $start_year = $start_year.'-12-31';
        $end_year = $end_year.'-01-01';

        $where[] = 'AND (birthday >= "'.$end_year .'" AND  birthday <= "'.$start_year.'")';
    }
}

if ($this->ci->input->post('birthday_filter')) {
    array_push($where, "AND date_format(birthday, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('birthday_filter')))) . "'");
}

if ($this->ci->input->post('gender_filter')) {
    $gender_filter = $this->ci->input->post('gender_filter');
    $where_gender_filter = '';
    foreach ($gender_filter as $y) {
        if ($y != '') {
            if ($where_gender_filter == '') {
                $where_gender_filter .= 'AND ('.db_prefix().'rec_candidate.gender = "' . $y . '"';
            } else {
                $where_gender_filter .= ' or '.db_prefix().'rec_candidate.gender = "' . $y . '"';
            }
        }
    }
    if ($where_gender_filter != '') {
        $where_gender_filter .= ')';
        array_push($where, $where_gender_filter);
    }
}

if ($this->ci->input->post('marital_status_filter')) {
    $marital_status_filter = $this->ci->input->post('marital_status_filter');
    $where_marital_status_filter = '';
    foreach ($marital_status_filter as $y) {
        if ($y != '') {
            if ($where_marital_status_filter == '') {
                $where_marital_status_filter .= 'AND ('.db_prefix().'rec_candidate.marital_status = "' . $y . '"';
            } else {
                $where_marital_status_filter .= ' or '.db_prefix().'rec_candidate.marital_status = "' . $y . '"';
            }
        }
    }
    if ($where_marital_status_filter != '') {
        $where_marital_status_filter .= ')';
        array_push($where, $where_marital_status_filter);
    }
}


if($string_query!=''){
  $string_query=rtrim($string_query," AND");
  $where=["where".' '.$string_query];
}

$custom_fields = get_custom_fields('candidate', [
    'show_on_table' => 1,
]);

$y            = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $y;
    if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
        $select_as = 'date_picker_cvalue_' . $y;
    }
    array_push($aColumns, 'ctable_' . $y . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $y . ' ON '.db_prefix().'rec_candidate.id = ctable_' . $y . '.relid AND ctable_' . $y . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $y . '.fieldid=' . $field['id']);
    $y++;
}
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'rec_candidate.id as id', 'last_name', 'gender', 'birthplace', 'home_town', 'place_of_issue', 'nationality', 'nation', 'religion', 'current_accommodation', 'alternate_contact_number', 'desired_salary', 'birthplace', 'home_town', 'identification', 'days_for_identity','place_of_issue', 'nationality', 'nation', 'height', 'weight', 'introduce_yourself', 'interests', 'phonenumber', 'skype',  'facebook',  'linkedin', 'resident', 'current_accommodation' ]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
         if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if($aColumns[$i] == 'id'){
            $_data = $aRow['id'];
        }elseif($aColumns[$i] == 'candidate_name'){
            $name = '<a href="' . admin_url('recruitment/candidate/' . $aRow['id']) . '">'.candidate_profile_image($aRow['id'],[
                    'staff-profile-image-small mright5',
                    ], 'small').'</a>';

            $name .= '<a href="' . admin_url('recruitment/candidate/' . $aRow['id'] ).'" >' . $aRow['candidate_name'].' '.$aRow['last_name']. '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('recruitment/candidate/' . $aRow['id'] ).'" >' . _l('view') . '</a>';

            if (has_permission('recruitment', '', 'edit') || is_admin()) {
                $name .= ' | <a href="' . admin_url('recruitment/candidates/' . $aRow['id'] ).'" >' ._l('edit') . '</a>';
            }

            if (has_permission('recruitment', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('recruitment/delete_candidate/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        }elseif ($aColumns[$i] == 'birthday') {
            $_data = _d($aRow['birthday']);
        }elseif ($aColumns[$i] == 'rec_campaign') {
            if($aRow['rec_campaign'] != null){

                $cp = get_rec_campaign_hp($aRow['rec_campaign']);
                if(isset($cp)){
                    $_data = $cp->campaign_code.' - '.$cp->campaign_name;
                }else{
                    $_data = '';
                }
            }else{
                $_data = '';

            }
            
        }elseif($aColumns[$i] == 'rate'){
            if (has_permission('recruitment', '', 'edit') || is_admin()) {
                if($aRow['status'] == 6){
                    $_data = '<a href="' . admin_url('recruitment/transfer_to_hr/' . $aRow['id'] ).'" class="btn btn-success" >' ._l('tranfer_personnels') .'</a>';
                }else{
                    $_data = '';
                }
            }else{
                $_data = '';
            }
        }elseif($aColumns[$i] == 'status'){
            $_data = get_status_candidate($aRow['status']);
        }elseif($aColumns[$i] == 'skill'){
            $skill_name_data = '';

            if(new_strlen($aRow['skill']) > 0){
                $skill_id = new_explode(',', $aRow['skill']);
                foreach($skill_id as $dpkey =>  $skill){ 
                    if(new_strlen(get_rec_skill_name($skill)) > 0){
                        $skill_name_data .= '<span class="label label-tag tag-id-1"><span class="tag">' .get_rec_skill_name($skill).'</span><span class="hide">, </span></span>&nbsp';
                    }

                    if($dpkey%3 ==0){
                        $skill_name_data .='<br/>';
                    }

                }
            }

            $_data = $skill_name_data;

        }elseif($aColumns[$i] == 'gender'){
            $_data = _l($aRow['gender'] ?? '');

        }elseif($aColumns[$i] == 'marital_status'){
            $_data = _l($aRow['marital_status'] ?? '');

        }elseif($aColumns[$i] == 'phonenumber'){
            $phonenumber_data = '';
            $phonenumber_data .= $aRow['phonenumber'];

            if(new_strlen($aRow['alternate_contact_number']) > 0){
                $phonenumber_data .= '<br/>'._l('alternate_number').': '. $aRow['alternate_contact_number'];
            }
            $_data = $phonenumber_data;
        }


        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
