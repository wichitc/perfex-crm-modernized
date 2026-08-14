<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\StoreDTO;

/**
 * Default `WebhookDispatcher` that just logs the dispatch. Used as
 * the production wiring until T3.4 ships the real per-resource
 * handlers — keeps a tenant who upgrades between T3.3 and T3.4 from
 * fatal-erroring on every inbound webhook.
 */
final class LoggingWebhookDispatcher implements WebhookDispatcher
{
    public function __construct(private LogRepository $log)
    {
    }

    public function dispatch(StoreDTO $store, string $topic, array $payload, string $correlationId): void
    {
        $this->log->write(
            LogRepository::LEVEL_INFO,
            'webhook.dispatched_noop',
            [
                'topic'    => $topic,
                'woo_id'   => $payload['id'] ?? null,
                'note'     => 'No-op dispatcher — concrete handler ships in T3.4.',
            ],
            $store->storeId,
            $correlationId,
        );
    }
}
