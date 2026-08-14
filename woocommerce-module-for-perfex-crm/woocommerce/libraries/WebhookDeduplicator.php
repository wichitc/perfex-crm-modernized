<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use WooCommerce\Repositories\WebhookLogRepository;

/**
 * Replay protection for inbound webhooks.
 *
 * Woo retries deliveries on any non-2xx response, so the same
 * `delivery_id` can arrive more than once. The unique key on
 * `tblwoocommerce_webhook_log.delivery_id` (migration 240) is the
 * underlying gate; this library is the friendly wrapper webhook
 * controllers use:
 *
 *   if (!$dedup->firstTimeSeen($deliveryId, $storeId)) {
 *       return ack200('replay');
 *   }
 *
 * Pruning happens via `WebhookLogRepository::prune()` from the cron
 * (default 24h retention — matches §13.2's dedup window).
 *
 * Spec refs: §13.2 steps 4–5, SEC-006.
 */
final class WebhookDeduplicator
{
    private WebhookLogRepository $logs;

    public function __construct(WebhookLogRepository $logs)
    {
        $this->logs = $logs;
    }

    /**
     * Returns true if this delivery_id has not been seen yet, false
     * if it has. Stateless from the caller's point of view: the
     * "first" call wins, every other call is treated as a replay.
     *
     * `$storeId` is taken explicitly so future per-store stats can be
     * derived from it without changing the call sites.
     */
    public function firstTimeSeen(string $deliveryId, int $storeId): bool
    {
        if ($deliveryId === '') {
            // No delivery id → can't dedupe; treat as never-seen so
            // the caller's invariant ("first time") holds, and rely
            // on the controller to reject the malformed request.
            return true;
        }

        return ! $this->logs->seenDeliveryId($deliveryId);
    }
}
