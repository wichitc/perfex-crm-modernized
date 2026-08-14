<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Libraries\JobQueue;
use WooCommerce\Libraries\SignatureVerifier;
use WooCommerce\Libraries\WebhookDeduplicator;
use WooCommerce\Repositories\CustomersRepository;
use WooCommerce\Repositories\JobsRepository;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\OrdersRepository;
use WooCommerce\Repositories\ProductsRepository;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Repositories\WebhookLogRepository;
use WooCommerce\Services\ResourceWebhookDispatcher;
use WooCommerce\Services\WebhookHandler;

/**
 * Public webhook endpoint at POST /woocommerce/webhook/index/{store_id}.
 *
 * Thin: parses the raw request, builds the handler graph, delegates to
 * `WebhookHandler` (the unit-tested seam), and writes the response.
 *
 * Spec refs: §13.
 *
 * @property CI_Input  $input
 * @property CI_Output $output
 * @property CI_DB_query_builder $db
 */
class Webhook extends App_Controller
{
    public function __construct()
    {
        parent::__construct();

        // CSRF is excluded for this URI in config/csrf_exclude_uris.php.
        // Permission checks: this is a public endpoint authenticated by
        // HMAC, not by Perfex session — no has_permission() here by design.
    }

    public function index(int $storeId = 0): void
    {
        // CI_Input exposes raw_input_stream as a magic property; cast through
        // a local var so static analysers don't choke on it.
        /** @phpstan-var string $rawBody */
        $rawBody  = (string) ($this->input->raw_input_stream ?? '');
        $headers  = $this->collectHeaders();

        $appKey  = defined('APP_ENC_KEY') ? (string) APP_ENC_KEY : '';
        $cipher  = new CredentialCipher($appKey);

        $log         = new LogRepository($this->db);
        $stores      = new StoresRepository($this->db, $cipher);
        $verifier    = new SignatureVerifier($log);
        $webhookLog  = new WebhookLogRepository($this->db);
        $dedup       = new WebhookDeduplicator($webhookLog);

        $jobs = new JobQueue(new JobsRepository($this->db), $log);
        // Job-handler registration lands in Phase 5; the queue still
        // accepts pushes today and the cron will pick them up.

        $dispatcher = new ResourceWebhookDispatcher(
            new OrdersRepository($this->db),
            new ProductsRepository($this->db),
            new CustomersRepository($this->db),
            $jobs,
            $log,
        );

        $handler = new WebhookHandler(
            $stores,
            $verifier,
            $dedup,
            $webhookLog,
            $log,
            $dispatcher,
        );

        $response = $handler->handle($storeId, $rawBody, $headers);

        $this->output
            ->set_status_header($response->httpCode)
            ->set_content_type('application/json', 'utf-8')
            ->set_output($response->bodyJson());
    }

    /**
     * Collect the X-WC-Webhook-* headers from $_SERVER. We don't rely
     * on getallheaders() because it's not present under php-fpm.
     *
     * @return array<string, string>
     */
    private function collectHeaders(): array
    {
        $out = [];
        foreach ($_SERVER as $key => $value) {
            if (! is_string($key) || ! str_starts_with($key, 'HTTP_')) {
                continue;
            }
            $name = strtolower(str_replace('_', '-', substr($key, 5)));
            $out[$name] = (string) $value;
        }
        return $out;
    }
}
