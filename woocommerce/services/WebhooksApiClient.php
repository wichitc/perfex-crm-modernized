<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Manage WooCommerce webhooks remotely. The two endpoints in v1:
 *  - `list(filter)` — pull the remote webhooks so the Validate panel
 *    can confirm our delivery URL is registered + active.
 *  - `batch(payload)` — POST /webhooks/batch with create/update/delete
 *    arrays. Used by the one-click "Generate webhooks" CTA.
 */
class WebhooksApiClient extends BaseApiClient
{
    /** @param array<string, mixed> $filter */
    public function list(array $filter = []): mixed
    {
        return $this->makeRequest('GET', 'webhooks', $filter);
    }

    /**
     * @param array{create?: list<array<string, mixed>>, update?: list<array<string, mixed>>, delete?: list<int>} $payload
     */
    public function batch(array $payload): mixed
    {
        return $this->makeRequest('POST', 'webhooks/batch', $payload);
    }
}
