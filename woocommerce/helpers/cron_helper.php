<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CronOrchestrator;
use WooCommerce\Libraries\JobQueue;
use WooCommerce\Libraries\StoreLock;
use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Repositories\CustomersRepository;
use WooCommerce\Repositories\JobsRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\OrdersRepository;
use WooCommerce\Repositories\ProductsRepository;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Repositories\SummaryRepository;
use WooCommerce\Repositories\WebhookLogRepository;
use WooCommerce\Services\DefaultApiClientFactory;
use WooCommerce\Services\Telemetry;
use WooCommerce\Services\WooSyncService;

if (! function_exists('woocommerce_cron')) {
    /**
     * Perfex cron entry point — called every tick by the framework's
     * task runner. Wires the orchestrator from CI's $db + $config and
     * delegates the actual loop to CronOrchestrator (which is the
     * unit-tested seam).
     *
     * The wiring is intentionally minimal — anything that needs
     * isolation lives behind a constructor argument and is testable
     * outside Perfex.
     *
     * Spec refs: §12.
     */
    function woocommerce_cron(): void
    {
        if (! function_exists('get_instance')) {
            return; // not running inside Perfex
        }

        $CI =& get_instance();

        $appKey = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : '';
        if ($appKey === '') {
            if (function_exists('log_activity')) {
                log_activity('WooCommerce cron skipped: APP_ENC_KEY is empty.');
            }
            return;
        }

        $cipher = new CredentialCipher($appKey);
        $stores = new StoresRepository($CI->db, $cipher);
        $lock   = new StoreLock($CI->db);
        $log    = new LogRepository($CI->db);

        $sync = new WooSyncService(
            new DefaultApiClientFactory($log),
            new OrdersRepository($CI->db),
            new ProductsRepository($CI->db),
            new CustomersRepository($CI->db),
            new SummaryRepository($CI->db),
            $stores,
            $log,
        );

        $orchestrator = new CronOrchestrator($stores, $lock, $sync, $log);
        $orchestrator->tick();

        // Drain the job queue (auto-conversions queued by webhook
        // dispatcher) at the end of every tick. Bounded to keep one
        // tick from being eaten by a flood of jobs.
        $jobs = new JobQueue(new JobsRepository($CI->db), $log);
        // Job-handler registration lands in Phase 5 — until then the
        // queue gracefully marks unknown types as failed/quarantined
        // rather than fatal-erroring.
        $jobs->processQueue();

        // Retention: drop old webhook delivery records past the
        // dedup window and old log rows past the retention window.
        (new WebhookLogRepository($CI->db))->prune();
        $log->prune();

        // T7.7: opt-in anonymous telemetry. No-op when the admin
        // hasn't toggled the setup-wizard checkbox; sends a tiny
        // PII-free fingerprint when they have.
        (new Telemetry($CI->db, $log))->maybeSend();
    }
}
