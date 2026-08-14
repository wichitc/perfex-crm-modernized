<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Repositories\StoresRepository;

/**
 * Logs view (T6.11). Filterable union over `tblwoocommerce_log` and
 * `tblwoocommerce_webhook_log` so support can chase a delivery from
 * a sliced-and-diced UI without writing SQL.
 *
 * Spec refs: §15, §7.1.9, §7.1.8.
 *
 * @property CI_Input            $input
 * @property CI_Output           $output
 * @property CI_DB_query_builder $db
 * @property CI_Loader           $load
 */
class Logs extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (function_exists('staff_logged_in') && ! staff_logged_in()) {
            redirect(admin_url('authentication/admin'));
            return;
        }
    }

    public function index(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $this->load->view('logs', [
            'title' => _l('woocommerce_logs') . ' | ' . _l('woocommerce'),
            'table' => App_table::find('woo_logs'),
        ]);
    }

    /**
     * AJAX endpoint for the logs DataTable. The union of
     * `tblwoocommerce_log` and `tblwoocommerce_webhook_log` is built
     * inside the table file's `outputUsing()` closure.
     */
    public function table(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            ajax_access_denied();
            return;
        }
        if (! $this->input->is_ajax_request()) {
            show_404();
            return;
        }
        App_table::find('woo_logs')->output();
    }

    /**
     * GET ?id=… returns the full context_json for a single row as
     * JSON, used by the inline modal that pops on row click.
     */
    public function context(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->respondJson(403, ['error' => 'forbidden']);
            return;
        }

        $source = (string) $this->input->get('source'); // 'log' | 'webhook'
        $id     = (int) $this->input->get('id');

        if (! in_array($source, ['log', 'webhook'], true) || $id <= 0) {
            $this->respondJson(400, ['error' => 'bad_request']);
            return;
        }

        $table = $source === 'webhook' ? 'woocommerce_webhook_log' : 'woocommerce_log';
        $row = $this->db
            ->where('id', $id)
            ->limit(1)
            ->get(db_prefix() . $table)
            ->row_array();

        if (! is_array($row)) {
            $this->respondJson(404, ['error' => 'not_found']);
            return;
        }

        $this->respondJson(200, ['source' => $source, 'row' => $row]);
    }

    /** @param array<string, mixed> $payload */
    private function respondJson(int $status, array $payload): void
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output((string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
