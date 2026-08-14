<?php

declare(strict_types=1);

namespace WooCommerce\Exceptions;

use Throwable;

/**
 * Wraps every error from the WooCommerce REST API. Carries the
 * structured context needed to both surface a useful error to the
 * admin UI and to write a structured log row.
 */
class ApiException extends WooCommerceException
{
    /** @var int HTTP status code, 0 if the request never reached the server. */
    public readonly int $httpCode;

    public readonly string $endpoint;

    /** @var array<string, mixed> */
    public array $context;

    public readonly string $correlationId;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message,
        int $httpCode,
        string $endpoint,
        string $correlationId = '',
        array $context = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);

        $this->httpCode      = $httpCode;
        $this->endpoint      = $endpoint;
        $this->correlationId = $correlationId;
        $this->context       = $context;
    }

    /**
     * @return array<string, mixed>
     */
    public function toLogContext(): array
    {
        return [
            'http_code'      => $this->httpCode,
            'endpoint'       => $this->endpoint,
            'correlation_id' => $this->correlationId,
            'message'        => $this->getMessage(),
        ] + $this->context;
    }
}
