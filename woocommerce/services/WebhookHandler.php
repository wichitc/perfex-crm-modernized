<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use Throwable;
use WooCommerce\Exceptions\EntityNotFoundException;
use WooCommerce\Libraries\SignatureVerifier;
use WooCommerce\Libraries\WebhookDeduplicator;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\StoresRepository;
use WooCommerce\Repositories\WebhookLogRepository;

/**
 * Pure handler for inbound webhook deliveries — the testable seam
 * behind the thin Webhook controller. Implements the §13.2 flow:
 *
 *   1. Resolve the store by id.
 *   2. Verify the signature against the store's webhook_secret.
 *   3. Reject delivery_id that's been seen in the dedup window.
 *   4. Record the delivery (received_at, signature_ok, payload_hash).
 *   5. Dispatch to per-resource handler via WebhookDispatcher.
 *   6. Mark the delivery `processed = 1` (or processed=0 + error on
 *      handler failure).
 *
 * Returns a `WebhookResponse{httpCode, body}` so the controller can
 * just echo it.
 *
 * Spec refs: §13, §13.2.
 */
final class WebhookHandler
{
    public function __construct(
        private StoresRepository       $stores,
        private SignatureVerifier      $verifier,
        private WebhookDeduplicator    $deduplicator,
        private WebhookLogRepository   $webhookLog,
        private LogRepository          $log,
        private WebhookDispatcher      $dispatcher,
    ) {
    }

    /**
     * @param array<string, string> $headers Inbound HTTP headers, original casing irrelevant — caller normalises.
     */
    public function handle(int $storeId, string $rawBody, array $headers): WebhookResponse
    {
        $correlationId = BaseApiClient::generateCorrelationId();
        $headers       = self::normaliseHeaders($headers);

        $deliveryId = $headers['x-wc-webhook-delivery-id'] ?? '';
        $signature  = $headers['x-wc-webhook-signature']   ?? '';
        $topic      = $headers['x-wc-webhook-topic']       ?? '';

        if ($deliveryId === '') {
            $this->logWarn('webhook.bad_request_missing_delivery_id', $storeId, $correlationId, ['topic' => $topic]);
            return new WebhookResponse(400, ['success' => false, 'error' => 'missing_delivery_id']);
        }

        try {
            $store = $this->stores->findStore($storeId);
        } catch (EntityNotFoundException) {
            $this->logWarn('webhook.store_not_found', null, $correlationId, ['store_id' => $storeId]);
            return new WebhookResponse(404, ['success' => false, 'error' => 'store_not_found']);
        }

        $secret = $store->webhookSecret ?? '';
        if (! $this->verifier->verify($rawBody, $signature, $secret)) {
            $this->log->write(
                LogRepository::LEVEL_WARN,
                'webhook.signature_failed',
                ['topic' => $topic, 'delivery_id' => $deliveryId],
                $storeId,
                $correlationId,
            );
            return new WebhookResponse(401, ['success' => false, 'error' => 'bad_signature']);
        }

        if (! $this->deduplicator->firstTimeSeen($deliveryId, $storeId)) {
            $this->log->write(
                LogRepository::LEVEL_INFO,
                'webhook.dedup_hit',
                ['topic' => $topic, 'delivery_id' => $deliveryId],
                $storeId,
                $correlationId,
            );
            return new WebhookResponse(200, ['success' => true, 'processed' => false, 'reason' => 'replay']);
        }

        [$resource, ] = self::splitTopic($topic);
        $payload = self::decodeBody($rawBody);

        $this->webhookLog->recordReceived(
            storeId:     $storeId,
            topic:       $topic,
            resource:    $resource,
            wooId:       isset($payload['id']) ? (int) $payload['id'] : null,
            deliveryId:  $deliveryId,
            signatureOk: true,
            payloadHash: hash('sha256', $rawBody),
        );

        try {
            $this->dispatcher->dispatch($store, $topic, $payload, $correlationId);
            $this->webhookLog->recordProcessed($deliveryId);
        } catch (Throwable $e) {
            $this->webhookLog->recordProcessed($deliveryId, $e->getMessage());
            $this->log->write(
                LogRepository::LEVEL_ERROR,
                'webhook.dispatch_failed',
                [
                    'topic'       => $topic,
                    'delivery_id' => $deliveryId,
                    'exception'   => $e::class,
                    'message'     => $e->getMessage(),
                ],
                $storeId,
                $correlationId,
            );

            // Per spec §13.2 step 7: return 200 even on permanent
            // failure so Woo doesn't retry forever; we still recorded
            // the failure for support.
            return new WebhookResponse(200, [
                'success'   => false,
                'processed' => false,
                'reason'    => 'handler_failed',
            ]);
        }

        return new WebhookResponse(200, ['success' => true, 'processed' => true]);
    }

    /** @param array<string, mixed> $context */
    private function logWarn(string $event, ?int $storeId, string $correlationId, array $context): void
    {
        $this->log->write(LogRepository::LEVEL_WARN, $event, $context, $storeId, $correlationId);
    }

    /**
     * @param array<string, string|array<int, string>> $headers
     * @return array<string, string>
     */
    private static function normaliseHeaders(array $headers): array
    {
        $out = [];
        foreach ($headers as $name => $value) {
            $out[strtolower((string) $name)] = is_array($value) ? (string) ($value[0] ?? '') : (string) $value;
        }
        return $out;
    }

    /**
     * @return array{0:string, 1:string} [resource, event]
     */
    private static function splitTopic(string $topic): array
    {
        $parts = explode('.', $topic, 2);
        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    /** @return array<string, mixed> */
    private static function decodeBody(string $body): array
    {
        if ($body === '') {
            return [];
        }
        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : [];
    }
}
