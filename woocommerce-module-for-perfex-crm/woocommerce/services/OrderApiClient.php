<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Order endpoints from spec §8.1. No DB; everything goes through
 * BaseApiClient::makeRequest so retries, error wrapping, logging, and
 * the correlation id are uniform across the API surface.
 */
class OrderApiClient extends BaseApiClient
{
    public function getByWooId(int $id): mixed
    {
        return $this->makeRequest('GET', "orders/$id");
    }

    /**
     * @param array<string, mixed> $params
     */
    public function getAll(array $params = []): mixed
    {
        return $this->makeRequest('GET', 'orders', $params);
    }

    public function updateStatus(int $id, string $status): mixed
    {
        return $this->makeRequest('PUT', "orders/$id", ['status' => $status]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): mixed
    {
        return $this->makeRequest('PUT', "orders/$id", $data);
    }

    public function delete(int $id, bool $force = true): mixed
    {
        return $this->makeRequest('DELETE', "orders/$id", ['force' => $force]);
    }
}
