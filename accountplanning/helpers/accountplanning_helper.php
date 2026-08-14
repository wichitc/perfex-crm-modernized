<?php 
defined('BASEPATH') or exit('No direct script access allowed');

function handle_accountplanning($accountplanning_id, $index_name = 'attachments')
{
    $path = get_upload_path_by_type('accountplanning') . $accountplanning_id . '/';
    $file_uploaded = false;
    $CI   = & get_instance();

    if (isset($_FILES[$index_name])) {
        _file_attachments_index_fix($index_name);
        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            // Get the temp file path
            $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];
            // Make sure we have a filepath

            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $file_uploaded = true;
                    $attachment    = [];
                    $attachment[]  = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['attachments']['type'][$i],
                    ];
                    $CI->misc_model->add_attachment_to_database($accountplanning_id, 'accountplanning', $attachment);
                }

            }
        }
    }

    if ($file_uploaded == true) {
        return true;
    }
    return false;
}

function reformat_currency($value)
{
    return str_replace(',','', $value);
}

/**
 * Notify staff when client requests plan update
 */
function accountplanning_notify_staff_of_update_request($plan_id, $plan, $contact_name = '')
{
    $CI = &get_instance();
    $tbl = db_prefix() . 'accountplanning_update_requests';
    if (!$CI->db->table_exists($tbl)) {
        return;
    }
    $staff_ids = [];
    $CI->db->select('rel_id');
    $CI->db->from(db_prefix() . 'accountplanning_team');
    $CI->db->where('accountplanning_id', (int) $plan_id);
    $CI->db->where('rel_type', 'pmax_team');
    $rows = $CI->db->get()->result_array();
    foreach ($rows as $r) {
        $staff_ids[] = (int) $r['rel_id'];
    }
    if (empty($staff_ids) && function_exists('get_staff_with_permission')) {
        $staff_ids = array_column(get_staff_with_permission('accountplanning', 'view'), 'staffid');
    }
    if (empty($staff_ids) && function_exists('get_staff_with_permission')) {
        $staff_ids = array_column(get_staff_with_permission('accountplanning', 'edit'), 'staffid');
    }
    $subject = is_object($plan) ? ($plan->subject ?? '') : ($plan['subject'] ?? '');
    $link = admin_url('accountplanning/view/' . $plan_id);
    $desc = 'Client requested account plan update: ' . $subject . ($contact_name ? ' (' . $contact_name . ')' : '');
    foreach (array_unique($staff_ids) as $sid) {
        if (function_exists('add_notification')) {
            add_notification([
                'description' => $desc,
                'touserid' => $sid,
                'link' => $link,
            ]);
        }
    }
}

/**
 * Trigger webhooks for account plan events
 * @param string $event plan.created|plan.updated|plan.deleted
 * @param int $plan_id
 * @param array $data optional payload (will be sanitized)
 */
function accountplanning_trigger_webhooks($event, $plan_id, $data = [])
{
    $CI = &get_instance();
    $tbl = db_prefix() . 'accountplanning_webhooks';
    if (!$CI->db->table_exists($tbl)) {
        return;
    }
    $CI->db->where('active', 1);
    $webhooks = $CI->db->get($tbl)->result_array();
    if (empty($webhooks)) {
        return;
    }
    $safe_data = [];
    $safe_keys = ['subject', 'status', 'client_id', 'date', 'revenue_next_year', 'client_status', 'bcg_model'];
    foreach ($safe_keys as $k) {
        if (isset($data[$k]) && !is_array($data[$k]) && !is_object($data[$k])) {
            $safe_data[$k] = $data[$k];
        }
    }
    $payload = array_merge([
        'event'    => $event,
        'plan_id'  => (int) $plan_id,
        'datetime' => date('c'),
    ], $safe_data);
    $json = json_encode($payload);
    foreach ($webhooks as $wh) {
        $events = !empty($wh['events']) ? json_decode($wh['events'], true) : [];
        if (is_array($events) && count($events) > 0 && !in_array($event, $events)) {
            continue;
        }
        $url = trim($wh['url']);
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            continue;
        }
        $sslVerify = get_option('accountplanning_webhook_ssl_verify') !== '0';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_HTTPHEADER      => ['Content-Type: application/json', 'User-Agent: Perfex-AccountPlanning/1.0'],
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 15,
            CURLOPT_CONNECTTIMEOUT  => 5,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_SSL_VERIFYPEER  => $sslVerify,
        ]);
        $result = curl_exec($ch);
        $errno = curl_errno($ch);
        if ($errno) {
            $errMsg = curl_error($ch);
            if (function_exists('log_activity')) {
                log_activity('Account Planning Webhook failed [URL: ' . substr($url, 0, 80) . '..., Error: ' . $errno . ' ' . $errMsg . ']');
            }
        }
        curl_close($ch);
    }
}