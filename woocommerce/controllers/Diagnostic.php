<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Repositories\StoresRepository;

/**
 * Diagnostic page (T6.12) — single screen support tickets paste from.
 *
 * Spec §4A.11: "PHP version, Perfex version, module version, store
 * count, last cron tick per store, webhook signature health (last N),
 * DB row counts (orders/products/customers per store)."
 *
 * Secrets are masked at the source (we read encrypted-cred columns
 * directly from the cache row, never decrypt for display).
 *
 * @property CI_Output           $output
 * @property CI_DB_query_builder $db
 * @property CI_Loader           $load
 */
class Diagnostic extends AdminController
{
    public function index(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $this->load->view('diagnostic', [
            'title'    => _l('woocommerce_diagnostic') . ' | ' . _l('woocommerce'),
            'snapshot' => $this->buildSnapshot(),
        ]);
    }

    /**
     * Returns the same data the page renders, as JSON, so the "Copy
     * as text" button can put a clean blob on the clipboard.
     */
    public function snapshot(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->output->set_status_header(403);
            return;
        }

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output((string) json_encode($this->buildSnapshot(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSnapshot(): array
    {
        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new CredentialCipher($appKey);
        $storesRepo = new StoresRepository($this->db, $cipher);
        $stores = $storesRepo->listStores();

        $perStore = [];
        foreach ($stores as $store) {
            $sid = (int) $store->storeId;
            $perStore[] = [
                'store_id'      => $sid,
                'name'          => $store->name,
                'url_host'      => parse_url($store->url, PHP_URL_HOST) ?: '(unparseable)',
                'is_active'     => $store->isActive,
                'verify_ssl'    => $store->verifySsl,
                'pages_per_tick' => $store->pagesPerTick,
                'order_count'   => (int) $this->db->where('store_id', $sid)->count_all_results(db_prefix() . 'woocommerce_orders'),
                'product_count' => (int) $this->db->where('store_id', $sid)->count_all_results(db_prefix() . 'woocommerce_products'),
                'customer_count'=> (int) $this->db->where('store_id', $sid)->count_all_results(db_prefix() . 'woocommerce_customers'),
                'last_cron_tick' => $this->lastCronTickFor($sid),
                'webhook_health' => $this->webhookHealthFor($sid),
            ];
        }

        return [
            'php_version'         => PHP_VERSION,
            'perfex_version'      => function_exists('get_app_version') ? (string) get_app_version() : '(unknown)',
            'module_version'      => defined('WOOCOMMERCE_MODULE_VERSION') ? (string) WOOCOMMERCE_MODULE_VERSION : '(unknown)',
            'store_count'         => count($stores),
            'jobs_pending'        => (int) $this->db->where('status', 'pending')->count_all_results(db_prefix() . 'woocommerce_jobs'),
            'jobs_quarantined'    => (int) $this->db->where('status', 'quarantined')->count_all_results(db_prefix() . 'woocommerce_jobs'),
            'log_rows'            => (int) $this->db->count_all_results(db_prefix() . 'woocommerce_log'),
            'webhook_log_rows'    => (int) $this->db->count_all_results(db_prefix() . 'woocommerce_webhook_log'),
            'cipher_versions'     => CredentialCipher::VERSION,
            'app_enc_key_set'     => $appKey !== '' && $appKey !== 'placeholder',
            'stores'              => $perStore,
            'generated_at'        => date('Y-m-d H:i:s'),
        ];
    }

    private function lastCronTickFor(int $storeId): ?string
    {
        $row = $this->db
            ->select('MAX(created_at) AS last')
            ->where('store_id', $storeId)
            ->where_in('event', ['cron.store_skipped_locked', 'cron.summary_failed', 'cron.checkOrders_failed', 'api.request_ok'])
            ->get(db_prefix() . 'woocommerce_log')
            ->row_array();
        return is_array($row) && ! empty($row['last']) ? (string) $row['last'] : null;
    }

    /**
     * @return array{total_received:int, signature_ok:int, signature_failed:int, processed:int, processed_failed:int}
     */
    private function webhookHealthFor(int $storeId): array
    {
        $window = date('Y-m-d H:i:s', time() - 7 * 86400);
        $tbl    = db_prefix() . 'woocommerce_webhook_log';

        return [
            'total_received'   => (int) $this->db->where('store_id', $storeId)->where('received_at >=', $window)->count_all_results($tbl),
            'signature_ok'     => (int) $this->db->where('store_id', $storeId)->where('received_at >=', $window)->where('signature_ok', 1)->count_all_results($tbl),
            'signature_failed' => (int) $this->db->where('store_id', $storeId)->where('received_at >=', $window)->where('signature_ok', 0)->count_all_results($tbl),
            'processed'        => (int) $this->db->where('store_id', $storeId)->where('received_at >=', $window)->where('processed', 1)->count_all_results($tbl),
            'processed_failed' => (int) $this->db->where('store_id', $storeId)->where('received_at >=', $window)->where('processed', 0)->where('error IS NOT NULL', null, false)->count_all_results($tbl),
        ];
    }
}
