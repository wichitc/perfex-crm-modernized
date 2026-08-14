<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Manual order → invoice conversion controller (§5.3).
 *
 * Endpoints:
 *  - GET  woocommerce/woocommerce_invoice/check_customer/{wooCustomerId}
 *  - GET  woocommerce/woocommerce_invoice/check_invoice/{wooOrderId}
 *  - POST woocommerce/woocommerce_invoice/preview/{storeId}/{wooOrderId}
 *  - POST woocommerce/woocommerce_invoice/create_invoice/{storeId}/{wooOrderId}
 *
 * Every state-changing endpoint runs has_permission('woocommerce', '',
 * 'create') per non-negotiable #4. The actual work is delegated to
 * the unit-tested OrderConverter / PaymentRecorder services.
 *
 * @property CI_Input            $input
 * @property CI_Output           $output
 * @property CI_DB_query_builder $db
 */
class Woocommerce_invoice extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (function_exists('staff_logged_in') && ! staff_logged_in()) {
            redirect(admin_url('authentication/admin'));
        }
    }

    public function check_customer(int $wooCustomerId = 0, int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->respondJson(403, ['error' => 'forbidden']);
            return;
        }

        $row = $this->db
            ->select('id')
            ->where('store_id', $storeId)
            ->where('woo_id',   $wooCustomerId)
            ->limit(1)
            ->get(db_prefix() . 'clients')
            ->row_array();

        $this->respondJson(200, [
            'client_id' => is_array($row) ? (int) $row['id'] : null,
        ]);
    }

    public function check_invoice(int $wooOrderId = 0, int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->respondJson(403, ['error' => 'forbidden']);
            return;
        }

        $row = $this->db
            ->select('id')
            ->where('store_id', $storeId)
            ->where('wco_id',   $wooOrderId)
            ->limit(1)
            ->get(db_prefix() . 'invoices')
            ->row_array();

        $this->respondJson(200, [
            'invoice_id' => is_array($row) ? (int) $row['id'] : null,
        ]);
    }

    public function preview(int $storeId = 0, int $wooOrderId = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->respondJson(403, ['error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0 || $wooOrderId <= 0) {
            $this->respondJson(400, ['error' => 'missing_ids']);
            return;
        }

        try {
            $store        = $this->buildStoresRepo()->findStore($storeId);
            $orderPayload = $this->fetchWooOrder($store, $wooOrderId);
            $converter    = $this->buildOrderConverter();
            $report       = $converter->previewConvert($orderPayload, $store);
        } catch (\Throwable $e) {
            $this->respondJson(500, ['error' => 'preview_failed', 'detail' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, $report);
    }

    public function create_invoice(int $storeId = 0, int $wooOrderId = 0): void
    {
        if (! has_permission('woocommerce', '', 'create')) {
            $this->respondJson(403, ['error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0 || $wooOrderId <= 0) {
            $this->respondJson(400, ['error' => 'missing_ids']);
            return;
        }

        try {
            $store        = $this->buildStoresRepo()->findStore($storeId);
            $orderPayload = $this->fetchWooOrder($store, $wooOrderId);
            $converter    = $this->buildOrderConverter();
            $result       = $converter->convert($orderPayload, $store);
        } catch (\Throwable $e) {
            $this->respondJson(500, ['error' => 'convert_failed', 'detail' => $e->getMessage()]);
            return;
        }

        // Stamp invoice_id on the cache row so the orders list + detail
        // flip to "Converted" without waiting for the next sync tick.
        // Idempotent: an existing link from a webhook auto-convert is
        // overwritten with the same id, so re-converts stay clean.
        $invoiceId = (int) $result['invoice_id'];
        if ($invoiceId > 0) {
            (new \WooCommerce\Repositories\OrdersRepository($this->db))
                ->upsertByWooId($storeId, $wooOrderId, [
                    'invoice_id'     => $invoiceId,
                    'last_synced_at' => date('Y-m-d H:i:s'),
                ]);
        }

        if (! $this->input->is_ajax_request()) {
            set_alert('success', _l('woocommerce_convert_success', (string) $invoiceId));
            redirect(admin_url('invoices/list_invoices/' . $invoiceId));
            return;
        }
        $this->respondJson(200, $result);
    }

    /**
     * POST /admin/woocommerce/woocommerce_invoice/import_customer/{storeId}/{wooCustomerId}
     *
     * Creates (or finds + links) a Perfex client for the Woo customer.
     * Wraps GuestClientFactory's findOrCreate against the live Woo
     * customer payload so the resulting Perfex client carries the
     * billing address + name + email exactly as the customer entered.
     */
    public function import_customer(int $storeId = 0, int $wooCustomerId = 0): void
    {
        if (! has_permission('woocommerce', '', 'create')) {
            access_denied('woocommerce');
            return;
        }
        if ($storeId <= 0 || $wooCustomerId <= 0) {
            redirect(admin_url('woocommerce/woocommerce/customers'));
            return;
        }

        try {
            $store    = $this->buildStoresRepo()->findStore($storeId);
            $logRepo  = new \WooCommerce\Repositories\LogRepository($this->db);
            $factory  = new \WooCommerce\Services\DefaultApiClientFactory($logRepo);
            $custResp = $factory->customers($store)->getByWooId($wooCustomerId);
            $customer = is_object($custResp) ? (array) $custResp : (array) $custResp;

            // Project the Woo customer into the order-shaped payload
            // GuestClientFactory expects (it reads $payload['billing']).
            $billing = isset($customer['billing']) ? (array) $customer['billing'] : [];
            // Email lives on the customer root, not always inside billing.
            if (empty($billing['email']) && ! empty($customer['email'])) {
                $billing['email'] = (string) $customer['email'];
            }
            if (empty($billing['first_name']) && ! empty($customer['first_name'])) {
                $billing['first_name'] = (string) $customer['first_name'];
            }
            if (empty($billing['last_name']) && ! empty($customer['last_name'])) {
                $billing['last_name'] = (string) $customer['last_name'];
            }

            $factory   = new \WooCommerce\Services\PerfexClientGateway($this->db);
            $guestSvc  = new \WooCommerce\Services\GuestClientFactory($factory, $logRepo);
            $clientId  = $guestSvc->findOrCreate(['billing' => $billing], $storeId);

            // Stamp woo_id + store_id so future ClientResolver lookups
            // match by that key (uq_tblclients_store_woo).
            $this->db
                ->where('userid', $clientId)
                ->update(db_prefix() . 'clients', [
                    'woo_id'   => $wooCustomerId,
                    'store_id' => $storeId,
                ]);
        } catch (\Throwable $e) {
            set_alert('danger', _l('woocommerce_import_failed', $e->getMessage()));
            redirect(admin_url('woocommerce/woocommerce/customer/' . $storeId . '/' . $wooCustomerId));
            return;
        }

        set_alert('success', _l('woocommerce_import_success'));
        redirect(admin_url('clients/client/' . $clientId));
    }

    private function buildStoresRepo(): \WooCommerce\Repositories\StoresRepository
    {
        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
        return new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchWooOrder(\WooCommerce\Repositories\StoreDTO $store, int $wooOrderId): array
    {
        $logRepo = new \WooCommerce\Repositories\LogRepository($this->db);
        $factory = new \WooCommerce\Services\DefaultApiClientFactory($logRepo);
        $resp    = $factory->orders($store)->getByWooId($wooOrderId);
        return is_object($resp) ? (array) $resp : (array) $resp;
    }

    private function buildOrderConverter(): \WooCommerce\Services\OrderConverter
    {
        $custRepo = new \WooCommerce\Repositories\CustomerFieldMappingRepository($this->db);
        $prodRepo = new \WooCommerce\Repositories\ProductFieldMappingRepository($this->db);
        $ordRepo  = new \WooCommerce\Repositories\OrderFieldMappingRepository($this->db);
        $logRepo  = new \WooCommerce\Repositories\LogRepository($this->db);

        $presets  = (new \WooCommerce\Libraries\PresetLoader($custRepo, $prodRepo, $ordRepo))
            ->readPresets();
        $resolver = new \WooCommerce\Libraries\MappingResolver(
            $presets, $custRepo, $prodRepo, $ordRepo
        );
        $transformer = new \WooCommerce\Libraries\WooToPerfexTransformer($logRepo);
        $paymentMode = new \WooCommerce\Services\PaymentModeService($this->db);

        $clientGateway   = new \WooCommerce\Services\PerfexClientGateway($this->db);
        $invoiceGateway  = new \WooCommerce\Services\PerfexInvoiceGateway($this->db);
        $clientResolver  = new \WooCommerce\Services\PerfexClientResolver($this->db);
        $guestFactory    = new \WooCommerce\Services\GuestClientFactory($clientGateway, $logRepo);

        return new \WooCommerce\Services\OrderConverter(
            $invoiceGateway,
            $guestFactory,
            $resolver,
            $transformer,
            $paymentMode,
            $logRepo,
            $clientResolver,
        );
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
