<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
     
    'is_name',
    'from_time',
    'interview_day',
    'campaign', 
    db_prefix().'rec_interview.id',
    'interviewer',
    'added_date',
    'added_from', 
    'send_notify', 
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'rec_interview';
$join         = [];
$where = [];

if ($this->ci->input->post('cp_from_date_filter')) {
    array_push($where, "AND date_format(interview_day, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('cp_from_date_filter')))) . "'");
}
if ($this->ci->input->post('cp_to_date_filter')) {
    array_push($where, "AND date_format(interview_day, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime(to_sql_date($this->ci->input->post('cp_to_date_filter')))) . "'");
}

if(is_admin()){
    /*view global*/
    if ($this->ci->input->post('cp_manager_filter')) {
        $arr_interviewer_filter = $this->ci->input->post('cp_manager_filter');

        $interviewer_filter = '';
        foreach ($arr_interviewer_filter as $y) {
            if ($y != '') {
                if ($interviewer_filter == '') {
                    $interviewer_filter .= 'AND (FIND_IN_SET('.$y.', '.db_prefix().'rec_interview.interviewer)';
                } else {
                    $interviewer_filter .= ' or FIND_IN_SET('.$y.', '.db_prefix().'rec_interview.interviewer)';
                }
            }
        }

        if ($interviewer_filter != '') {
            $interviewer_filter .= ')';
            array_push($where, $interviewer_filter);
        }
    }
}else{
    /*View own*/
    array_push($where, 'AND (FIND_IN_SET('.get_staff_user_id().', '.db_prefix().'rec_interview.interviewer) OR ('.db_prefix().'rec_interview.added_from = '.get_staff_user_id().'))');
}

$custom_fields = get_custom_fields('interview', [
    'show_on_table' => 1,
]);

$y            = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $y;
    if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
        $select_as = 'date_picker_cvalue_' . $y;
    }
    array_push($aColumns, 'ctable_' . $y . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $y . ' ON '.db_prefix().'rec_interview.id = ctable_' . $y . '.relid AND ctable_' . $y . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $y . '.fieldid=' . $field['id']);
    $y++;
}
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}




$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix().'rec_interview.id as id','to_time','position', 'from_hours','to_hours', 'interview_location']);

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

        if($aColumns[$i] == 'added_from'){
            $_data = '<a href="' . admin_url('staff/profile/' . $aRow['added_from']) . '">' . staff_profile_image($aRow['added_from'], [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('staff/profile/' . $aRow['added_from']) . '">' . get_staff_full_name($aRow['added_from']) . '</a>';

        }elseif($aColumns[$i] == 'is_name'){
           

            $name = '<a href="' . admin_url('recruitment/interview_schedule/' . $aRow['id'] ).'" onclick="init_recruitment_interview_schedules('.$aRow['id'].'); return false;">' . $aRow['is_name'] . '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('recruitment/interview_schedule/' . $aRow['id'] ).'" onclick="init_recruitment_interview_schedules('.$aRow['id'].'); return false;">' . _l('view') . '</a>';

            if (has_permission('recruitment', '', 'edit') || is_admin()) {
                $name .= ' | <a href="#" onclick='.'"'.'edit_interview_schedule(this,' . $aRow['id'] . '); return false;'.'"'.' data-is_name="'.$aRow['is_name'].'" data-campaign="'.$aRow['campaign'].'" data-interview_day="'._d($aRow['interview_day']).'" data-from_time="'.$aRow['from_time'].'" data-to_time="'.$aRow['to_time'].'" data-interviewer="'.$aRow['interviewer'].'" data-position="'. $aRow['position'].'" data-interview_location="'. $aRow['interview_location'].'" >' ._l('edit') . '</a>';
            }

            if (has_permission('recruitment', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('recruitment/delete_interview_schedule/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;

        }elseif($aColumns[$i] == 'from_time'){
            $from_hours_format='';
            $to_hours_format='';

            $from_hours = _dt($aRow['from_hours']);
            $from_hours = explode(" ", $from_hours);

            foreach ($from_hours as $key => $value) {
              if($key != 0){
                $from_hours_format .= $value;
                }
            }

            $to_hours = _dt($aRow['to_hours']);
            $to_hours = explode(" ", $to_hours);
            foreach ($to_hours as $key => $value) {
                  if($key != 0){
                    $to_hours_format .= $value;
                }
            }

            $_data = $from_hours_format.' - '.$to_hours_format;
        }elseif ($aColumns[$i] == 'interview_day') {
            $_data = _d($aRow['interview_day']);
        }elseif ($aColumns[$i] == 'campaign') {
            $cp = get_rec_campaign_hp($aRow['campaign']);
            if(isset($cp)){
                $_data = $cp->campaign_code.' - '.$cp->campaign_name;
            }else{
                $_data = '';
            }
            
        }elseif($aColumns[$i] == db_prefix().'rec_interview.id'){
            $can = get_candidate_interview($aRow['id']);
            $ata = '';
            foreach($can as $cad){
                $ata .= '<a href="' . admin_url('recruitment/candidate/' . $cad) . '">'.candidate_profile_image($cad,[
                    'staff-profile-image-small mright5',
                    ], 'small', [
                    'data-toggle' => 'tooltip',
                    'data-title'  =>  get_candidate_name($cad),
                    ]).'</a>';
            }
            $_data = $ata;
        }elseif($aColumns[$i] == 'interviewer'){
            $inv = new_explode(',', $aRow['interviewer']);
            $ata = '';
            foreach($inv as $iv){
                $ata .= '<a href="' . admin_url('staff/profile/' . $iv) . '">'.staff_profile_image($iv,[
                    'staff-profile-image-small mright5',
                    ], 'small', [
                    'data-toggle' => 'tooltip',
                    'data-title'  =>  get_staff_full_name($iv),
                    ]).'</a>';
            }
            $_data = $ata;
        }elseif($aColumns[$i] == 'added_date'){
            $_data = _d($aRow['added_date']);

        }elseif($aColumns[$i] == 'send_notify'){
            $option = '';

            $title = '';
            $btn_color = '';
            if($aRow['send_notify'] != 0){
                $btn_color = 'btn-warning';
                $title .= _l("The_interview_schedule_has_been_sent").' ';
                $title .= _l("to_the_interviewer_and_the_interviewees");
            }else{
                $btn_color = 'btn-success';
                $title .= _l("send_the_interview_schedule_to_the_interviewer_and_the_interviewees");
            }

            $option .= icon_btn('recruitment/send_interview_schedule/' . $aRow['id'], 'fa-sharp fa-solid fa-paper-plane', $btn_color, ['data-original-title' => $title, 'data-toggle' => 'tooltip', 'data-placement' => 'top']);

            $_data = $option;
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
