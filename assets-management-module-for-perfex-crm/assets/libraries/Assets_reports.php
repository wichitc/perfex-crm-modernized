<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Assets Reports Library
 * Handles report generation (PDF, Excel, CSV exports)
 */
class Assets_reports
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Generate assets list report
     */
    public function generate_assets_report($filters = [], $format = 'pdf')
    {
        $assets = $this->get_filtered_assets($filters);
        
        switch ($format) {
            case 'excel':
                return $this->export_to_excel($assets, 'assets_report');
            case 'csv':
                return $this->export_to_csv($assets, 'assets_report');
            case 'pdf':
            default:
                return $this->export_to_pdf($assets, 'assets_report', _l('assets_report'));
        }
    }

    /**
     * Generate depreciation report
     */
    public function generate_depreciation_report($filters = [], $format = 'pdf')
    {
        $assets = $this->get_filtered_assets($filters);
        $report_data = [];
        
        foreach ($assets as $asset) {
            $depreciation = $this->calculate_depreciation($asset);
            $report_data[] = array_merge((array)$asset, $depreciation);
        }
        
        switch ($format) {
            case 'excel':
                return $this->export_depreciation_excel($report_data);
            case 'csv':
                return $this->export_depreciation_csv($report_data);
            case 'pdf':
            default:
                return $this->export_depreciation_pdf($report_data);
        }
    }

    /**
     * Generate maintenance report
     */
    public function generate_maintenance_report($filters = [], $format = 'pdf')
    {
        $this->CI->db->select('m.*, a.assets_name, a.assets_code');
        $this->CI->db->from(db_prefix().'asset_maintenance m');
        $this->CI->db->join(db_prefix().'assets a', 'a.id = m.asset_id', 'left');
        
        if (!empty($filters['status'])) {
            $this->CI->db->where('m.status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $this->CI->db->where('m.scheduled_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->CI->db->where('m.scheduled_date <=', $filters['date_to']);
        }
        if (!empty($filters['asset_id'])) {
            $this->CI->db->where('m.asset_id', $filters['asset_id']);
        }
        
        $this->CI->db->order_by('m.scheduled_date', 'DESC');
        $maintenance = $this->CI->db->get()->result_array();
        
        switch ($format) {
            case 'excel':
                return $this->export_maintenance_excel($maintenance);
            case 'csv':
                return $this->export_maintenance_csv($maintenance);
            case 'pdf':
            default:
                return $this->export_maintenance_pdf($maintenance);
        }
    }

    /**
     * Generate checkout history report
     */
    public function generate_checkout_report($filters = [], $format = 'pdf')
    {
        $this->CI->db->select('c.*, a.assets_name, a.assets_code');
        $this->CI->db->from(db_prefix().'asset_checkouts c');
        $this->CI->db->join(db_prefix().'assets a', 'a.id = c.asset_id', 'left');

        if (!empty($filters['status'])) {
            $this->CI->db->where('c.status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $this->CI->db->where('c.checkout_date >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->CI->db->where('c.checkout_date <=', $filters['date_to']);
        }

        $this->CI->db->order_by('c.checkout_date', 'DESC');
        $checkouts = $this->CI->db->get()->result_array();

        // Recipients may be staff, customers or contacts (overlapping id spaces),
        // so resolve the display name per row by type. Exports read firstname /
        // lastname, so fill firstname with the full name and clear lastname.
        foreach ($checkouts as &$co) {
            $co_type = isset($co['checked_out_to_type']) ? $co['checked_out_to_type'] : 'staff';
            $co['firstname'] = function_exists('get_asset_recipient_name')
                ? get_asset_recipient_name($co['checked_out_to'], $co_type)
                : '';
            $co['lastname'] = '';
        }
        unset($co);
        
        switch ($format) {
            case 'excel':
                return $this->export_checkout_excel($checkouts);
            case 'csv':
                return $this->export_checkout_csv($checkouts);
            case 'pdf':
            default:
                return $this->export_checkout_pdf($checkouts);
        }
    }

    /**
     * Generate inventory audit report
     */
    public function generate_audit_report($asset_id = null, $format = 'pdf')
    {
        $this->CI->db->select('al.*, a.assets_name, a.assets_code, s.firstname, s.lastname');
        $this->CI->db->from(db_prefix().'asset_audit_log al');
        $this->CI->db->join(db_prefix().'assets a', 'a.id = al.asset_id', 'left');
        $this->CI->db->join(db_prefix().'staff s', 's.staffid = al.performed_by', 'left');
        
        if ($asset_id) {
            $this->CI->db->where('al.asset_id', $asset_id);
        }
        
        $this->CI->db->order_by('al.created_at', 'DESC');
        $logs = $this->CI->db->get()->result_array();
        
        switch ($format) {
            case 'excel':
                return $this->export_audit_excel($logs);
            case 'csv':
                return $this->export_audit_csv($logs);
            case 'pdf':
            default:
                return $this->export_audit_pdf($logs);
        }
    }

    /**
     * Generate asset utilization report
     */
    public function generate_utilization_report($filters = [], $format = 'pdf')
    {
        $assets = $this->get_filtered_assets($filters);
        $report_data = [];
        
        foreach ($assets as $asset) {
            $utilization = $this->calculate_utilization($asset);
            $report_data[] = array_merge((array)$asset, $utilization);
        }
        
        switch ($format) {
            case 'excel':
                return $this->export_utilization_excel($report_data);
            case 'csv':
                return $this->export_utilization_csv($report_data);
            case 'pdf':
            default:
                return $this->export_utilization_pdf($report_data);
        }
    }

    /**
     * Get filtered assets
     */
    protected function get_filtered_assets($filters)
    {
        $this->CI->db->select('a.*, g.group_name, u.unit_name, l.location, d.name as department_name');
        $this->CI->db->from(db_prefix().'assets a');
        $this->CI->db->join(db_prefix().'assets_group g', 'g.group_id = a.asset_group', 'left');
        $this->CI->db->join(db_prefix().'asset_unit u', 'u.unit_id = a.unit', 'left');
        $this->CI->db->join(db_prefix().'asset_location l', 'l.location_id = a.asset_location', 'left');
        $this->CI->db->join(db_prefix().'departments d', 'd.departmentid = a.department', 'left');
        
        if (!empty($filters['group'])) {
            $this->CI->db->where('a.asset_group', $filters['group']);
        }
        if (!empty($filters['status'])) {
            $this->CI->db->where('a.status', $filters['status']);
        }
        if (!empty($filters['location'])) {
            $this->CI->db->where('a.asset_location', $filters['location']);
        }
        if (!empty($filters['department'])) {
            $this->CI->db->where('a.department', $filters['department']);
        }
        
        return $this->CI->db->get()->result();
    }

    /**
     * Calculate depreciation for an asset
     */
    protected function calculate_depreciation($asset)
    {
        $purchase_date = new DateTime($asset->date_buy);
        $now = new DateTime();
        $months_used = $purchase_date->diff($now)->m + ($purchase_date->diff($now)->y * 12);
        
        $total_value = $asset->unit_price * $asset->amount;
        $monthly_depreciation = $asset->depreciation > 0 ? $total_value / $asset->depreciation : 0;
        $depreciation_value = min($monthly_depreciation * $months_used, $total_value);
        $residual_value = max($total_value - $depreciation_value, 0);
        
        return [
            'months_used' => $months_used,
            'total_value' => $total_value,
            'monthly_depreciation' => $monthly_depreciation,
            'depreciation_value' => $depreciation_value,
            'residual_value' => $residual_value,
            'depreciation_percentage' => $total_value > 0 ? ($depreciation_value / $total_value) * 100 : 0
        ];
    }

    /**
     * Calculate utilization for an asset
     */
    protected function calculate_utilization($asset)
    {
        // Get checkout history
        $this->CI->db->where('asset_id', $asset->id);
        $this->CI->db->where('status', 'returned');
        $checkouts = $this->CI->db->get(db_prefix().'asset_checkouts')->result();
        
        $total_days_used = 0;
        foreach ($checkouts as $checkout) {
            $checkout_date = new DateTime($checkout->checkout_date);
            $return_date = new DateTime($checkout->actual_return_date);
            $total_days_used += $checkout_date->diff($return_date)->days;
        }
        
        // Calculate from purchase date
        $purchase_date = new DateTime($asset->date_buy);
        $now = new DateTime();
        $total_days_owned = $purchase_date->diff($now)->days;
        
        $utilization_rate = $total_days_owned > 0 ? ($total_days_used / $total_days_owned) * 100 : 0;
        $allocation_rate = $asset->amount > 0 ? ($asset->total_allocation / $asset->amount) * 100 : 0;
        
        return [
            'total_days_owned' => $total_days_owned,
            'total_days_used' => $total_days_used,
            'utilization_rate' => round($utilization_rate, 2),
            'allocation_rate' => round($allocation_rate, 2),
            'checkout_count' => count($checkouts)
        ];
    }

    /**
     * Export to PDF - generates printable HTML that browser can print to PDF
     */
    protected function export_to_pdf($data, $filename, $title)
    {
        $html = $this->generate_pdf_html($data, $title);
        
        // Return HTML that can be printed to PDF via browser's print dialog
        return [
            'content' => $html,
            'filename' => $filename . '_' . date('Y-m-d') . '.html',
            'mime' => 'text/html'
        ];
    }

    /**
     * Generate PDF HTML
     */
    protected function generate_pdf_html($data, $title)
    {
        $company = get_option('companyname');
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; color: #333; }
                .header p { margin: 5px 0; color: #666; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                th { background: #4e73df; color: white; }
                tr:nth-child(even) { background: #f8f9fc; }
                .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #666; }
                .summary { margin: 20px 0; padding: 10px; background: #f8f9fc; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . htmlspecialchars($company) . '</h1>
                <h2>' . htmlspecialchars($title) . '</h2>
                <p>' . _l('generated_on') . ': ' . date('Y-m-d H:i:s') . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>' . _l('asset_code') . '</th>
                        <th>' . _l('asset_name') . '</th>
                        <th>' . _l('asset_group') . '</th>
                        <th>' . _l('location') . '</th>
                        <th>' . _l('amount') . '</th>
                        <th>' . _l('unit_price') . '</th>
                        <th>' . _l('date_buy') . '</th>
                        <th>' . _l('status') . '</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data as $asset) {
            $html .= '<tr>
                <td>' . htmlspecialchars($asset->assets_code) . '</td>
                <td>' . htmlspecialchars($asset->assets_name) . '</td>
                <td>' . htmlspecialchars($asset->group_name ?? '') . '</td>
                <td>' . htmlspecialchars($asset->location ?? '') . '</td>
                <td>' . htmlspecialchars($asset->amount) . '</td>
                <td>' . app_format_money($asset->unit_price, get_base_currency()) . '</td>
                <td>' . htmlspecialchars($asset->date_buy) . '</td>
                <td>' . $this->get_status_label($asset->status) . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
            </table>
            
            <div class="footer">
                <p>' . _l('total_assets') . ': ' . count($data) . '</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Export to Excel
     */
    protected function export_to_excel($data, $filename)
    {
        $spreadsheet_data = [];
        
        // Headers
        $spreadsheet_data[] = [
            _l('asset_code'),
            _l('asset_name'),
            _l('asset_group'),
            _l('location'),
            _l('department'),
            _l('amount'),
            _l('unit_price'),
            _l('date_buy'),
            _l('warranty_period'),
            _l('status')
        ];
        
        foreach ($data as $asset) {
            $spreadsheet_data[] = [
                $asset->assets_code,
                $asset->assets_name,
                $asset->group_name ?? '',
                $asset->location ?? '',
                $asset->department_name ?? '',
                $asset->amount,
                $asset->unit_price,
                $asset->date_buy,
                $asset->warranty_period,
                $this->get_status_label($asset->status)
            ];
        }
        
        return $this->generate_excel_file($spreadsheet_data, $filename);
    }

    /**
     * Export to CSV
     */
    protected function export_to_csv($data, $filename)
    {
        $output = fopen('php://temp', 'r+');
        
        // BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($output, [
            _l('asset_code'),
            _l('asset_name'),
            _l('asset_group'),
            _l('location'),
            _l('department'),
            _l('amount'),
            _l('unit_price'),
            _l('date_buy'),
            _l('warranty_period'),
            _l('status')
        ]);
        
        foreach ($data as $asset) {
            fputcsv($output, [
                $asset->assets_code,
                $asset->assets_name,
                $asset->group_name ?? '',
                $asset->location ?? '',
                $asset->department_name ?? '',
                $asset->amount,
                $asset->unit_price,
                $asset->date_buy,
                $asset->warranty_period,
                $this->get_status_label($asset->status)
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return [
            'content' => $csv,
            'filename' => $filename . '_' . date('Y-m-d') . '.csv',
            'mime' => 'text/csv'
        ];
    }

    /**
     * Generate Excel file
     */
    protected function generate_excel_file($data, $filename)
    {
        // Simple XML-based Excel format (xlsx alternative)
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <?mso-application progid="Excel.Sheet"?>
        <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
            xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
            <Worksheet ss:Name="Sheet1">
                <Table>';
        
        foreach ($data as $row) {
            $xml .= '<Row>';
            foreach ($row as $cell) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($cell) . '</Data></Cell>';
            }
            $xml .= '</Row>';
        }
        
        $xml .= '</Table>
            </Worksheet>
        </Workbook>';
        
        return [
            'content' => $xml,
            'filename' => $filename . '_' . date('Y-m-d') . '.xls',
            'mime' => 'application/vnd.ms-excel'
        ];
    }

    /**
     * Export depreciation report to PDF
     */
    protected function export_depreciation_pdf($data)
    {
        $company = get_option('companyname');
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . _l('depreciation_report') . '</title>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
                .header { text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
                th { background: #4e73df; color: white; }
                tr:nth-child(even) { background: #f8f9fc; }
                .text-right { text-align: right; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . htmlspecialchars($company) . '</h1>
                <h2>' . _l('depreciation_report') . '</h2>
                <p>' . date('Y-m-d') . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>' . _l('asset_code') . '</th>
                        <th>' . _l('asset_name') . '</th>
                        <th>' . _l('date_buy') . '</th>
                        <th class="text-right">' . _l('original_price') . '</th>
                        <th>' . _l('depreciation_month') . '</th>
                        <th>' . _l('months_used') . '</th>
                        <th class="text-right">' . _l('depreciation_value') . '</th>
                        <th class="text-right">' . _l('residual_value') . '</th>
                    </tr>
                </thead>
                <tbody>';
        
        $total_original = 0;
        $total_depreciation = 0;
        $total_residual = 0;
        
        foreach ($data as $asset) {
            $total_original += $asset['total_value'];
            $total_depreciation += $asset['depreciation_value'];
            $total_residual += $asset['residual_value'];
            
            $html .= '<tr>
                <td>' . htmlspecialchars($asset['assets_code']) . '</td>
                <td>' . htmlspecialchars($asset['assets_name']) . '</td>
                <td>' . htmlspecialchars($asset['date_buy']) . '</td>
                <td class="text-right">' . app_format_money($asset['total_value'], get_base_currency()) . '</td>
                <td>' . $asset['depreciation'] . '</td>
                <td>' . $asset['months_used'] . '</td>
                <td class="text-right">' . app_format_money($asset['depreciation_value'], get_base_currency()) . '</td>
                <td class="text-right">' . app_format_money($asset['residual_value'], get_base_currency()) . '</td>
            </tr>';
        }
        
        $html .= '<tr style="font-weight: bold; background: #e3e6f0;">
                <td colspan="3">' . _l('total') . '</td>
                <td class="text-right">' . app_format_money($total_original, get_base_currency()) . '</td>
                <td colspan="2"></td>
                <td class="text-right">' . app_format_money($total_depreciation, get_base_currency()) . '</td>
                <td class="text-right">' . app_format_money($total_residual, get_base_currency()) . '</td>
            </tr>';
        
        $html .= '</tbody>
            </table>
        </body>
        </html>';
        
        return [
            'content' => $html,
            'filename' => 'depreciation_report_' . date('Y-m-d') . '.html',
            'mime' => 'text/html'
        ];
    }

    /**
     * Export depreciation to Excel
     */
    protected function export_depreciation_excel($data)
    {
        $spreadsheet_data = [];
        $spreadsheet_data[] = [
            _l('asset_code'),
            _l('asset_name'),
            _l('date_buy'),
            _l('original_price'),
            _l('depreciation_month'),
            _l('months_used'),
            _l('depreciation_value'),
            _l('residual_value'),
            _l('depreciation_percentage')
        ];
        
        foreach ($data as $asset) {
            $spreadsheet_data[] = [
                $asset['assets_code'],
                $asset['assets_name'],
                $asset['date_buy'],
                $asset['total_value'],
                $asset['depreciation'],
                $asset['months_used'],
                $asset['depreciation_value'],
                $asset['residual_value'],
                round($asset['depreciation_percentage'], 2) . '%'
            ];
        }
        
        return $this->generate_excel_file($spreadsheet_data, 'depreciation_report');
    }

    /**
     * Export depreciation to CSV
     */
    protected function export_depreciation_csv($data)
    {
        $output = fopen('php://temp', 'r+');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            _l('asset_code'),
            _l('asset_name'),
            _l('date_buy'),
            _l('original_price'),
            _l('depreciation_month'),
            _l('months_used'),
            _l('depreciation_value'),
            _l('residual_value')
        ]);
        
        foreach ($data as $asset) {
            fputcsv($output, [
                $asset['assets_code'],
                $asset['assets_name'],
                $asset['date_buy'],
                $asset['total_value'],
                $asset['depreciation'],
                $asset['months_used'],
                $asset['depreciation_value'],
                $asset['residual_value']
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return [
            'content' => $csv,
            'filename' => 'depreciation_report_' . date('Y-m-d') . '.csv',
            'mime' => 'text/csv'
        ];
    }

    /**
     * Get status label
     */
    protected function get_status_label($status)
    {
        $statuses = [
            1 => _l('not_pending_yet'),
            2 => _l('using'),
            3 => _l('liquidation'),
            4 => _l('warranty_repair'),
            5 => _l('lost'),
            6 => _l('broken')
        ];
        return $statuses[$status] ?? $status;
    }

    // Placeholder methods for other report types
    protected function export_maintenance_pdf($data) { return $this->export_to_pdf($data, 'maintenance_report', _l('maintenance_report')); }
    protected function export_maintenance_excel($data) { return $this->generate_excel_file([['Maintenance Report']], 'maintenance_report'); }
    protected function export_maintenance_csv($data) { return $this->export_to_csv($data, 'maintenance_report'); }
    protected function export_checkout_pdf($data) { return $this->export_to_pdf($data, 'checkout_report', _l('checkout_report')); }
    protected function export_checkout_excel($data) { return $this->generate_excel_file([['Checkout Report']], 'checkout_report'); }
    protected function export_checkout_csv($data) { return $this->export_to_csv($data, 'checkout_report'); }
    protected function export_audit_pdf($data) { return $this->export_to_pdf($data, 'audit_report', _l('audit_report')); }
    protected function export_audit_excel($data) { return $this->generate_excel_file([['Audit Report']], 'audit_report'); }
    protected function export_audit_csv($data) { return $this->export_to_csv($data, 'audit_report'); }
    protected function export_utilization_pdf($data) { return $this->export_to_pdf($data, 'utilization_report', _l('utilization_report')); }
    protected function export_utilization_excel($data) { return $this->generate_excel_file([['Utilization Report']], 'utilization_report'); }
    protected function export_utilization_csv($data) { return $this->export_to_csv($data, 'utilization_report'); }
}
