<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\StoreDTO;

/**
 * Dispatches a verified, deduped webhook payload to the right per-
 * resource handler. Skeleton interface here so T3.3 (controller) can
 * land before T3.4 (real handlers) — production wiring uses the
 * `LoggingWebhookDispatcher` no-op default until T3.4 ships the
 * concrete handlers per spec §13.3.
 *
 * Topic convention from Woo: `<resource>.<event>` — e.g.
 * `order.created`, `product.updated`, `customer.deleted`.
 */
interface WebhookDispatcher
{
    /**
     * @param array<string, mixed> $payload Decoded webhook body.
     */
    public function dispatch(StoreDTO $store, string $topic, array $payload, string $correlationId): void;
}
