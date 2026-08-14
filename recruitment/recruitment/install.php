<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'rec_job_position')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_job_position` (
      `position_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `position_name` varchar(200) NOT NULL,
      `position_description` text NULL,
      PRIMARY KEY (`position_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_proposal')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_proposal` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `proposal_name` varchar(200) NOT NULL,
      `position` int(11) NOT NULL,
      `department` int(11) NULL,
      `amount_recruiment` int(11) NULL,
      `form_work` varchar(45) NULL, 
      `workplace` varchar(255) NULL,
      `salary_from` DECIMAL(15,0) NULL,
      `salary_to` DECIMAL(15,0) NULL,
      `from_date` date NULL,
      `to_date` date NOT NULL,
      `reason_recruitment` text NULL,
      `job_description` text NULL,
      `approver` int(11) NOT NULL,
      `ages_from` int(11) NULL,
      `ages_to` int(11) NULL,
      `gender` varchar(10) NULL,
      `height` float NULL,
      `weight` float NULL,
      `literacy` varchar(200) NULL,
      `experience` varchar(200) NULL,
      `add_from` int(11) NOT NULL,
      `date_add` date NOT NULL,
      `status` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_campaign')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_campaign` (
      `cp_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `campaign_code` varchar(200) NOT NULL,
      `campaign_name` varchar(200) NOT NULL,
      `cp_proposal` text NULL,
      `cp_position` int(11) NOT NULL,
      `cp_department` int(11) NULL,
      `cp_amount_recruiment` int(11) NULL,
      `cp_form_work` varchar(45) NULL, 
      `cp_workplace` varchar(255) NULL,
      `cp_salary_from` DECIMAL(15,0) NULL,
      `cp_salary_to` DECIMAL(15,0) NULL,
      `cp_from_date` date NULL,
      `cp_to_date` date NOT NULL,
      `cp_reason_recruitment` text NULL,
      `cp_job_description` text NULL,
      `cp_manager` text NULL,
      `cp_follower` text NULL,
      `cp_ages_from` int(11) NULL,
      `cp_ages_to` int(11) NULL,
      `cp_gender` varchar(10) NULL,
      `cp_height` float NULL,
      `cp_weight` float NULL,
      `cp_literacy` varchar(200) NULL,
      `cp_experience` varchar(200) NULL,
      `cp_add_from` int(11) NOT NULL,
      `cp_date_add` date NOT NULL,
      `cp_status` int(11) NOT NULL,
      PRIMARY KEY (`cp_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_candidate')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_candidate` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `rec_campaign` int(11) NULL,
      `candidate_code` varchar(200) NOT NULL,
      `candidate_name` varchar(200) NOT NULL,
      `birthday` date NULL,
      `gender` varchar(11) NULL,
      `birthplace` text NULL,
      `home_town` text NULL,
      `identification` varchar(45) NULL, 
      `days_for_identity` date NULL,
      `place_of_issue` varchar(255) NULL,
      `marital_status` varchar(11) NULL,
      `nationality` varchar(100) NULL,
      `nation` varchar(100) NOT NULL,
      `religion` varchar(100) NULL,
      `height` float NULL,
      `weight` float NULL,
      `introduce_yourself` text NULL,
      `phonenumber` int(15) NULL,
      `email` text NULL,
      `skype` text NULL,
      `facebook` text NULL,
      `resident` text NULL,
      `current_accommodation` text NULL,
      `status` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('date_add' ,db_prefix() . 'rec_candidate')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    ADD COLUMN `date_add` DATE NULL AFTER `status`
  ;");
}

if (!$CI->db->field_exists('desired_salary' ,db_prefix() . 'rec_candidate')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    ADD COLUMN `desired_salary` DATE NULL AFTER `status`
  ;");
}

if (!$CI->db->field_exists('rate' ,db_prefix() . 'rec_candidate')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    ADD COLUMN `rate` int(11) NULL AFTER `status`
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'cd_work_experience')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "cd_work_experience` (
      `we_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `candidate` int(11) NOT NULL,
      `from_date` date NULL,
      `to_date` date NULL,
      `company` varchar(200) NULL,
      `position` varchar(200) NULL,
      `contact_person` varchar(200) NULL,
      `salary` varchar(200) NULL,
      `reason_quitwork` varchar(200) NULL,
      `job_description` varchar(200) NULL,
      PRIMARY KEY (`we_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if ($CI->db->field_exists('job_description' ,db_prefix() . 'cd_work_experience')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "cd_work_experience`
    CHANGE COLUMN `job_description` `job_description` TEXT NULL
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'cd_literacy')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "cd_literacy` (
      `li_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `candidate` int(11) NOT NULL,
      `literacy_from_date` date NULL,
      `literacy_to_date` date NULL,
      `diploma` varchar(200) NULL,
      `training_places` varchar(200) NULL,
      `specialized` varchar(200) NULL,
      `training_form` varchar(200) NULL,
      PRIMARY KEY (`li_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'cd_family_infor')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "cd_family_infor` (
      `fi_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `candidate` int(11) NOT NULL,
      `relationship` varchar(100) NULL,
      `name` varchar(200) NULL,
      `fi_birthday` date NULL,
      `job` varchar(200) NULL,
      `address` varchar(200) NULL,
      `phone` int(15) NULL,
      PRIMARY KEY (`fi_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_interview')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_interview` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `campaign` int(11) NOT NULL,
      `is_name` varchar(100) NOT NULL,
      `interview_day` varchar(200) NOT NULL,
      `from_time` text NOT NULL,
      `to_time` text NOT NULL,
      `from_hours` datetime NULL,
      `to_hours` datetime NULL,
      `interviewer` text NOT NULL,
      `added_from` int(11) NOT NULL,
      `added_date` date NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'cd_interview')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "cd_interview` (
      `in_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `candidate` int(11) NOT NULL,
      `interview` int(11) NOT NULL,
      PRIMARY KEY (`in_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'cd_care')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "cd_care` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `candidate` int(11) NOT NULL,
      `care_time` datetime NOT NULL,
      `care_result` text NOT NULL,
      `description` text NULL,
      `add_from` int(11) NOT NULL,
      `add_time` datetime NULL,
      `type` varchar(45) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


if (!$CI->db->table_exists(db_prefix() . 'rec_criteria')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_criteria` (
      `criteria_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `criteria_type` varchar(45) NOT NULL,
      `criteria_title` varchar(200) NOT NULL,
      `group_criteria` int(11)  NULL,
      `description` text NULL,
      `add_from` int(11) NOT NULL,
      `add_date` date NULL,
      `score_des1` text NULL,
      `score_des2` text NULL,
      `score_des3` text NULL,
      `score_des4` text NULL,
      `score_des5` text NULL,
      PRIMARY KEY (`criteria_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_evaluation_form')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_evaluation_form` (
      `form_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `form_name` varchar(200) NOT NULL,
      `position` int(11) NULL,
      `add_from` int(11) NOT NULL,
      `add_date` date NULL,
      PRIMARY KEY (`form_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_list_criteria')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_list_criteria` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `evaluation_form` int(11) NOT NULL,
      `group_criteria` int(11) NOT NULL,
      `evaluation_criteria` int(11) NOT NULL,
      `percent` float NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_cd_evaluation')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_cd_evaluation` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `criteria` int(11) NOT NULL,
      `rate_score` int(11) NOT NULL,
      `assessor` int(11) NOT NULL,
      `evaluation_date` datetime NOT NULL,
      `percent` int(11) NOT NULL,
      `candidate` int(11) NOT NULL,
      `feedback` TEXT NOT NULL,
      `group_criteria` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_set_transfer_record')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_set_transfer_record` (
      `set_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `send_to` varchar(45) NOT NULL,
      `email_to` text NULL,
      `add_from` int(11) NOT NULL,
      `add_date` date NOT NULL,
      `subject` text NOT NULL,
      `content` text NULL,
      PRIMARY KEY (`set_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->field_exists('order' ,db_prefix() . 'rec_set_transfer_record')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_set_transfer_record`
    ADD COLUMN `order` int(11) NOT NULL AFTER `set_id`
  ;");
}

if (!$CI->db->table_exists(db_prefix() . 'rec_campaign_form_web')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_campaign_form_web` (
     `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
     `rec_campaign_id` int(11) NOT NULL, 
     `form_type` int(11) NULL,
     `lead_source` varchar(10) NULL,
      `lead_status` varchar(10) NULL,
      `notify_ids_staff` text NULL,
      `notify_ids_roles` text NULL,
      `form_key` varchar(32) NULL,
      `notify_lead_imported` int(11) NULL DEFAULT '1',
      `notify_type` varchar(20) DEFAULT NULL,
      `notify_ids` mediumtext,
      `responsible` int(11) NULL DEFAULT '0',
      `name` varchar(191) NULL,
      `form_data` mediumtext,
      `recaptcha` int(11) NULL DEFAULT '0',
      `submit_btn_name` varchar(40) DEFAULT NULL,
      `success_submit_msg` text,
      `language` varchar(40) DEFAULT NULL,
      `allow_duplicate` int(11) NULL DEFAULT '1',
      `mark_public` int(11) NULL DEFAULT '0',
      `track_duplicate_field` varchar(20) DEFAULT NULL,
      `track_duplicate_field_and` varchar(20) DEFAULT NULL,
      `create_task_on_duplicate` int(11) NULL DEFAULT '0',
    PRIMARY KEY (`id`, `rec_campaign_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if (!$CI->db->table_exists(db_prefix() . 'web_to_recruitment')) {
     $CI->db->query('CREATE TABLE `' . db_prefix()."web_to_recruitment` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `campaign_code` varchar(200) NULL,
      `campaign_name` varchar(200) NULL,
      `cp_proposal` text NULL,
      `cp_position` int(11) NULL,
      `cp_department` int(11) NULL,
      `cp_amount_recruiment` int(11) NULL,
      `cp_form_work` varchar(45) NULL, 
      `cp_workplace` varchar(255) NULL,
      `cp_salary_from` DECIMAL(15,0) NULL,
      `cp_salary_to` DECIMAL(15,0) NULL,
      `cp_from_date` date NULL,
      `cp_to_date` date NULL,
      `cp_reason_recruitment` text NULL,
      `cp_job_description` text NULL,
      `cp_manager` text NULL,
      `cp_follower` text NULL,
      `cp_ages_from` int(11) NULL,
      `cp_ages_to` int(11) NULL,
      `cp_gender` varchar(10) NULL,
      `cp_height` float NULL,
      `cp_weight` float NULL,
      `cp_literacy` varchar(200) NULL,
      `cp_experience` varchar(200) NULL,
      `cp_add_from` int(11) NULL,
      `cp_date_add` date NULL,
      `cp_status` int(11) NULL,
      `nation` varchar(15),
      `nationality` varchar(15),
      `religion` varchar(15),
      `marital_status` varchar(15),
      `birthplace` varchar(200),
      `home_town` varchar(200),
      `resident` varchar(200),
      `current_accommodation` varchar(200),
      `cp_desired_salary` varchar(10) NULL,
      `specialized` varchar(100),
      `training_form` varchar(50),
      `training_places` varchar(50),

      `lead_source` varchar(10) NULL,
      `lead_status` varchar(10) NULL,
      `notify_ids_staff` text NULL,
      `notify_ids_roles` text NULL,
      `form_key` varchar(32) NULL,
      `notify_lead_imported` int(11) NULL DEFAULT '1',
      `notify_type` varchar(20) DEFAULT NULL,
      `notify_ids` mediumtext,
      `responsible` int(11) NULL DEFAULT '0',
      `name` varchar(191) NULL,
      `form_data` mediumtext,
      `recaptcha` int(11) NULL DEFAULT '0',
      `submit_btn_name` varchar(40) DEFAULT NULL,
      `success_submit_msg` text,
      `language` varchar(40) DEFAULT NULL,
      `allow_duplicate` int(11) NULL DEFAULT '1',
      `mark_public` int(11) NULL DEFAULT '0',
      `track_duplicate_field` varchar(20) DEFAULT NULL,
      `track_duplicate_field_and` varchar(20) DEFAULT NULL,
      `create_task_on_duplicate` int(11) NULL DEFAULT '0',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
    $form_data='[{"label":"Croatia","value":"55"},{"label":"Cuba","value":"56"},{"label":"Curacao","value":"57"},{"label":"Cyprus","value":"58"},{"label":"Czech Republic","value":"59"},{"label":"Democratic Republic of the Congo","value":"60"},{"label":"Denmark","value":"61"},{"label":"Djibouti","value":"62"},{"label":"Dominica","value":"63"},{"label":"Dominican Republic","value":"64"},{"label":"Ecuador","value":"65"},{"label":"Egypt","value":"66"},{"label":"El Salvador","value":"67"},{"label":"Equatorial Guinea","value":"68"},{"label":"Eritrea","value":"69"},{"label":"Estonia","value":"70"},{"label":"Ethiopia","value":"71"},{"label":"Falkland Islands (Malvinas)","value":"72"},{"label":"Faroe Islands","value":"73"},{"label":"Fiji","value":"74"},{"label":"Finland","value":"75"},{"label":"France","value":"76"},{"label":"French Guiana","value":"77"},{"label":"French Polynesia","value":"78"},{"label":"French Southern Territories","value":"79"},{"label":"Gabon","value":"80"},{"label":"Gambia","value":"81"},{"label":"Georgia","value":"82"},{"label":"Germany","value":"83"},{"label":"Ghana","value":"84"},{"label":"Gibraltar","value":"85"},{"label":"Greece","value":"86"},{"label":"Greenland","value":"87"},{"label":"Grenada","value":"88"},{"label":"Guadaloupe","value":"89"},{"label":"Guam","value":"90"},{"label":"Guatemala","value":"91"},{"label":"Guernsey","value":"92"},{"label":"Guinea","value":"93"},{"label":"Guinea-Bissau","value":"94"},{"label":"Guyana","value":"95"},{"label":"Haiti","value":"96"},{"label":"Heard Island and McDonald Islands","value":"97"},{"label":"Honduras","value":"98"},{"label":"Hong Kong","value":"99"},{"label":"Hungary","value":"100"},{"label":"Iceland","value":"101"},{"label":"India","value":"102"},{"label":"Indonesia","value":"103"},{"label":"Iran","value":"104"},{"label":"Iraq","value":"105"},{"label":"Ireland","value":"106"},{"label":"Isle of Man","value":"107"},{"label":"Israel","value":"108"},{"label":"Italy","value":"109"},{"label":"Jamaica","value":"110"},{"label":"Japan","value":"111"},{"label":"Jersey","value":"112"},{"label":"Jordan","value":"113"},{"label":"Kazakhstan","value":"114"},{"label":"Kenya","value":"115"},{"label":"Kiribati","value":"116"},{"label":"Kosovo","value":"117"},{"label":"Kuwait","value":"118"},{"label":"Kyrgyzstan","value":"119"},{"label":"Laos","value":"120"},{"label":"Latvia","value":"121"},{"label":"Lebanon","value":"122"},{"label":"Lesotho","value":"123"},{"label":"Liberia","value":"124"},{"label":"Libya","value":"125"},{"label":"Liechtenstein","value":"126"},{"label":"Lithuania","value":"127"},{"label":"Luxembourg","value":"128"},{"label":"Macao","value":"129"},{"label":"North Macedonia","value":"130"},{"label":"Madagascar","value":"131"},{"label":"Malawi","value":"132"},{"label":"Malaysia","value":"133"},{"label":"Maldives","value":"134"},{"label":"Mali","value":"135"},{"label":"Malta","value":"136"},{"label":"Marshall Islands","value":"137"},{"label":"Martinique","value":"138"},{"label":"Mauritania","value":"139"},{"label":"Mauritius","value":"140"},{"label":"Mayotte","value":"141"},{"label":"Mexico","value":"142"},{"label":"Micronesia","value":"143"},{"label":"Moldava","value":"144"},{"label":"Monaco","value":"145"},{"label":"Mongolia","value":"146"},{"label":"Montenegro","value":"147"},{"label":"Montserrat","value":"148"},{"label":"Morocco","value":"149"},{"label":"Mozambique","value":"150"},{"label":"Myanmar (Burma)","value":"151"},{"label":"Namibia","value":"152"},{"label":"Nauru","value":"153"},{"label":"Nepal","value":"154"},{"label":"Netherlands","value":"155"},{"label":"New Caledonia","value":"156"},{"label":"New Zealand","value":"157"},{"label":"Nicaragua","value":"158"},{"label":"Niger","value":"159"},{"label":"Nigeria","value":"160"},{"label":"Niue","value":"161"},{"label":"Norfolk Island","value":"162"},{"label":"North Korea","value":"163"},{"label":"Northern Mariana Islands","value":"164"},{"label":"Norway","value":"165"},{"label":"Oman","value":"166"},{"label":"Pakistan","value":"167"},{"label":"Palau","value":"168"},{"label":"Palestine","value":"169"},{"label":"Panama","value":"170"},{"label":"Papua New Guinea","value":"171"},{"label":"Paraguay","value":"172"},{"label":"Peru","value":"173"},{"label":"Phillipines","value":"174"},{"label":"Pitcairn","value":"175"},{"label":"Poland","value":"176"},{"label":"Portugal","value":"177"},{"label":"Puerto Rico","value":"178"},{"label":"Qatar","value":"179"},{"label":"Reunion","value":"180"},{"label":"Romania","value":"181"},{"label":"Russia","value":"182"},{"label":"Rwanda","value":"183"},{"label":"Saint Barthelemy","value":"184"},{"label":"Saint Helena","value":"185"},{"label":"Saint Kitts and Nevis","value":"186"},{"label":"Saint Lucia","value":"187"},{"label":"Saint Martin","value":"188"},{"label":"Saint Pierre and Miquelon","value":"189"},{"label":"Saint Vincent and the Grenadines","value":"190"},{"label":"Samoa","value":"191"},{"label":"San Marino","value":"192"},{"label":"Sao Tome and Principe","value":"193"},{"label":"Saudi Arabia","value":"194"},{"label":"Senegal","value":"195"},{"label":"Serbia","value":"196"},{"label":"Seychelles","value":"197"},{"label":"Sierra Leone","value":"198"},{"label":"Singapore","value":"199"},{"label":"Sint Maarten","value":"200"},{"label":"Slovakia","value":"201"},{"label":"Slovenia","value":"202"},{"label":"Solomon Islands","value":"203"},{"label":"Somalia","value":"204"},{"label":"South Africa","value":"205"},{"label":"South Georgia and the South Sandwich Islands","value":"206"},{"label":"South Korea","value":"207"},{"label":"South Sudan","value":"208"},{"label":"Spain","value":"209"},{"label":"Sri Lanka","value":"210"},{"label":"Sudan","value":"211"},{"label":"Suriname","value":"212"},{"label":"Svalbard and Jan Mayen","value":"213"},{"label":"Swaziland","value":"214"},{"label":"Sweden","value":"215"},{"label":"Switzerland","value":"216"},{"label":"Syria","value":"217"},{"label":"Taiwan","value":"218"},{"label":"Tajikistan","value":"219"},{"label":"Tanzania","value":"220"},{"label":"Thailand","value":"221"},{"label":"Timor-Leste (East Timor)","value":"222"},{"label":"Togo","value":"223"},{"label":"Tokelau","value":"224"},{"label":"Tonga","value":"225"},{"label":"Trinidad and Tobago","value":"226"},{"label":"Tunisia","value":"227"},{"label":"Turkey","value":"228"},{"label":"Turkmenistan","value":"229"},{"label":"Turks and Caicos Islands","value":"230"},{"label":"Tuvalu","value":"231"},{"label":"Uganda","value":"232"},{"label":"Ukraine","value":"233"},{"label":"United Arab Emirates","value":"234"},{"label":"United Kingdom","value":"235"},{"label":"United States","value":"236"},{"label":"United States Minor Outlying Islands","value":"237"},{"label":"Uruguay","value":"238"},{"label":"Uzbekistan","value":"239"},{"label":"Vanuatu","value":"240"},{"label":"Vatican City","value":"241"},{"label":"Venezuela","value":"242"},{"label":"Vietnam","value":"243","selected":true},{"label":"Virgin Islands, British","value":"244"},{"label":"Virgin Islands, US","value":"245"},{"label":"Wallis and Futuna","value":"246"},{"label":"Western Sahara","value":"247"},{"label":"Yemen","value":"248"},{"label":"Zambia","value":"249"},{"label":"Zimbabwe","value":"250"}]},{"type":"text","label":"_national","className":"form-control","name":"nation","subtype":"text"},{"type":"text","label":"_religion","className":"form-control","name":"religion","subtype":"text"},{"type":"text","label":"_phone","className":"form-control","name":"phonenumber","subtype":"text"},{"type":"select","label":"_diploma","className":"form-control","name":"diploma","values":[{"label":"","value":""},{"label":"master_s_degree","value":"0"},{"label":"Ph_D","value":"1"},{"label":"bachelor","value":"2"},{"label":"university","value":"3"},{"label":"vocational_colleges","value":"4"},{"label":"vocational","value":"5"},{"label":"high_school","value":"6"}]},{"type":"text","label":"training_places","className":"form-control","name":"training_places","subtype":"text"},{"type":"text","label":"specialized","className":"form-control","name":"specialized","subtype":"text"},{"type":"text","label":"forms_of_training","className":"form-control","name":"training_form","subtype":"text"},{"type":"text","label":"issue_date_identification_card","className":"form-control fc-datepicker","name":"days_for_identity","subtype":"text"}]';
      $data['campaign_code']="";
      $data['campaign_name']="";
      $data['cp_proposal']="";
      $data['cp_position']="";
      $data['cp_department']="";
      $data['cp_amount_recruiment']="1";
      $data['cp_form_work']="";
      $data['cp_workplace']="";
      $data['cp_salary_from']="";
      $data['cp_salary_to']="";
      $data['cp_from_date']="";
      $data['cp_to_date']="";
      $data['cp_reason_recruitment']="";
      $data['cp_job_description']="";
      $data['cp_manager']="";
      $data['cp_follower']="";
      $data['cp_ages_from']="15";
      $data['cp_ages_to']="60";
      $data['cp_gender']="";
      $data['cp_height']="1";
      $data['cp_weight']="40";
      $data['cp_literacy']="";
      $data['cp_experience']="";
      $data['cp_add_from']="";
      $data['cp_date_add']="";
      $data['cp_status']="";
      $data['nation']="";
      $data['nationality']="";
      $data['religion']="";
      $data['marital_status']="";
      $data['birthplace']="";
      $data['home_town']="";
      $data['resident']="";
      $data['current_accommodation']="";
      $data['cp_desired_salary']="";
      $data['specialized']="";
      $data['training_form']="";
      $data['training_places']="";

      $data['lead_source']="";
      $data['lead_status']="";
      $data['notify_ids_staff']="";
      $data['notify_ids_roles']="";
      $data['form_key']=app_generate_hash();
      $data['notify_lead_imported']="";
      $data['notify_type']="";
      $data['notify_ids']="";
      $data['responsible']="";
      $data['name']="recruitment_form";
      $data['form_data']=$form_data;
      $data['recaptcha']="";
      $data['submit_btn_name']="sent"; 
      $data['success_submit_msg']="sent_successfully";  
      $data['language']="";
      $data['allow_duplicate']="";
      $data['mark_public']="0";
      $data['track_duplicate_field']="";
      $data['track_duplicate_field_and']="";
      $data['create_task_on_duplicate']="";
  $CI->db->insert(db_prefix() . 'web_to_recruitment', $data);
}

if ($CI->db->field_exists('name' ,db_prefix() . 'rec_campaign_form_web')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_campaign_form_web`
    CHANGE COLUMN `name` `r_form_name` VARCHAR(191) NULL
  ;");
}


if (!$CI->db->field_exists('recruitment_channel' ,db_prefix() . 'rec_candidate')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    ADD COLUMN `recruitment_channel` int(11) NULL AFTER `date_add`
  ;");
}
$CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    CHANGE COLUMN `desired_salary` `desired_salary` DECIMAL(15,0) NULL DEFAULT NULL;");
$CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    CHANGE COLUMN `phonenumber` `phonenumber` TEXT NULL DEFAULT NULL;");

if (!$CI->db->table_exists(db_prefix() . 'cd_skill')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "cd_skill` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `candidate` int(11) NOT NULL,
      `skill_name` text NULL,
      `skill_description` text NULL,
      PRIMARY KEY (`id`,`candidate`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'rec_skill')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_skill` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `skill_name` text  NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
  }

  if (!$CI->db->field_exists('skill', 'rec_candidate')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_candidate` 
      ADD COLUMN `skill` text
      ;');            
  }

  if (!$CI->db->field_exists('interests', 'rec_candidate')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_candidate` 
      ADD COLUMN `interests` text
      ;');            
  }

    //update recruitment portal
  if (!$CI->db->table_exists(db_prefix() . 'rec_company')) {
      $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_company` (

        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `company_name` varchar(200) NOT NULL,
        `company_description` text NULL,
        `company_address` varchar(200) NULL,
        `company_industry` text NULL,

        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
  }

  if (!$CI->db->field_exists('display_salary', 'rec_campaign')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_campaign` 
      ADD COLUMN `display_salary` int(15) null
      ;');            
  }

  if (!$CI->db->table_exists(db_prefix() . 'job_industry')) {
      $CI->db->query('CREATE TABLE `' . db_prefix() . "job_industry` (

        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `industry_name` varchar(200) NOT NULL,
        `industry_description` text NULL,

        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
  }

  if (!$CI->db->field_exists('industry_id', 'rec_job_position')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_job_position` 
      ADD COLUMN `industry_id` int(15) null
      ;');            
  }


  if (!$CI->db->field_exists('company_id', 'rec_job_position')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_job_position` 
      ADD COLUMN `company_id` int(15) null
      ;');            
  }
  
  if (!$CI->db->field_exists('job_skill', 'rec_job_position')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_job_position` 
      ADD COLUMN `job_skill` text
      ;');            
  }

  if (!$CI->db->field_exists('rec_channel_form_id', 'rec_campaign')) {
    $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_campaign` 
    ADD COLUMN `rec_channel_form_id` int(15) null
    ;');            
}
  if (!$CI->db->field_exists('position', 'rec_interview')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_interview` 
      ADD COLUMN `position` int(15) null
      ;');            
  }

    if (!$CI->db->field_exists('linkedin', 'rec_candidate')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_candidate` 
      ADD COLUMN `linkedin` text null
      ;');            
  } 

  if (!$CI->db->field_exists('alternate_contact_number', 'rec_candidate')) {
      $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_candidate` 
      ADD COLUMN `alternate_contact_number` varchar(15) null
      ;');            
  }

 add_option('recruitment_create_campaign_with_plan ', 1, 1);


  if (!$CI->db->field_exists('company_id', 'rec_campaign')) {
    $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_campaign` 
    ADD COLUMN `company_id` int(15) null
    ;');            
  }

  //version 115
  if (!$CI->db->field_exists('last_name' ,db_prefix() . 'rec_candidate')) { 
    $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
      ADD COLUMN `last_name` VARCHAR(200) NULL
      ;");
  }

  if (!$CI->db->field_exists('year_experience' ,db_prefix() . 'rec_candidate')) { 
   $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
   ADD COLUMN `year_experience` VARCHAR(200) NULL
   ;");
 }

 add_option('display_quantity_to_be_recruited', 1, 1);

 if (!$CI->db->table_exists(db_prefix() . 'rec_activity_log')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "rec_activity_log` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rel_id` int NULL ,
  `rel_type` varchar(100) NULL ,
  `description` mediumtext NULL,
  `additional_data` text NULL,
  `date` datetime NULL,
  `staffid` int(11) NULL,
  `full_name` varchar(100) NULL,

  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('cd_from_hours' ,db_prefix() . 'cd_interview')) { 
 $CI->db->query('ALTER TABLE `' . db_prefix() . "cd_interview`

 ADD COLUMN `cd_from_hours` datetime NULL,
 ADD COLUMN `cd_to_hours` datetime NULL
 ;");
}

if (!$CI->db->field_exists('send_notify' ,db_prefix() . 'rec_interview')) { 
 $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_interview`

 ADD COLUMN `send_notify` int(1) NULL DEFAULT 0
 ;");
}

if (!$CI->db->field_exists('interview_location' ,db_prefix() . 'rec_interview')) { 
 $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_interview`

 ADD COLUMN `interview_location` TEXT NULL
 ;");
}

if (!$CI->db->field_exists("password" ,db_prefix() . "rec_candidate")) { 
  $CI->db->query("ALTER TABLE `" . db_prefix() . "rec_candidate`

  ADD COLUMN `password` varchar(255) NULL,
  ADD COLUMN `new_pass_key` varchar(32) NULL,
  ADD COLUMN `new_pass_key_requested` datetime NULL,
  ADD COLUMN `email_verified_at` datetime NULL,
  ADD COLUMN `email_verification_key` varchar(32) NULL,
  ADD COLUMN `email_verification_sent_at` DATETIME NULL,
  ADD COLUMN `last_ip` varchar(40) NULL,
  ADD COLUMN `last_login` DATETIME NULL,
  ADD COLUMN `last_password_change` DATETIME NULL,
  ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT '1'

  ;");
}

add_option('candidate_code_prefix', 'ID', 1);
add_option('candidate_code_number', 1, 1);
add_option('send_email_welcome_for_new_candidate', 1, 1);

if (!$CI->db->table_exists(db_prefix() . "rec_applied_jobs")) {
  $CI->db->query("CREATE TABLE `" . db_prefix() . "rec_applied_jobs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `candidate_id` int NULL ,
  `campaign_id` int NULL ,
  `date_created` datetime NULL,
  `status` TEXT NULL,
  `activate` TEXT NULL,

  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->field_exists('status' ,db_prefix() . 'cd_interview')) { 
 $CI->db->query('ALTER TABLE `' . db_prefix() . "cd_interview`
 ADD COLUMN `status` int(11) NULL
 ;");
}

if (!$CI->db->field_exists("candidate_id" ,db_prefix() . "user_meta")) { 
 $CI->db->query("ALTER TABLE `" . db_prefix() . "user_meta`
 ADD COLUMN `candidate_id` bigint(20) unsigned NOT NULL DEFAULT '0'
 ;");
}
if (!$CI->db->field_exists("candidate_id" ,db_prefix() . "consents")) { 
 $CI->db->query("ALTER TABLE `" . db_prefix() . "consents`
 ADD COLUMN `candidate_id` bigint(20) unsigned NOT NULL DEFAULT '0'
 ;");
}

create_email_template("Changed Candidate Status", "<br />Hi {candidate_name} {last_name}<br /><br />I would like to inform you that your candidate profile status changed to {candidate_status}<br /><br />I will contact you again as soon as I have any news.<br /><br />Kind Regards,<br />{email_signature}.", "change_candidate_status", "Change Candidate Status (Sent to Candidate)", "change-candidate-status-to-candidate");

create_email_template("Changed Candidate Job Applied Status", "<br />Hi {candidate_name} {last_name}<br /><br />I would like to inform you that your Job Applied status changed to {job_applied_status}<br /><br />I will contact you again as soon as I have any news.<br /><br />Kind Regards,<br />{email_signature}.", "change_candidate_job_applied_status", "Change Candidate Job Applied Status (Sent to Candidate)", "change-candidate-job-applied-status-to-candidate");

create_email_template("Changed Candidate Interview Schedule Status", "<br />Hi {candidate_name} {last_name}<br /><br />I would like to inform you that your Interview Schedule status changed to {interview_schedule_status}<br /><br />I will contact you again as soon as I have any news.<br /><br />Kind Regards,<br />{email_signature}.", "change_candidate_interview_schedule_status", "Change Candidate Interview Schedule Status (Sent to Candidate)", "change-candidate-interview-schedule-status-to-candidate");

if (!$CI->db->table_exists(db_prefix() . "rec_notifications")) {
  $CI->db->query("CREATE TABLE `" . db_prefix() . "rec_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isread` int(11) NOT NULL DEFAULT '0',
  `isread_inline` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `description` text NOT NULL,
  `fromuserid` int(11) NOT NULL,
  `fromclientid` int(11) NOT NULL DEFAULT '0',
  `from_fullname` varchar(100) NOT NULL,
  `touserid` int(11) NOT NULL,
  `fromcompany` int(11) DEFAULT NULL,
  `link` mediumtext,
  `additional_data` text,

  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->field_exists('job_meta_title', 'rec_campaign')) {
  $CI->db->query('ALTER TABLE `'.db_prefix() . 'rec_campaign` 
  ADD COLUMN `job_meta_title` text null,
  ADD COLUMN `job_meta_description` text null
  ;');            
}

create_email_template('New candidate have applied', '<p><span style="font-size: 12pt;">New Candidate have been applied.</span><br /><br /><span style="font-size: 12pt;">You can view the Candidate profile on the following link: <a href="{candidate_link}">#{candidate_link}</a><br /><br />Kind Regards,</span><br /><span style="font-size: 12pt;">{email_signature}</span></p>', 'new_candidate_have_applied', 'New candidate have applied (Sent to Responsible)', 'new-candidate-have-applied');

create_email_template("[{companyname}] Invitation to Interview", "<p>Dear<span> <strong>{candidate_name}</strong></span>,</p>
<p>Thank you for your application to the<span> <strong>{position} </strong></span><span></span>role at<span> <strong>{companyname}</strong></span><strong>.</strong></p>
<p>We would like to invite you to interview for the role with<span> <strong>{interviewer}</strong></span>,<span> <strong>{is_name}</strong></span><strong>.</strong><span> The interview will take place on <strong>{from_time} - {to_time} on {interview_day}</strong></span>.</p>
<p>Our office is located at {interview_location}</p>
<p>Please reply to this email directly with your availability time</p>
<p>We look forward to speaking with you.</p>
<p>Sincerely,</p>
<p><span>{email_signature}</span><span></span></p>", "send_interview_schedule", "Send Interview Schedule (Sent to Candidate)", "send-interview-schedule-to-candidate");

if (!$CI->db->field_exists('currency' ,db_prefix() . 'rec_proposal')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_proposal`
    ADD COLUMN `currency` int(11) NOT NULL DEFAULT '0'
  ;");
}
if (!$CI->db->field_exists('currency' ,db_prefix() . 'rec_campaign')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_campaign`
    ADD COLUMN `currency` int(11) NOT NULL DEFAULT '0'
  ;");
}
if (!$CI->db->field_exists('currency' ,db_prefix() . 'rec_candidate')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "rec_candidate`
    ADD COLUMN `currency` int(11) NOT NULL DEFAULT '0'
  ;");
}