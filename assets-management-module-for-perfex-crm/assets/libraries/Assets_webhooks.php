<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Assets Webhooks Library
 * Handles webhook management and triggering for asset events
 */
class Assets_webhooks
{
    protected $CI;
    
    // Available webhook events
    public static $events = [
        'asset.created' => 'When a new asset is created',
        'asset.updated' => 'When an asset is updated',
        'asset.deleted' => 'When an asset is deleted',
        'asset.allocated' => 'When an asset is allocated to staff',
        'asset.revoked' => 'When an asset allocation is revoked',
        'asset.checked_out' => 'When an asset is checked out',
        'asset.checked_in' => 'When an asset is checked in',
        'asset.reserved' => 'When an asset is reserved',
        'asset.reservation_approved' => 'When a reservation is approved',
        'asset.reservation_rejected' => 'When a reservation is rejected',
        'asset.maintenance_scheduled' => 'When maintenance is scheduled',
        'asset.maintenance_completed' => 'When maintenance is completed',
        'asset.transferred' => 'When an asset is transferred',
        'asset.lost' => 'When an asset is reported lost',
        'asset.broken' => 'When an asset is reported broken',
        'asset.warranty' => 'When an asset is sent for warranty',
        'asset.liquidated' => 'When an asset is liquidated',
        'alert.warranty_expiring' => 'When warranty is about to expire',
        'alert.insurance_expiring' => 'When insurance is about to expire',
        'alert.maintenance_due' => 'When maintenance is due',
        'alert.checkout_overdue' => 'When a checkout is overdue',
        'alert.low_stock' => 'When asset stock is low',
    ];

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Get all webhooks
     */
    public function get_webhooks($id = null)
    {
        if ($id) {
            $this->CI->db->where('id', $id);
            return $this->CI->db->get(db_prefix().'asset_webhooks')->row();
        }
        return $this->CI->db->get(db_prefix().'asset_webhooks')->result_array();
    }

    /**
     * Get active webhooks for a specific event
     */
    public function get_webhooks_for_event($event)
    {
        $this->CI->db->where('active', 1);
        $webhooks = $this->CI->db->get(db_prefix().'asset_webhooks')->result_array();
        
        $matching = [];
        foreach ($webhooks as $webhook) {
            $events = json_decode($webhook['events'], true) ?: [];
            if (in_array($event, $events) || in_array('*', $events)) {
                $matching[] = $webhook;
            }
        }
        return $matching;
    }

    /**
     * Create a new webhook
     */
    public function create_webhook($data)
    {
        $insert = [
            'name' => $data['name'],
            'url' => $data['url'],
            'secret_key' => isset($data['secret_key']) ? $data['secret_key'] : $this->generate_secret(),
            'events' => is_array($data['events']) ? json_encode($data['events']) : $data['events'],
            'active' => isset($data['active']) ? $data['active'] : 1,
            'headers' => isset($data['headers']) ? json_encode($data['headers']) : null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->CI->db->insert(db_prefix().'asset_webhooks', $insert);
        return $this->CI->db->insert_id();
    }

    /**
     * Update a webhook
     */
    public function update_webhook($id, $data)
    {
        $update = [];
        
        if (isset($data['name'])) $update['name'] = $data['name'];
        if (isset($data['url'])) $update['url'] = $data['url'];
        if (isset($data['secret_key'])) $update['secret_key'] = $data['secret_key'];
        if (isset($data['events'])) $update['events'] = is_array($data['events']) ? json_encode($data['events']) : $data['events'];
        if (isset($data['active'])) $update['active'] = $data['active'];
        if (isset($data['headers'])) $update['headers'] = json_encode($data['headers']);
        
        $update['updated_at'] = date('Y-m-d H:i:s');

        $this->CI->db->where('id', $id);
        return $this->CI->db->update(db_prefix().'asset_webhooks', $update);
    }

    /**
     * Delete a webhook
     */
    public function delete_webhook($id)
    {
        // Delete logs first
        $this->CI->db->where('webhook_id', $id);
        $this->CI->db->delete(db_prefix().'asset_webhook_logs');
        
        // Delete webhook
        $this->CI->db->where('id', $id);
        return $this->CI->db->delete(db_prefix().'asset_webhooks');
    }

    /**
     * Trigger webhooks for an event
     */
    public function trigger($event, $payload = [])
    {
        $webhooks = $this->get_webhooks_for_event($event);
        
        if (empty($webhooks)) {
            return;
        }

        $payload['event'] = $event;
        $payload['timestamp'] = date('c');
        $payload['source'] = 'perfex_assets_module';

        foreach ($webhooks as $webhook) {
            $this->send_webhook($webhook, $event, $payload);
        }
    }

    /**
     * Send webhook request
     */
    protected function send_webhook($webhook, $event, $payload)
    {
        $start_time = microtime(true);
        
        $json_payload = json_encode($payload);
        $signature = hash_hmac('sha256', $json_payload, $webhook['secret_key']);
        
        $headers = [
            'Content-Type: application/json',
            'X-Asset-Webhook-Event: ' . $event,
            'X-Asset-Webhook-Signature: ' . $signature,
            'X-Asset-Webhook-Timestamp: ' . time(),
            'User-Agent: PerfexCRM-Assets-Webhook/1.2.0'
        ];

        // Add custom headers if any
        if (!empty($webhook['headers'])) {
            $custom_headers = json_decode($webhook['headers'], true);
            if (is_array($custom_headers)) {
                foreach ($custom_headers as $key => $value) {
                    $headers[] = $key . ': ' . $value;
                }
            }
        }

        $ch = curl_init($webhook['url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        $execution_time = microtime(true) - $start_time;
        
        // Log the webhook call
        $this->log_webhook($webhook['id'], $event, $json_payload, $http_code, $response ?: $error, $execution_time);
        
        // Update webhook status
        $update = [
            'last_triggered' => date('Y-m-d H:i:s'),
            'last_response_code' => $http_code
        ];
        
        if ($http_code < 200 || $http_code >= 300) {
            $update['failure_count'] = $webhook['failure_count'] + 1;
            
            // Disable webhook after 10 consecutive failures
            if ($update['failure_count'] >= 10) {
                $update['active'] = 0;
            }
        } else {
            $update['failure_count'] = 0;
        }
        
        $this->CI->db->where('id', $webhook['id']);
        $this->CI->db->update(db_prefix().'asset_webhooks', $update);
        
        return $http_code >= 200 && $http_code < 300;
    }

    /**
     * Log webhook execution
     */
    protected function log_webhook($webhook_id, $event, $payload, $response_code, $response_body, $execution_time)
    {
        $this->CI->db->insert(db_prefix().'asset_webhook_logs', [
            'webhook_id' => $webhook_id,
            'event' => $event,
            'payload' => $payload,
            'response_code' => $response_code,
            'response_body' => substr($response_body, 0, 65535),
            'created_at' => date('Y-m-d H:i:s'),
            'execution_time' => $execution_time
        ]);
    }

    /**
     * Get webhook logs
     */
    public function get_logs($webhook_id = null, $limit = 100)
    {
        if ($webhook_id) {
            $this->CI->db->where('webhook_id', $webhook_id);
        }
        $this->CI->db->order_by('created_at', 'DESC');
        $this->CI->db->limit($limit);
        return $this->CI->db->get(db_prefix().'asset_webhook_logs')->result_array();
    }

    /**
     * Clear old logs (older than 30 days)
     */
    public function clear_old_logs($days = 30)
    {
        $this->CI->db->where('created_at <', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        return $this->CI->db->delete(db_prefix().'asset_webhook_logs');
    }

    /**
     * Generate a random secret key
     */
    protected function generate_secret()
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Test a webhook
     */
    public function test_webhook($id)
    {
        $webhook = $this->get_webhooks($id);
        if (!$webhook) {
            return false;
        }

        $test_payload = [
            'test' => true,
            'message' => 'This is a test webhook from Perfex CRM Assets Module',
            'webhook_id' => $id,
            'webhook_name' => $webhook->name
        ];

        return $this->send_webhook((array)$webhook, 'test', $test_payload);
    }

    /**
     * Get available events list
     */
    public function get_available_events()
    {
        return self::$events;
    }

    /**
     * Retry failed webhook
     */
    public function retry_webhook_log($log_id)
    {
        $this->CI->db->where('id', $log_id);
        $log = $this->CI->db->get(db_prefix().'asset_webhook_logs')->row();
        
        if (!$log) {
            return false;
        }

        $webhook = $this->get_webhooks($log->webhook_id);
        if (!$webhook) {
            return false;
        }

        $payload = json_decode($log->payload, true);
        return $this->send_webhook((array)$webhook, $log->event, $payload);
    }
}
