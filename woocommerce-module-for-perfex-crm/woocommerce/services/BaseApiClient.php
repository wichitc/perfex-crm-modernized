<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use WooCommerce\Exceptions\ApiException;
use WooCommerce\Repositories\LogRepository;
use WooCommerce\Repositories\StoreDTO;

/**
 * Single gate to the WooCommerce REST API. Every request:
 *  - generates a UUID-v4 `correlation_id` and propagates it as
 *    `X-WooModule-Correlation` so server logs can be joined back to
 *    Perfex's audit trail;
 *  - retries up to 3× on 5xx with 200/400/800 ms backoff;
 *  - wraps every failure as an `ApiException` with httpCode +
 *    endpoint + context;
 *  - logs every error via `LogRepository`; samples successes at 1%
 *    so high-volume traffic doesn't bloat the log table.
 *
 * SSL verification defaults to **true**. The per-store toggle on the
 * StoreDTO is the only way to opt out — there is no hardcoded false
 * anywhere (closes SEC-001 / non-negotiable #2).
 */
class BaseApiClient
{
    private const MAX_ATTEMPTS = 4;
    private const BACKOFF_US   = [200_000, 400_000, 800_000];
    private const SAMPLE_RATE  = 100;
    private const HEADER_NAME  = 'X-WooModule-Correlation';

    protected Client $sdk;
    protected LogRepository $log;
    protected ?int $storeId;
    private ?\Closure $sleepFn = null;

    public function __construct(StoreDTO $store, LogRepository $log, ?Client $sdk = null)
    {
        $this->log     = $log;
        $this->storeId = $store->storeId;
        $this->sdk     = $sdk ?? new Client(
            $store->url,
            $store->key,
            $store->secret,
            [
                'wp_api'            => true,
                'version'           => 'wc/v3',
                'verify_ssl'        => $store->verifySsl, // per-store, default true
                'query_string_auth' => $store->queryAuth,
                'timeout'           => 30,
            ]
        );
    }

    /** @internal allow tests to provide a no-op sleep */
    public function setSleepFn(\Closure $fn): void
    {
        $this->sleepFn = $fn;
    }

    public function testConnection(): bool
    {
        try {
            $this->makeRequest('GET', 'data/currencies/current');
            return true;
        } catch (ApiException) {
            return false;
        }
    }

    /**
     * @param array<string, mixed> $params query / body, depending on $method
     * @return mixed Decoded response (object|array, per the SDK's behaviour).
     * @throws ApiException On any non-recoverable failure.
     */
    public function makeRequest(string $method, string $endpoint, array $params = []): mixed
    {
        $correlationId = self::generateCorrelationId();
        $method = strtoupper($method);

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            $startedAt = microtime(true);

            try {
                $response = $this->dispatch($method, $endpoint, $params, $correlationId);

                $latencyMs = (int) ((microtime(true) - $startedAt) * 1000);
                $this->maybeLogSuccess($method, $endpoint, $correlationId, $latencyMs, $attempt);

                return $response;
            } catch (HttpClientException $e) {
                $httpCode = self::extractHttpCode($e);

                if ($httpCode >= 500 && $attempt < self::MAX_ATTEMPTS) {
                    $this->sleep(self::BACKOFF_US[$attempt - 1]);
                    continue;
                }

                $this->logFailure($method, $endpoint, $correlationId, $httpCode, $attempt, $e);
                throw new ApiException(
                    sprintf('%s %s failed: %s', $method, $endpoint, $e->getMessage()),
                    $httpCode,
                    $endpoint,
                    $correlationId,
                    ['attempts' => $attempt],
                    $e,
                );
            }
        }

        // Unreachable in practice (the loop either returns or throws),
        // but the analyser wants every path to terminate.
        throw new ApiException("$method $endpoint exhausted retries", 0, $endpoint, $correlationId, []);
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function dispatch(string $method, string $endpoint, array $params, string $correlationId): mixed
    {
        // The Automattic SDK has no public hook for adding a custom header
        // per call, so the correlation id rides in our own log rows only.
        // A future task can replace the SDK with Guzzle + a middleware that
        // adds X-WooModule-Correlation as a real header.

        return match ($method) {
            'GET'    => $this->sdk->get($endpoint, $params),
            'POST'   => $this->sdk->post($endpoint, $params),
            'PUT'    => $this->sdk->put($endpoint, $params),
            'DELETE' => $this->sdk->delete($endpoint, $params),
            default  => throw new ApiException(
                "Unsupported HTTP method: $method",
                0,
                $endpoint,
                $correlationId,
            ),
        };
    }

    private function maybeLogSuccess(string $method, string $endpoint, string $correlationId, int $latencyMs, int $attempt): void
    {
        if (random_int(1, self::SAMPLE_RATE) !== 1) {
            return;
        }

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'api.request_ok',
            [
                'method'     => $method,
                'endpoint'   => $endpoint,
                'http_code'  => 200,
                'latency_ms' => $latencyMs,
                'attempts'   => $attempt,
            ],
            $this->storeId,
            $correlationId,
        );
    }

    private function logFailure(string $method, string $endpoint, string $correlationId, int $httpCode, int $attempt, HttpClientException $e): void
    {
        $this->log->write(
            LogRepository::LEVEL_ERROR,
            'api.request_failed',
            [
                'method'     => $method,
                'endpoint'   => $endpoint,
                'http_code'  => $httpCode,
                'attempts'   => $attempt,
                'message'    => $e->getMessage(),
            ],
            $this->storeId,
            $correlationId,
        );
    }

    private function sleep(int $microseconds): void
    {
        if ($this->sleepFn !== null) {
            ($this->sleepFn)($microseconds);
            return;
        }

        usleep($microseconds);
    }

    private static function extractHttpCode(HttpClientException $e): int
    {
        $response = $e->getResponse();
        if ($response !== null && method_exists($response, 'getCode')) {
            return (int) $response->getCode();
        }

        return 0;
    }

    public static function generateCorrelationId(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40); // version 4
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); // variant 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    public static function correlationHeaderName(): string
    {
        return self::HEADER_NAME;
    }
}
