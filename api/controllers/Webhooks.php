<?php
defined('BASEPATH') or exit('No direct script access allowed');

require __DIR__ . '/REST_Controller.php';
require_once __DIR__ . '/../libraries/Api_Webhook_Service.php';

/**
 * @api {get} api/webhooks List Webhooks
 * @apiVersion 3.0.0
 * @apiName GetWebhooks
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiDescription List all configured webhooks. Secrets are never returned (has_secret flag only). Supports page/per_page, sort, fields and date filters.
 */

/**
 * @api {post} api/webhooks Create a Webhook
 * @apiVersion 3.0.0
 * @apiName PostWebhook
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiParam {String} name Webhook name.
 * @apiParam {String} url Target URL (SSRF-checked: https/http public hosts only).
 * @apiParam {String} events Comma list of event names, or "*" for all. See GET api/webhooks/events.
 * @apiParam {String} [secret] HMAC secret. Deliveries then carry X-Perfex-Signature: t=&lt;unix&gt;,v1=&lt;hmac_sha256&gt;.
 * @apiParam {Number} [timeout=30] Request timeout in seconds (1-120).
 * @apiParam {Number} [retry_count=3] Retries in queued mode (0-10).
 * @apiParam {String} [headers] JSON object of custom headers.
 */

/**
 * @api {get} api/webhooks/:id Request Webhook Information
 * @apiVersion 3.0.0
 * @apiName GetWebhook
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 */

/**
 * @api {put} api/webhooks/:id Update a Webhook
 * @apiVersion 3.0.0
 * @apiName PutWebhook
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiDescription Partial update - only the provided fields change; same validation as create.
 */

/**
 * @api {delete} api/webhooks/:id Delete a Webhook
 * @apiVersion 3.0.0
 * @apiName DeleteWebhook
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiDescription Deletes the webhook together with its delivery logs and queued jobs.
 */

/**
 * @api {post} api/webhooks/:id/toggle Enable/Disable a Webhook
 * @apiVersion 3.0.0
 * @apiName ToggleWebhook
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 */

/**
 * @api {get} api/webhooks/events Webhook Event Catalog
 * @apiVersion 3.0.0
 * @apiName GetWebhookEvents
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiDescription The authoritative catalog: 124 events across 22 resource groups (invoice_created, lead_status_changed, ticket_reply_created, kb_article_created...).
 */

/**
 * @api {get} api/webhooks/:id/logs Webhook Delivery Logs
 * @apiVersion 3.0.0
 * @apiName GetWebhookLogs
 * @apiGroup Webhooks
 * @apiHeader {String} authtoken Authentication token, generated from admin area
 * @apiDescription Latest 500 delivery attempts with status, response code and error details. Paginated via page/per_page.
 */
class Webhooks extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_webhooks_permission();
    }

    /**
     * Tokens with granular permissions enabled need the webhooks feature;
     * full-access tokens (permission_enable = 0) pass.
     */
    private function require_webhooks_permission()
    {
        $token = isset($this->rest->key) ? $this->rest->key : '';
        if ($token === '') {
            return; // token-less access is already rejected upstream
        }

        $row = $this->db
            ->where('token', $token)
            ->get(db_prefix() . 'user_api')
            ->row_array();

        if (!$row || (int)$row['permission_enable'] !== 1) {
            return;
        }

        $allowed = $this->db
            ->where('api_id', $row['id'])
            ->where('feature', 'webhooks')
            ->get(db_prefix() . 'user_api_permissions')
            ->num_rows();

        if ($allowed === 0) {
            $this->response([
                'status'  => FALSE,
                'message' => 'This token has no webhooks permission',
            ], REST_Controller::HTTP_FORBIDDEN);
        }
    }

    /**
     * Serialize a webhook row for API output (secret never leaves the server).
     */
    private function present(array $row)
    {
        $row['events']     = array_values(array_filter(array_map('trim', explode(',', (string)$row['events']))));
        $row['headers']    = !empty($row['headers']) ? json_decode($row['headers'], true) : null;
        $row['has_secret'] = !empty($row['secret']);
        unset($row['secret']);
        return $row;
    }

    /**
     * Validate + normalize a create/update payload. Responds 422 on problems.
     *
     * @param bool $creating
     * @return array Fields ready for insert/update
     */
    private function validated_payload($creating)
    {
        $post   = $this->input->post();
        $errors = [];
        $data   = [];

        if ($creating || array_key_exists('name', $post)) {
            $name = isset($post['name']) ? trim((string)$post['name']) : '';
            if ($name === '') {
                $errors['name'] = 'The name field is required.';
            } else {
                $data['name'] = $name;
            }
        }

        if ($creating || array_key_exists('url', $post)) {
            $url = isset($post['url']) ? trim((string)$post['url']) : '';
            $ssrfError = null;
            $resolved  = null;
            $strict = function_exists('get_option') ? (get_option('api_webhook_ssrf_strict') == '1') : false;
            if ($url === '') {
                $errors['url'] = 'The url field is required.';
            } elseif (!Api_Webhook_Service::isUrlSafe($url, $strict, $resolved, $ssrfError)) {
                $errors['url'] = 'URL rejected: ' . $ssrfError;
            } else {
                $data['url'] = $url;
            }
        }

        if ($creating || array_key_exists('events', $post)) {
            $events = isset($post['events']) ? $post['events'] : '';
            if (is_string($events)) {
                $events = array_filter(array_map('trim', explode(',', $events)));
            }
            if (!is_array($events) || empty($events)) {
                $errors['events'] = 'Provide at least one event (or "*").';
            } else {
                $valid   = Api_Webhook_Service::eventNames();
                $unknown = array_values(array_diff($events, $valid));
                if (!empty($unknown)) {
                    $errors['events'] = 'Unknown events: ' . implode(', ', $unknown)
                        . '. See GET api/webhooks/events for the catalog.';
                } else {
                    $data['events'] = implode(',', $events);
                }
            }
        }

        if (array_key_exists('secret', $post)) {
            $data['secret'] = (string)$post['secret'];
        }
        if (array_key_exists('active', $post)) {
            $data['active'] = (int)((string)$post['active'] === '1' || $post['active'] === 1 || $post['active'] === true);
        }
        if (array_key_exists('timeout', $post)) {
            $data['timeout'] = max(1, min(120, (int)$post['timeout']));
        }
        if (array_key_exists('retry_count', $post)) {
            $data['retry_count'] = max(0, min(10, (int)$post['retry_count']));
        }
        if (array_key_exists('headers', $post)) {
            $headers = $post['headers'];
            if (is_string($headers) && $headers !== '') {
                $decoded = json_decode($headers, true);
                if (!is_array($decoded)) {
                    $errors['headers'] = 'Headers must be a JSON object.';
                } else {
                    $data['headers'] = json_encode($decoded);
                }
            } elseif (is_array($headers)) {
                $data['headers'] = json_encode($headers);
            } else {
                $data['headers'] = null;
            }
        }

        if (!empty($errors)) {
            $this->api_validation_error($errors, 'Webhook validation failed');
        }

        return $data;
    }

    // ------------------------------------------------------------------
    // CRUD
    // ------------------------------------------------------------------

    public function data_get($id = '')
    {
        if (!empty($id)) {
            $row = $this->db->where('id', (int)$id)->get(db_prefix() . 'api_webhooks')->row_array();
            if (!$row) {
                $this->response(['status' => FALSE, 'message' => 'Webhook not found'], REST_Controller::HTTP_NOT_FOUND);
            }
            $this->response($this->present($row), REST_Controller::HTTP_OK);
        }

        $rows = $this->db->order_by('id', 'ASC')->get(db_prefix() . 'api_webhooks')->result_array();
        $rows = array_map([$this, 'present'], $rows);
        $this->api_list_response($rows);
    }

    public function data_post()
    {
        $data = $this->validated_payload(true);

        $data['active']       = isset($data['active']) ? $data['active'] : 1;
        $data['timeout']      = isset($data['timeout']) ? $data['timeout'] : 30;
        $data['retry_count']  = isset($data['retry_count']) ? $data['retry_count'] : 3;
        $data['date_created'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'api_webhooks', $data);
        $id = $this->db->insert_id();

        if (!$id) {
            $this->response(['status' => FALSE, 'message' => 'Webhook could not be created'], REST_Controller::HTTP_BAD_REQUEST);
        }

        $this->fire_system_event('webhook_created', $id);

        $row = $this->db->where('id', $id)->get(db_prefix() . 'api_webhooks')->row_array();
        $this->response([
            'status'    => TRUE,
            'message'   => 'Webhook created successfully',
            'record_id' => $id,
            'data'      => $this->present($row),
        ], REST_Controller::HTTP_OK);
    }

    public function data_put($id = '')
    {
        $existing = $this->find_or_404($id);
        $data     = $this->validated_payload(false);

        if (empty($data)) {
            $this->api_validation_error(['_general' => 'No updatable fields provided'], 'Nothing to update');
        }

        $this->db->where('id', $existing['id'])->update(db_prefix() . 'api_webhooks', $data);
        $this->fire_system_event('webhook_updated', $existing['id']);

        $row = $this->db->where('id', $existing['id'])->get(db_prefix() . 'api_webhooks')->row_array();
        $this->response([
            'status'  => TRUE,
            'message' => 'Webhook updated successfully',
            'data'    => $this->present($row),
        ], REST_Controller::HTTP_OK);
    }

    public function data_delete($id = '')
    {
        $existing = $this->find_or_404($id);

        $this->db->where('webhook_id', $existing['id'])->delete(db_prefix() . 'api_webhook_logs');
        if ($this->db->table_exists(db_prefix() . 'api_webhook_queue')) {
            $this->db->where('webhook_id', $existing['id'])->delete(db_prefix() . 'api_webhook_queue');
        }
        $this->db->where('id', $existing['id'])->delete(db_prefix() . 'api_webhooks');

        $this->fire_system_event('webhook_deleted', $existing['id']);

        $this->response(['status' => TRUE, 'message' => 'Webhook deleted successfully'], REST_Controller::HTTP_OK);
    }

    // ------------------------------------------------------------------
    // Extras
    // ------------------------------------------------------------------

    public function toggle_post($id = '')
    {
        $existing  = $this->find_or_404($id);
        $newActive = ((int)$existing['active'] === 1) ? 0 : 1;

        $this->db->where('id', $existing['id'])->update(db_prefix() . 'api_webhooks', ['active' => $newActive]);

        $this->response([
            'status'  => TRUE,
            'message' => $newActive ? 'Webhook enabled' : 'Webhook disabled',
            'active'  => $newActive,
        ], REST_Controller::HTTP_OK);
    }

    public function events_get()
    {
        $catalog = Api_Webhook_Service::eventCatalog();
        $total   = 0;
        foreach ($catalog as $events) {
            $total += count($events);
        }

        $this->response([
            'total_events' => $total,
            'wildcard'     => '*',
            'groups'       => $catalog,
        ], REST_Controller::HTTP_OK);
    }

    public function logs_get($id = '')
    {
        $existing = $this->find_or_404($id);

        $rows = $this->db
            ->where('webhook_id', $existing['id'])
            ->order_by('id', 'DESC')
            ->limit(500)
            ->get(db_prefix() . 'api_webhook_logs')
            ->result_array();

        $this->api_list_response($rows, ['date_field' => 'triggered_at']);
    }

    // ------------------------------------------------------------------
    // Internals
    // ------------------------------------------------------------------

    private function find_or_404($id)
    {
        if (empty($id) || !is_numeric($id)) {
            $this->response(['status' => FALSE, 'message' => 'Invalid webhook ID'], REST_Controller::HTTP_NOT_FOUND);
        }
        $row = $this->db->where('id', (int)$id)->get(db_prefix() . 'api_webhooks')->row_array();
        if (!$row) {
            $this->response(['status' => FALSE, 'message' => 'Webhook not found'], REST_Controller::HTTP_NOT_FOUND);
        }
        return $row;
    }

    private function fire_system_event($event, $webhookId)
    {
        try {
            $service = new Api_Webhook_Service();
            $service->triggerWebhooks($event, ['webhook_id' => $webhookId]);
        } catch (Exception $e) {
            // System events must never break the management API
        }
    }
}
