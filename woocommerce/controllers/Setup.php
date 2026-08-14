<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Repositories\StoresRepository;

/**
 * First-run setup wizard (T6.13).
 *
 * Detected from {@see Stores::index} when there are zero stores; the
 * stores list redirects here so a fresh admin lands on a guided
 * 4-step flow instead of an empty grid:
 *
 *   1. Welcome — tell the user what's about to happen.
 *   2. Connect first store — hand off to the T6.3 wizard.
 *   3. Load preset mappings — bulk-insert the curated mappings via PresetLoader.
 *   4. Generate webhooks — link to the T6.10 panel.
 *
 * Each step has a "skip" link; the wizard records progress in
 * `tbloptions` keyed `woocommerce_setup_progress`.
 *
 * @property CI_Input  $input
 * @property CI_Output $output
 * @property CI_DB_query_builder $db
 */
class Setup extends AdminController
{
    private const PROGRESS_OPTION = 'woocommerce_setup_progress';

    public function __construct()
    {
        parent::__construct();
        if (function_exists('staff_logged_in') && ! staff_logged_in()) {
            redirect(admin_url('authentication/admin'));
        }
    }

    /**
     * GET /admin/woocommerce/setup — landing page picks the right
     * step based on saved progress + actual repo state.
     */
    public function index(): void
    {
        if (! has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
            return;
        }

        $progress = (int) (get_option(self::PROGRESS_OPTION) ?: 1);
        $stores   = $this->buildStoresRepository()->listStores();

        // If a store has been added since the last visit, jump the wizard
        // forward — but only if the user hasn't already advanced past
        // step 2 themselves.
        if ($stores !== [] && $progress < 3) {
            $progress = 3;
        }

        $this->load->view('setup/wizard', [
            'title'    => _l('woocommerce_setup') . ' | ' . _l('woocommerce'),
            'progress' => $progress,
            'stores'   => $stores,
        ]);
    }

    /**
     * POST /admin/woocommerce/setup/advance — bumps progress to the
     * passed step number. Used by the "Next / Skip" buttons.
     */
    public function advance(): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            redirect(admin_url('woocommerce'));
            return;
        }

        $step = max(1, min(5, (int) $this->input->post('step')));
        $existing = get_option(self::PROGRESS_OPTION);
        if ($existing === '' || $existing === false || $existing === null) {
            add_option(self::PROGRESS_OPTION, (string) $step);
        } else {
            update_option(self::PROGRESS_OPTION, (string) $step);
        }

        // T7.7: persist the telemetry opt-in toggle when the welcome
        // step posts. Only set when the field is present in the post
        // body (so other advance calls don't accidentally clear it).
        if ($this->input->post('telemetry_opt_in') !== null) {
            $optIn = $this->input->post('telemetry_opt_in') === '1' ? '1' : '0';
            if (get_option(\WooCommerce\Services\Telemetry::OPT_IN_OPTION) === '') {
                add_option(\WooCommerce\Services\Telemetry::OPT_IN_OPTION, $optIn);
            } else {
                update_option(\WooCommerce\Services\Telemetry::OPT_IN_OPTION, $optIn);
            }
        }

        if ($step >= 5) {
            redirect(admin_url('woocommerce/stores'));
            return;
        }

        redirect(admin_url('woocommerce/setup'));
    }

    /**
     * POST /admin/woocommerce/setup/load_presets — bulk-load preset
     * mappings into the most-recently-created store for all three
     * entities. Surfaces row counts on the wizard's step 3 banner.
     */
    public function load_presets(): void
    {
        if (! has_permission('woocommerce', '', 'edit')) {
            redirect(admin_url('woocommerce'));
            return;
        }

        $stores = $this->buildStoresRepository()->listStores();
        if ($stores === []) {
            set_alert('warning', _l('woocommerce_setup_no_store_yet'));
            redirect(admin_url('woocommerce/setup'));
            return;
        }

        $latest = $stores[count($stores) - 1];
        $storeId = (int) $latest->storeId;

        $custRepo = new \WooCommerce\Repositories\CustomerFieldMappingRepository($this->db);
        $prodRepo = new \WooCommerce\Repositories\ProductFieldMappingRepository($this->db);
        $ordRepo  = new \WooCommerce\Repositories\OrderFieldMappingRepository($this->db);
        $loader   = new \WooCommerce\Libraries\PresetLoader($custRepo, $prodRepo, $ordRepo);

        $totals = [
            'customer' => $loader->load($storeId, 'customer'),
            'product'  => $loader->load($storeId, 'product'),
            'order'    => $loader->load($storeId, 'order'),
        ];

        set_alert(
            'success',
            sprintf(
                (string) _l('woocommerce_setup_presets_loaded'),
                array_sum($totals)
            )
        );

        update_option(self::PROGRESS_OPTION, '4');
        redirect(admin_url('woocommerce/setup'));
    }

    private function buildStoresRepository(): StoresRepository
    {
        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : 'placeholder';
        $cipher = new CredentialCipher($appKey);
        return new StoresRepository($this->db, $cipher);
    }
}
