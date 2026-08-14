<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Assets Notifications Library
 * Handles email alerts and in-app notifications for asset events
 */
class Assets_notifications
{
    protected $CI;
    
    // Notification types
    const TYPE_WARRANTY_EXPIRY = 'warranty_expiry';
    const TYPE_INSURANCE_EXPIRY = 'insurance_expiry';
    const TYPE_MAINTENANCE_DUE = 'maintenance_due';
    const TYPE_CHECKOUT_OVERDUE = 'checkout_overdue';
    const TYPE_RESERVATION_REMINDER = 'reservation_reminder';
    const TYPE_LOW_STOCK = 'low_stock';
    const TYPE_ASSET_END_OF_LIFE = 'asset_end_of_life';
    const TYPE_ASSET_ALLOCATED = 'asset_allocated';
    const TYPE_ASSET_REVOKED = 'asset_revoked';
    const TYPE_RESERVATION_APPROVED = 'reservation_approved';
    const TYPE_RESERVATION_REJECTED = 'reservation_rejected';
    const TYPE_MAINTENANCE_COMPLETED = 'maintenance_completed';

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('emails_model');
    }

    /**
     * Get notification settings
     */
    public function get_settings($type = null)
    {
        if ($type) {
            $this->CI->db->where('notification_type', $type);
            return $this->CI->db->get(db_prefix().'asset_notification_settings')->row();
        }
        return $this->CI->db->get(db_prefix().'asset_notification_settings')->result_array();
    }

    /**
     * Update notification settings
     */
    public function update_settings($type, $data)
    {
        $this->CI->db->where('notification_type', $type);
        $exists = $this->CI->db->get(db_prefix().'asset_notification_settings')->row();

        $update = [
            'enabled' => isset($data['enabled']) ? $data['enabled'] : 1,
            'email_enabled' => isset($data['email_enabled']) ? $data['email_enabled'] : 1,
            'days_before' => isset($data['days_before']) ? $data['days_before'] : 7,
            'recipients' => isset($data['recipients']) ? (is_array($data['recipients']) ? json_encode($data['recipients']) : $data['recipients']) : null,
        ];

        if ($exists) {
            $this->CI->db->where('notification_type', $type);
            return $this->CI->db->update(db_prefix().'asset_notification_settings', $update);
        } else {
            $update['notification_type'] = $type;
            $this->CI->db->insert(db_prefix().'asset_notification_settings', $update);
            return $this->CI->db->insert_id();
        }
    }

    /**
     * Create in-app notification
     */
    public function create($data)
    {
        $insert = [
            'type' => $data['type'],
            'asset_id' => isset($data['asset_id']) ? $data['asset_id'] : null,
            'staff_id' => $data['staff_id'],
            'title' => $data['title'],
            'message' => isset($data['message']) ? $data['message'] : null,
            'link' => isset($data['link']) ? $data['link'] : null,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->CI->db->insert(db_prefix().'asset_notifications', $insert);
        return $this->CI->db->insert_id();
    }

    /**
     * Get notifications for a staff member
     */
    public function get_notifications($staff_id, $unread_only = false, $limit = 50)
    {
        $this->CI->db->where('staff_id', $staff_id);
        if ($unread_only) {
            $this->CI->db->where('is_read', 0);
        }
        $this->CI->db->order_by('created_at', 'DESC');
        $this->CI->db->limit($limit);
        return $this->CI->db->get(db_prefix().'asset_notifications')->result_array();
    }

    /**
     * Get unread notification count
     */
    public function get_unread_count($staff_id)
    {
        $this->CI->db->where('staff_id', $staff_id);
        $this->CI->db->where('is_read', 0);
        return $this->CI->db->count_all_results(db_prefix().'asset_notifications');
    }

    /**
     * Mark notification as read
     */
    public function mark_as_read($id, $staff_id = null)
    {
        $this->CI->db->where('id', $id);
        if ($staff_id) {
            $this->CI->db->where('staff_id', $staff_id);
        }
        return $this->CI->db->update(db_prefix().'asset_notifications', [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark all notifications as read for a staff member
     */
    public function mark_all_as_read($staff_id)
    {
        $this->CI->db->where('staff_id', $staff_id);
        $this->CI->db->where('is_read', 0);
        return $this->CI->db->update(db_prefix().'asset_notifications', [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $this->CI->db->where('id', $id);
        return $this->CI->db->delete(db_prefix().'asset_notifications');
    }

    /**
     * Delete old notifications (older than 90 days)
     */
    public function delete_old_notifications($days = 90)
    {
        $this->CI->db->where('created_at <', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        return $this->CI->db->delete(db_prefix().'asset_notifications');
    }

    /**
     * Send email notification
     */
    public function send_email($to, $subject, $message, $data = [])
    {
        $this->CI->load->library('email');
        
        // Use Perfex CRM email template system if available
        $template = isset($data['template']) ? $data['template'] : null;
        
        if ($template && function_exists('send_mail_template')) {
            return send_mail_template($template, $to, $data['merge_fields'] ?? []);
        }
        
        // Fallback to basic email
        $this->CI->email->clear();
        $this->CI->email->from(get_option('smtp_email'), get_option('companyname'));
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        
        return $this->CI->email->send();
    }

    /**
     * Notify about warranty expiry
     */
    public function notify_warranty_expiry($asset, $days_until_expiry)
    {
        $settings = $this->get_settings(self::TYPE_WARRANTY_EXPIRY);
        if (!$settings || !$settings->enabled) {
            return false;
        }

        $recipients = $this->get_notification_recipients($settings);
        
        $title = _l('warranty_expiring_notification_title');
        $message = sprintf(_l('warranty_expiring_notification_message'), $asset->assets_name, $days_until_expiry);
        $link = admin_url('assets/manage_assets#' . $asset->id);

        foreach ($recipients as $staff_id) {
            // Create in-app notification
            $this->create([
                'type' => self::TYPE_WARRANTY_EXPIRY,
                'asset_id' => $asset->id,
                'staff_id' => $staff_id,
                'title' => $title,
                'message' => $message,
                'link' => $link
            ]);

            // Send email if enabled
            if ($settings->email_enabled) {
                $staff = $this->CI->staff_model->get($staff_id);
                if ($staff && !empty($staff->email)) {
                    $this->send_email($staff->email, $title, $this->build_email_body($title, $message, $link));
                }
            }
        }

        return true;
    }

    /**
     * Notify about maintenance due
     */
    public function notify_maintenance_due($asset, $maintenance)
    {
        $settings = $this->get_settings(self::TYPE_MAINTENANCE_DUE);
        if (!$settings || !$settings->enabled) {
            return false;
        }

        $recipients = $this->get_notification_recipients($settings);
        
        $title = _l('maintenance_due_notification_title');
        $message = sprintf(_l('maintenance_due_notification_message'), $asset->assets_name, $maintenance->scheduled_date);
        $link = admin_url('assets/maintenance/' . $maintenance->id);

        foreach ($recipients as $staff_id) {
            $this->create([
                'type' => self::TYPE_MAINTENANCE_DUE,
                'asset_id' => $asset->id,
                'staff_id' => $staff_id,
                'title' => $title,
                'message' => $message,
                'link' => $link
            ]);

            if ($settings->email_enabled) {
                $staff = $this->CI->staff_model->get($staff_id);
                if ($staff && !empty($staff->email)) {
                    $this->send_email($staff->email, $title, $this->build_email_body($title, $message, $link));
                }
            }
        }

        return true;
    }

    /**
     * Notify about checkout overdue
     */
    public function notify_checkout_overdue($checkout, $asset)
    {
        $settings = $this->get_settings(self::TYPE_CHECKOUT_OVERDUE);
        if (!$settings || !$settings->enabled) {
            return false;
        }

        $title = _l('checkout_overdue_notification_title');
        $message = sprintf(_l('checkout_overdue_notification_message'), $asset->assets_name);
        $link = admin_url('assets/checkouts');

        // Notify the person who has the asset
        $this->create([
            'type' => self::TYPE_CHECKOUT_OVERDUE,
            'asset_id' => $checkout->asset_id,
            'staff_id' => $checkout->checked_out_to,
            'title' => $title,
            'message' => $message,
            'link' => $link
        ]);

        if ($settings->email_enabled) {
            $staff = $this->CI->staff_model->get($checkout->checked_out_to);
            if ($staff && !empty($staff->email)) {
                $this->send_email($staff->email, $title, $this->build_email_body($title, $message, $link));
            }
        }

        // Also notify admins/managers
        $recipients = $this->get_notification_recipients($settings);
        foreach ($recipients as $staff_id) {
            if ($staff_id != $checkout->checked_out_to) {
                $this->create([
                    'type' => self::TYPE_CHECKOUT_OVERDUE,
                    'asset_id' => $checkout->asset_id,
                    'staff_id' => $staff_id,
                    'title' => $title,
                    'message' => $message,
                    'link' => $link
                ]);
            }
        }

        return true;
    }

    /**
     * Notify about asset allocation
     */
    public function notify_asset_allocated($asset, $staff_id, $quantity)
    {
        $title = _l('asset_allocated_notification_title');
        $message = sprintf(_l('asset_allocated_notification_message'), $quantity, $asset->assets_name);
        $link = admin_url('assets/manage_assets#' . $asset->id);

        $this->create([
            'type' => self::TYPE_ASSET_ALLOCATED,
            'asset_id' => $asset->id,
            'staff_id' => $staff_id,
            'title' => $title,
            'message' => $message,
            'link' => $link
        ]);

        // Send email
        $staff = $this->CI->staff_model->get($staff_id);
        if ($staff && !empty($staff->email)) {
            $this->send_email($staff->email, $title, $this->build_email_body($title, $message, $link));
        }

        return true;
    }

    /**
     * Notify about reservation status change
     */
    public function notify_reservation_status($reservation, $asset, $status)
    {
        $type = $status == 'approved' ? self::TYPE_RESERVATION_APPROVED : self::TYPE_RESERVATION_REJECTED;
        $title = $status == 'approved' ? _l('reservation_approved_notification_title') : _l('reservation_rejected_notification_title');
        $message = sprintf(_l('reservation_status_notification_message'), $asset->assets_name, $status);
        $link = admin_url('assets/reservations');

        $this->create([
            'type' => $type,
            'asset_id' => $reservation->asset_id,
            'staff_id' => $reservation->reserved_by,
            'title' => $title,
            'message' => $message,
            'link' => $link
        ]);

        $staff = $this->CI->staff_model->get($reservation->reserved_by);
        if ($staff && !empty($staff->email)) {
            $this->send_email($staff->email, $title, $this->build_email_body($title, $message, $link));
        }

        return true;
    }

    /**
     * Notify about low stock
     */
    public function notify_low_stock($asset, $current_stock, $threshold = 5)
    {
        $settings = $this->get_settings(self::TYPE_LOW_STOCK);
        if (!$settings || !$settings->enabled) {
            return false;
        }

        $recipients = $this->get_notification_recipients($settings);
        
        $title = _l('low_stock_notification_title');
        $message = sprintf(_l('low_stock_notification_message'), $asset->assets_name, $current_stock);
        $link = admin_url('assets/manage_assets#' . $asset->id);

        foreach ($recipients as $staff_id) {
            $this->create([
                'type' => self::TYPE_LOW_STOCK,
                'asset_id' => $asset->id,
                'staff_id' => $staff_id,
                'title' => $title,
                'message' => $message,
                'link' => $link
            ]);

            if ($settings->email_enabled) {
                $staff = $this->CI->staff_model->get($staff_id);
                if ($staff && !empty($staff->email)) {
                    $this->send_email($staff->email, $title, $this->build_email_body($title, $message, $link));
                }
            }
        }

        return true;
    }

    /**
     * Get notification recipients based on settings
     */
    protected function get_notification_recipients($settings)
    {
        $recipients = [];
        
        if (!empty($settings->recipients)) {
            $recipients = json_decode($settings->recipients, true);
            if (!is_array($recipients)) {
                $recipients = [];
            }
        }

        // If no recipients specified, get all admins
        if (empty($recipients)) {
            $this->CI->db->select('staffid');
            $this->CI->db->where('admin', 1);
            $this->CI->db->where('active', 1);
            $admins = $this->CI->db->get(db_prefix().'staff')->result_array();
            foreach ($admins as $admin) {
                $recipients[] = $admin['staffid'];
            }
        }

        return $recipients;
    }

    /**
     * Build HTML email body
     */
    protected function build_email_body($title, $message, $link = null)
    {
        $company_name = get_option('companyname');
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4e73df; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8f9fc; }
                .button { display: inline-block; padding: 10px 20px; background: #4e73df; color: white; text-decoration: none; border-radius: 4px; margin-top: 15px; }
                .footer { padding: 15px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>' . htmlspecialchars($title) . '</h2>
                </div>
                <div class="content">
                    <p>' . nl2br(htmlspecialchars($message)) . '</p>';
        
        if ($link) {
            $html .= '<p><a href="' . htmlspecialchars($link) . '" class="button">' . _l('view_details') . '</a></p>';
        }
        
        $html .= '
                </div>
                <div class="footer">
                    <p>' . htmlspecialchars($company_name) . ' - Asset Management System</p>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
