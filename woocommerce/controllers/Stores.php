<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Libraries\MappingResolver;
use WooCommerce\Libraries\PresetLoader;
use WooCommerce\Repositories\CustomerFieldMappingRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\OrderFieldMappingRepository;
use WooCommerce\Repositories\ProductFieldMappingRepository;
use WooCommerce\Repositories\StoreDTO;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Services\DefaultApiClientFactory;
use WooCommerce\Services\MappingService;

/**
 * Stores admin controller. Mapping override / revert (T4.3) +
 * active-store switcher (T6.1).
 *
 * Every state-changing endpoint runs `has_permission('woocommerce',
 * '', 'edit')` per non-negotiable #4 (spec §9.2).
 *
 * @property CI_Input  $input
 * @property CI_Output $output
 * @property CI_DB_query_builder|CI_DB_mysqli_driver $db
 */
class Stores extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        // AdminController already gates on staff_logged_in() — keep
        // this defensive check for tests / sandbox boots where the
        // session helper isn't loaded.
        if (function_exists('staff_logged_in') && ! staff_logged_in()) {
            redirect(admin_url('authentication/admin'));
        }
    }

    private function buildStoresRepository(): StoresRepository
    {
        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new CredentialCipher($appKey);
        return new StoresRepository($this->db, $cipher);
    }

    /**
     * GET /admin/woocommerce/stores/create — render the wizard with no
     * pre-filled values. Same view as edit, just with a null $store.
     */
    public function create(): void
    {
        if (! has_permission('woocommerce', '', 'create')) {
            access_denied('woocommerce');
            return;
        }
        $this->load->view('modals/new_store', [
            'title'             => _l('woocommerce_new_store') . ' | ' . _l('woocommerce'),
            'store'             => null,
            'staffOptions'      => $this->staffOptions(),
            'assignedStaffIds'  => [],
        ]);
    }

    /**
     * GET /admin/woocommerce/stores/edit/{id} — render the wizard with
     * the store's existing values pre-filled.
     */
    public function edit(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            access_denied('woocommerce');
            return;
        }
        if ($id <= 0) {
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        try {
            $store = $this->buildStoresRepository()->findStore($id);
        } catch (\Throwable $e) {
            set_alert('danger', _l('woocommerce_store_not_found'));
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        $this->load->view('modals/edit_store', [
            'title'            => _l('woocommerce_edit_store') . ' — ' . $store->name . ' | ' . _l('woocommerce'),
            'store'            => $store,
            'staffOptions'     => $this->staffOptions(),
            'assignedStaffIds' => $this->assignedStaffIds($id),
        ]);
    }

    /**
     * POST /admin/woocommerce/stores/save — wizard submit. Persists the
     * full DTO and redirects back to the list with a success alert. We
     * keep it idempotent: the same payload reposted simply rewrites the
     * row, so a flaky network won't double-create.
     */
    public function save(): void
    {
        $id = (int) $this->input->post('store_id');
        $cap = $id > 0 ? 'edit' : 'create';
        if (! has_permission('woocommerce', '', $cap)) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }

        try {
            $dto = $this->dtoFromPost();
        } catch (InvalidArgumentException $e) {
            $this->respondJson(400, ['success' => false, 'error' => $e->getMessage()]);
            return;
        }

        $repo = $this->buildStoresRepository();
        if ($id > 0) {
            $repo->updateStore($id, $dto);
            $newId = $id;
            $msg   = 'updated_successfully';
        } else {
            $newId = $repo->insertStore($dto->withChanges([
                'dateCreated' => date('Y-m-d H:i:s'),
            ]));
            $msg = 'added_successfully';
        }

        $this->persistAssignedStaff($newId, (array) $this->input->post('assigned_staff'));

        set_alert('success', _l($msg, _l('woocommerce_store')));
        $this->respondJson(200, [
            'success'  => true,
            'store_id' => $newId,
            'redirect' => admin_url('woocommerce/stores'),
        ]);
    }

    /**
     * POST /admin/woocommerce/stores/credentials_test — used by the
     * wizard's "Test Connection" button. Builds a transient DTO from
     * the in-progress form values and pings `data/currencies/current`
     * via {@see BaseApiClient::testConnection()}. Returns a JSON ok
     * flag so the wizard can show inline feedback.
     */
    public function credentials_test(): void
    {
        if (! has_permission('woocommerce', '', 'create')
            && ! has_permission('woocommerce', '', 'edit')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }

        $url    = trim((string) $this->input->post('url'));
        $key    = trim((string) $this->input->post('consumer_key'));
        $secret = trim((string) $this->input->post('consumer_secret'));
        $verify = (bool) $this->input->post('verify_ssl');

        if ($url === '' || $key === '' || $secret === '') {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_credentials']);
            return;
        }

        $store = new StoreDTO(
            storeId: null,
            name: 'preview',
            url: $url,
            key: $key,
            secret: $secret,
            verifySsl: $verify,
        );

        $logRepo = new LogRepository($this->db);
        $factory = new DefaultApiClientFactory($logRepo);
        $client  = $factory->orders($store);
        $ok      = $client->testConnection();

        $this->respondJson(200, ['success' => $ok]);
    }

    /**
     * GET /admin/woocommerce/stores/delete/{id}. Hard delete; FK CASCADE
     * on field-mapping tables clears those automatically.
     */
    public function delete(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'delete')) {
            access_denied('woocommerce');
            return;
        }
        if ($id > 0) {
            $this->db->where('store_id', $id)->delete(db_prefix() . 'woocommerce_stores');
            set_alert('success', _l('deleted', _l('woocommerce_store')));
        }
        redirect(admin_url('woocommerce/stores'));
    }

    /**
     * GET /admin/woocommerce/stores/refresh/{id}. Triggers a one-off
     * sync tick by enqueueing the cron job for this specific store.
     * The actual sync runs on the next cron tick — we don't block the
     * admin request on it.
     */
    public function refresh(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            access_denied('woocommerce');
            return;
        }
        if ($id > 0) {
            $this->db->where('store_id', $id)
                ->update(db_prefix() . 'woocommerce_stores', [
                    'date_modified' => date('Y-m-d H:i:s'),
                ]);
            set_alert('success', _l('woocommerce_refresh_queued'));
        }
        redirect(admin_url('woocommerce/stores'));
    }

    /**
     * GET /admin/woocommerce/stores/mappings/{id}. Hands off to the
     * field-mappings editor (T6.4) scoped to the store.
     */
    public function mappings(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }
        if ($id <= 0) {
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        try {
            $store = $this->buildStoresRepository()->findStore($id);
        } catch (\Throwable $e) {
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        $custRepo = new CustomerFieldMappingRepository($this->db);
        $prodRepo = new ProductFieldMappingRepository($this->db);
        $ordRepo  = new OrderFieldMappingRepository($this->db);

        $presets  = (new PresetLoader($custRepo, $prodRepo, $ordRepo))->readPresets();
        $resolver = new MappingResolver($presets, $custRepo, $prodRepo, $ordRepo);

        $this->load->view('field_mappings', [
            'title'    => _l('woocommerce_field_mappings') . ' — ' . $store->name . ' | ' . _l('woocommerce'),
            'store'    => $store,
            'mappings' => [
                'customer' => $resolver->resolve($id, 'customer'),
                'product'  => $resolver->resolve($id, 'product'),
                'order'    => $resolver->resolve($id, 'order'),
            ],
        ]);
    }

    /**
     * GET /admin/woocommerce/stores/webhooks/{id}. Renders the standalone
     * webhook generation + validation panel (T6.10).
     */
    public function webhooks(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }
        if ($id <= 0) {
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        try {
            $store = $this->buildStoresRepository()->findStore($id);
        } catch (\Throwable $e) {
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        $this->load->view('webhooks', [
            'title' => _l('woocommerce_webhooks') . ' — ' . $store->name . ' | ' . _l('woocommerce'),
            'store' => $store,
        ]);
    }

    /**
     * POST /admin/woocommerce/stores/webhooks_generate/{id} (T6.10).
     * Creates the remote webhooks for the topics the user selected.
     * Each topic posts to our /webhook/index/{storeId} endpoint and
     * uses the store's `webhook_secret` for HMAC signing.
     *
     * Idempotent: if a topic already exists remotely we POST it again
     * via Woo's batch endpoint, which Woo will reject with 400 — we
     * collect those into a "skipped" bucket so the UI can show the
     * existing ones distinctly.
     */
    public function webhooks_generate(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($id <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_store_id']);
            return;
        }

        try {
            $store = $this->buildStoresRepository()->findStore($id);
        } catch (\Throwable $e) {
            $this->respondJson(404, ['success' => false, 'error' => 'store_not_found']);
            return;
        }

        $topicsSelected = (array) $this->input->post('topics');
        $allTopics      = self::canonicalTopicMap();
        // Public endpoint, NOT admin-prefixed — matches `config/csrf_exclude_uris.php`.
        // Using admin_url() here registers the webhook at /admin/woocommerce/webhook/...
        // which the CSRF allow-list (anchored ^...$) doesn't match, so the inbound
        // POST gets rejected 419 before the Webhook controller runs.
        $deliveryUrl    = site_url('woocommerce/webhook/index/' . $id);

        $logRepo = new LogRepository($this->db);
        $client  = (new DefaultApiClientFactory($logRepo))->webhooks($store);

        // De-dup: pull the existing remote webhooks first and skip any
        // (topic, delivery_url) pair we've already registered. Keyed on
        // both because the same Perfex install might host multiple Woo
        // → Perfex routes when admins move stores around. Falling back
        // to "no existing" on a remote read failure means we still try
        // to create — Woo's `webhooks/batch` will reject a true
        // duplicate (HTTP 400) rather than register it twice.
        $existingPairs = [];
        try {
            $remoteList = $client->list(['per_page' => 100]);
            foreach ((array) $remoteList as $r) {
                $r = is_object($r) ? (array) $r : (array) $r;
                $topic = (string) ($r['topic'] ?? '');
                $url   = (string) ($r['delivery_url'] ?? '');
                if ($topic !== '' && $url !== '') {
                    $existingPairs[$topic . '|' . $url] = true;
                }
            }
        } catch (\Throwable $e) {
            // Soft-fail: log, but proceed to attempt creation.
            $logRepo->write(
                'warn',
                'webhook_list_before_generate_failed',
                ['detail' => $e->getMessage()],
                $id,
            );
        }

        $createPayload = [];
        $skipped       = [];
        foreach ($topicsSelected as $resource) {
            $resource = is_string($resource) ? $resource : '';
            if (! isset($allTopics[$resource])) {
                continue;
            }
            foreach ($allTopics[$resource] as $topic) {
                if (isset($existingPairs[$topic . '|' . $deliveryUrl])) {
                    $skipped[] = $topic;
                    continue;
                }
                $createPayload[] = [
                    'name'         => 'Perfex / ' . $store->name . ' / ' . $topic,
                    'topic'        => $topic,
                    'delivery_url' => $deliveryUrl,
                    'secret'       => (string) $store->webhookSecret,
                    'status'       => 'active',
                ];
            }
        }

        if ($createPayload === [] && $skipped === []) {
            $this->respondJson(400, ['success' => false, 'error' => 'no_topics_selected']);
            return;
        }

        // Nothing new to create — every selected topic already exists.
        // Surface that to the UI so it can show "all already registered".
        if ($createPayload === []) {
            $this->respondJson(200, [
                'success'   => true,
                'created'   => 0,
                'skipped'   => $skipped,
                'response'  => null,
            ]);
            return;
        }

        try {
            $remoteResp = $client->batch(['create' => $createPayload]);
        } catch (\Throwable $e) {
            $this->respondJson(502, ['success' => false, 'error' => 'remote_failure', 'detail' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, [
            'success'  => true,
            'created'  => count($createPayload),
            'skipped'  => $skipped,
            'response' => $remoteResp,
        ]);
    }

    /**
     * GET /admin/woocommerce/stores/webhooks_status/{id} (T6.10). Fetches
     * the remote webhook list and joins it with our local webhook_log
     * counters so the panel can show "active / last delivery / sig OK
     * / dedup hits" per topic in one call.
     */
    public function webhooks_status(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($id <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_store_id']);
            return;
        }

        try {
            $store = $this->buildStoresRepository()->findStore($id);
        } catch (\Throwable $e) {
            $this->respondJson(404, ['success' => false, 'error' => 'store_not_found']);
            return;
        }

        $logRepo = new LogRepository($this->db);
        $client  = (new DefaultApiClientFactory($logRepo))->webhooks($store);

        try {
            $remote = $client->list(['per_page' => 100]);
        } catch (\Throwable $e) {
            $this->respondJson(502, ['success' => false, 'error' => 'remote_failure', 'detail' => $e->getMessage()]);
            return;
        }

        // Pull our local stats per topic in a single grouped query.
        $localRows = $this->db
            ->select('topic,
                COUNT(*)                                  AS deliveries,
                MAX(received_at)                          AS last_received,
                SUM(signature_ok)                         AS sig_ok,
                SUM(CASE WHEN signature_ok = 0 THEN 1 ELSE 0 END) AS sig_fail,
                SUM(CASE WHEN processed = 0   THEN 1 ELSE 0 END) AS pending', false)
            ->where('store_id', $id)
            ->group_by('topic')
            ->get(db_prefix() . 'woocommerce_webhook_log')
            ->result_array();

        $localByTopic = [];
        foreach ($localRows as $row) {
            $localByTopic[(string) ($row['topic'] ?? '')] = $row;
        }

        $rows = [];
        foreach ((array) $remote as $r) {
            $r = is_object($r) ? (array) $r : (array) $r;
            $topic = (string) ($r['topic'] ?? '');
            $local = $localByTopic[$topic] ?? null;

            $rows[] = [
                'remote_id'     => (int) ($r['id'] ?? 0),
                'topic'         => $topic,
                'status'        => (string) ($r['status'] ?? ''),
                'delivery_url'  => (string) ($r['delivery_url'] ?? ''),
                'created_at'    => (string) ($r['date_created'] ?? ''),
                'deliveries'    => $local ? (int) $local['deliveries'] : 0,
                'last_received' => $local ? (string) ($local['last_received'] ?? '') : '',
                'sig_ok'        => $local ? (int) $local['sig_ok'] : 0,
                'sig_fail'      => $local ? (int) $local['sig_fail'] : 0,
                'pending'       => $local ? (int) $local['pending'] : 0,
            ];
        }

        $this->respondJson(200, ['success' => true, 'rows' => $rows]);
    }

    /**
     * POST /admin/woocommerce/stores/webhooks_delete/{id} (T6.10).
     * Body: remote_id. Deletes the remote webhook so an admin can
     * re-create it cleanly after rotating the secret.
     */
    public function webhooks_delete(int $id = 0): void
    {
        if (! has_permission('woocommerce', '', 'delete')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        $remoteId = (int) $this->input->post('remote_id');
        if ($id <= 0 || $remoteId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_ids']);
            return;
        }

        try {
            $store = $this->buildStoresRepository()->findStore($id);
        } catch (\Throwable $e) {
            $this->respondJson(404, ['success' => false, 'error' => 'store_not_found']);
            return;
        }

        $logRepo = new LogRepository($this->db);
        $client  = (new DefaultApiClientFactory($logRepo))->webhooks($store);

        try {
            $client->batch(['delete' => [$remoteId]]);
        } catch (\Throwable $e) {
            $this->respondJson(502, ['success' => false, 'error' => 'remote_failure', 'detail' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, ['success' => true]);
    }

    // ---------------------------------------------------------------------
    // T6.4 — Field Mappings editor endpoints. The view at views/field_mappings
    // hits these via AJAX. add_mapping / delete_mapping operate on a single
    // row by id; load_preset / reset_tab / preflight act on the whole tab.
    // ---------------------------------------------------------------------

    /**
     * POST /admin/woocommerce/stores/add_mapping/{entity}/{storeId}.
     * Body: wc_field, perfex_field, is_required, default_value.
     */
    public function add_mapping(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_store_id']);
            return;
        }

        $row = [
            'store_id'      => $storeId,
            'wc_field'      => trim((string) $this->input->post('wc_field')),
            'perfex_field'  => trim((string) $this->input->post('perfex_field')),
            'is_required'   => (int) $this->input->post('is_required'),
            'default_value' => (string) $this->input->post('default_value'),
            'is_predefined' => 0,
            'is_overridden' => 0,
        ];
        if ($row['wc_field'] === '' || $row['perfex_field'] === '') {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_fields']);
            return;
        }

        $repo = $this->repoForEntity($entity);
        if ($repo === null) {
            $this->respondJson(400, ['success' => false, 'error' => 'unknown_entity']);
            return;
        }

        $this->db->insert($this->mappingTableFor($entity), $row);
        $this->respondJson(200, [
            'success' => true,
            'id'      => (int) $this->db->insert_id(),
        ]);
    }

    /**
     * POST /admin/woocommerce/stores/delete_mapping/{entity}/{storeId}.
     * Body: id. Predefined rows aren't in the DB (they live in the preset
     * config) so this only deletes overrides + customs — passing a
     * predefined `wc_field/perfex_field` is a no-op by definition.
     */
    public function delete_mapping(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'delete')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        $rowId = (int) $this->input->post('id');
        if ($storeId <= 0 || $rowId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_ids']);
            return;
        }

        $table = $this->mappingTableFor($entity);
        if ($table === '') {
            $this->respondJson(400, ['success' => false, 'error' => 'unknown_entity']);
            return;
        }

        $this->db->where('id', $rowId)->where('store_id', $storeId)->delete($table);
        $this->respondJson(200, [
            'success' => true,
            'deleted' => (int) $this->db->affected_rows(),
        ]);
    }

    /**
     * POST /admin/woocommerce/stores/load_preset/{entity}/{storeId}.
     * Inserts the predefined rows for the entity into the DB so they
     * become editable. Idempotent — `PresetLoader::load` skips rows
     * that already exist.
     */
    public function load_preset(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_store_id']);
            return;
        }

        $custRepo = new CustomerFieldMappingRepository($this->db);
        $prodRepo = new ProductFieldMappingRepository($this->db);
        $ordRepo  = new OrderFieldMappingRepository($this->db);

        try {
            $inserted = (new PresetLoader($custRepo, $prodRepo, $ordRepo))
                ->load($storeId, $entity);
        } catch (\Throwable $e) {
            $this->respondJson(400, ['success' => false, 'error' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, ['success' => true, 'inserted' => $inserted]);
    }

    /**
     * POST /admin/woocommerce/stores/reset_tab/{entity}/{storeId}.
     * Wipes every override + custom row for the entity, leaving only
     * the predefined rows (which live in config/predefined_mappings.php
     * and aren't stored in the DB) visible on the next read.
     */
    public function reset_tab(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'delete')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }
        if ($storeId <= 0) {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_store_id']);
            return;
        }

        $table = $this->mappingTableFor($entity);
        if ($table === '') {
            $this->respondJson(400, ['success' => false, 'error' => 'unknown_entity']);
            return;
        }

        $this->db->where('store_id', $storeId)->delete($table);
        $this->respondJson(200, [
            'success' => true,
            'deleted' => (int) $this->db->affected_rows(),
        ]);
    }

    /**
     * POST /admin/woocommerce/stores/preflight/{entity}/{storeId}.
     * Runs MappingPreflight on the proposed mappings (if posted) or the
     * current store mappings (if not). Returns the warning list shape
     * MappingPreflight emits so the editor can render a modal.
     */
    public function preflight(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
            return;
        }

        $proposed = $this->input->post('mappings');
        if (! is_array($proposed)) {
            $proposed = [];
        }

        // MappingPreflight wants the cache repos (Orders/Products/Customers)
        // — not the field-mapping repos. It pulls a sample of recent cached
        // rows and dry-runs them through the transformer, so it needs to
        // read from the cache tables.
        $ordersRepo    = new \WooCommerce\Repositories\OrdersRepository($this->db);
        $productsRepo  = new \WooCommerce\Repositories\ProductsRepository($this->db);
        $customersRepo = new \WooCommerce\Repositories\CustomersRepository($this->db);
        $transformer   = new \WooCommerce\Libraries\WooToPerfexTransformer(
            new LogRepository($this->db)
        );

        try {
            $preflight = new \WooCommerce\Libraries\MappingPreflight(
                $ordersRepo, $productsRepo, $customersRepo, $transformer
            );
            $report = $preflight->dryRun($storeId, $entity, $proposed);
        } catch (\Throwable $e) {
            $this->respondJson(400, ['success' => false, 'error' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, ['success' => true, 'report' => $report]);
    }

    /**
     * Active staff rows for the wizard's assignee picker.
     *
     * @return list<array{id:int,name:string,email:string}>
     */
    private function staffOptions(): array
    {
        $rows = $this->db
            ->select('staffid, firstname, lastname, email')
            ->where('active', 1)
            ->order_by('firstname', 'asc')
            ->get(db_prefix() . 'staff')
            ->result_array();

        $out = [];
        foreach ($rows as $row) {
            $first = trim((string) ($row['firstname'] ?? ''));
            $last  = trim((string) ($row['lastname'] ?? ''));
            $name  = trim($first . ' ' . $last);
            $out[] = [
                'id'    => (int) ($row['staffid'] ?? 0),
                'name'  => $name !== '' ? $name : (string) ($row['email'] ?? ''),
                'email' => (string) ($row['email'] ?? ''),
            ];
        }
        return $out;
    }

    /**
     * Staff ids currently assigned to a store, for pre-selecting the
     * picker in edit mode.
     *
     * @return list<int>
     */
    private function assignedStaffIds(int $storeId): array
    {
        if ($storeId <= 0) {
            return [];
        }
        $rows = $this->db
            ->select('staff_id')
            ->where('store_id', $storeId)
            ->get(db_prefix() . 'woocommerce_assigned')
            ->result_array();

        return array_values(array_map(static fn(array $r): int => (int) ($r['staff_id'] ?? 0), $rows));
    }

    /**
     * Replace the assigned-staff set for a store. Hard-resets the rows
     * and rewrites them from the posted list — small N (≤ admin staff
     * count) so a delete + re-insert is simpler than a diff.
     *
     * @param array<int|string, mixed> $postedIds
     */
    private function persistAssignedStaff(int $storeId, array $postedIds): void
    {
        if ($storeId <= 0) {
            return;
        }

        $table = db_prefix() . 'woocommerce_assigned';
        $this->db->where('store_id', $storeId)->delete($table);

        $unique = [];
        foreach ($postedIds as $raw) {
            $id = (int) $raw;
            if ($id > 0) {
                $unique[$id] = true;
            }
        }
        foreach (array_keys($unique) as $staffId) {
            $this->db->insert($table, [
                'store_id' => $storeId,
                'staff_id' => $staffId,
            ]);
        }
    }

    private function repoForEntity(string $entity): ?\WooCommerce\Repositories\FieldMappingRepository
    {
        return match ($entity) {
            'customer', 'contact' => new CustomerFieldMappingRepository($this->db),
            'product'             => new ProductFieldMappingRepository($this->db),
            'order'               => new OrderFieldMappingRepository($this->db),
            default               => null,
        };
    }

    private function mappingTableFor(string $entity): string
    {
        return match ($entity) {
            'customer', 'contact' => db_prefix() . 'woocommerce_customer_field_mapping',
            'product'             => db_prefix() . 'woocommerce_product_field_mapping',
            'order'               => db_prefix() . 'woocommerce_order_field_mapping',
            default               => '',
        };
    }

    /**
     * Topic map used by webhooks_generate. Keyed by resource so the UI
     * can present three checkboxes (orders / products / customers)
     * and we know exactly which sub-topics to subscribe each to.
     *
     * @return array<string, list<string>>
     */
    private static function canonicalTopicMap(): array
    {
        return [
            'orders'    => ['order.created',    'order.updated',    'order.deleted'],
            'products'  => ['product.created',  'product.updated',  'product.deleted'],
            'customers' => ['customer.created', 'customer.updated', 'customer.deleted'],
        ];
    }

    /**
     * Build a StoreDTO from the wizard POST. Validates required fields
     * and rejects malformed URLs early — the BaseApiClient assumes a
     * URL with scheme + host and would throw deeper otherwise.
     */
    private function dtoFromPost(): StoreDTO
    {
        $name   = trim((string) $this->input->post('name'));
        $url    = trim((string) $this->input->post('url'));
        $key    = trim((string) $this->input->post('consumer_key'));
        $secret = trim((string) $this->input->post('consumer_secret'));

        if ($name === '' || $url === '' || $key === '' || $secret === '') {
            throw new InvalidArgumentException('missing_required_fields');
        }

        $parsed = parse_url($url);
        if (! is_array($parsed) || empty($parsed['scheme']) || empty($parsed['host'])) {
            throw new InvalidArgumentException('invalid_url');
        }

        $autoStatuses = $this->input->post('auto_invoice_statuses');
        $autoStatusesValue = null;
        if (is_array($autoStatuses)) {
            $autoStatusesValue = implode(',', array_map('strval', $autoStatuses));
        } elseif (is_string($autoStatuses) && $autoStatuses !== '') {
            $autoStatusesValue = $autoStatuses;
        }

        return new StoreDTO(
            storeId: null,
            name: $name,
            url: $url,
            key: $key,
            secret: $secret,
            verifySsl:           (bool) $this->input->post('verify_ssl'),
            isActive:            (bool) ($this->input->post('is_active') ?? true),
            pagesPerTick:        max(1, min(50, (int) $this->input->post('pages_per_tick') ?: 3)),
            autoConvertCustomer: (bool) $this->input->post('auto_convert_customer'),
            autoConvertProduct:  (bool) $this->input->post('auto_convert_product'),
            autoConvertOrder:    (bool) $this->input->post('auto_convert_order'),
            autoInvoiceStatuses: $autoStatusesValue,
            queryAuth:           (bool) $this->input->post('query_auth'),
        );
    }

    /**
     * Stores list. Card grid (T6.2) replacing the legacy DataTable.
     */
    public function index(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new \WooCommerce\Libraries\CredentialCipher($appKey);
        $repo   = new \WooCommerce\Repositories\StoresRepository($this->db, $cipher);

        $staffId = function_exists('get_staff_user_id') ? (int) get_staff_user_id() : 0;
        $activeStoreId = null;
        if ($staffId > 0) {
            $row = $this->db->select('store_id')
                ->where('staffid', $staffId)
                ->limit(1)
                ->get(db_prefix() . 'staff')
                ->row_array();
            if (is_array($row) && ! empty($row['store_id'])) {
                $activeStoreId = (int) $row['store_id'];
            }
        }

        $stores = $repo->listStores();

        // T6.13: zero-store first-run → setup wizard. Skipped if the
        // admin has explicitly dismissed the wizard (progress option
        // bumped to 5) so they don't get punted back into it after
        // deleting the only store.
        if ($stores === [] && (int) (get_option('woocommerce_setup_progress') ?: 1) < 5) {
            redirect(admin_url('woocommerce/setup'));
            return;
        }

        $statsByStore = [];
        foreach ($stores as $store) {
            $sid = (int) $store->storeId;
            $statsByStore[$sid] = $this->collectStoreStats($sid);
        }

        $this->load->view('stores', [
            'title'           => _l('woocommerce_stores') . ' | ' . _l('woocommerce'),
            'stores'          => $stores,
            'stats_by_store'  => $statsByStore,
            'active_store_id' => $activeStoreId,
        ]);
    }

    /**
     * @return array{order_count:int, product_count:int, customer_count:int, last_synced_at:?string, assigned_staff:int, webhooks_active:int}
     */
    private function collectStoreStats(int $storeId): array
    {
        $tbl = static fn(string $t): string => db_prefix() . $t;

        $oc = (int) $this->db->where('store_id', $storeId)->count_all_results($tbl('woocommerce_orders'));
        $pc = (int) $this->db->where('store_id', $storeId)->count_all_results($tbl('woocommerce_products'));
        $cc = (int) $this->db->where('store_id', $storeId)->count_all_results($tbl('woocommerce_customers'));
        $assigned = (int) $this->db->where('store_id', $storeId)->count_all_results($tbl('woocommerce_assigned'));

        $latestRow = $this->db
            ->select_max('last_synced_at', 'last_synced')
            ->where('store_id', $storeId)
            ->get($tbl('woocommerce_orders'))
            ->row_array();
        $lastSynced = is_array($latestRow) ? ($latestRow['last_synced'] ?? null) : null;

        return [
            'order_count'     => $oc,
            'product_count'   => $pc,
            'customer_count'  => $cc,
            'last_synced_at'  => $lastSynced === null ? null : (string) $lastSynced,
            'assigned_staff'  => $assigned,
            'webhooks_active' => 0, // T6.10 webhook panel populates this.
        ];
    }

    /**
     * Switch the staff's active store. POST {store_id}; the page
     * reload picks up the new value via tblstaff.store_id.
     */
    public function switch_active(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            redirect(admin_url());
            return;
        }

        $storeId = (int) $this->input->post('store_id');
        if ($storeId <= 0) {
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('woocommerce/stores'));
            return;
        }

        $staffId = function_exists('get_staff_user_id') ? (int) get_staff_user_id() : 0;
        if ($staffId > 0) {
            $this->db
                ->where('staffid', $staffId)
                ->update(db_prefix() . 'staff', ['store_id' => $storeId]);
        }

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('woocommerce/stores'));
    }

    public function override_mapping(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondForbidden();
            return;
        }

        $origWc     = (string) $this->input->post('wc_field');
        $origPerfex = (string) $this->input->post('perfex_field');
        $newValues  = [
            'wc_field'      => (string) ($this->input->post('new_wc_field')     ?: $origWc),
            'perfex_field'  => (string) ($this->input->post('new_perfex_field') ?: $origPerfex),
            'is_required'   => (int)    ($this->input->post('is_required')      ?: 0),
            'default_value' => (string) ($this->input->post('default_value')    ?: ''),
        ];

        if ($origWc === '' || $origPerfex === '') {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_original_fields']);
            return;
        }

        try {
            $newId = $this->mappingService()->override($storeId, $entity, $origWc, $origPerfex, $newValues);
        } catch (InvalidArgumentException $e) {
            $this->respondJson(400, ['success' => false, 'error' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, ['success' => true, 'id' => $newId]);
    }

    /**
     * Revert an override. Resolution falls back to the predefined
     * row automatically on the next read.
     */
    public function revert_mapping(string $entity = 'customer', int $storeId = 0): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            $this->respondForbidden();
            return;
        }

        $origWc     = (string) $this->input->post('wc_field');
        $origPerfex = (string) $this->input->post('perfex_field');

        if ($origWc === '' || $origPerfex === '') {
            $this->respondJson(400, ['success' => false, 'error' => 'missing_original_fields']);
            return;
        }

        try {
            $reverted = $this->mappingService()->revert($storeId, $entity, $origWc, $origPerfex);
        } catch (InvalidArgumentException $e) {
            $this->respondJson(400, ['success' => false, 'error' => $e->getMessage()]);
            return;
        }

        $this->respondJson(200, ['success' => $reverted]);
    }

    private function mappingService(): MappingService
    {
        return new MappingService(
            new CustomerFieldMappingRepository($this->db),
            new ProductFieldMappingRepository($this->db),
            new OrderFieldMappingRepository($this->db),
        );
    }

    private function respondForbidden(): void
    {
        $this->respondJson(403, ['success' => false, 'error' => 'forbidden']);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function respondJson(int $status, array $payload): void
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output((string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
