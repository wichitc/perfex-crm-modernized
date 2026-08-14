<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * What `WebhookHandler::handle()` returns. Lifted into a value object
 * so the test surface is a single immutable structure rather than a
 * pair of out-arguments / globals.
 */
final class WebhookResponse
{
    /** @param array<string, mixed> $body */
    public function __construct(
        public readonly int $httpCode,
        public readonly array $body,
    ) {
    }

    public function bodyJson(): string
    {
        return (string) json_encode($this->body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
