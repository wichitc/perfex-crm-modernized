<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Customer endpoints. Note: **no `create()`** — Perfex never creates
 * Woo customers in v1 per spec §8.1.
 */
class CustomerApiClient extends BaseApiClient
{
    public function getByWooId(int $id): mixed
    {
        return $this->makeRequest('GET', "customers/$id");
    }

    /** @param array<string, mixed> $params */
    public function getAll(array $params = []): mixed
    {
        return $this->makeRequest('GET', 'customers', $params);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): mixed
    {
        return $this->makeRequest('PUT', "customers/$id", $data);
    }

    public function delete(int $id, bool $force = true, ?int $reassign = null): mixed
    {
        $params = ['force' => $force];
        if ($reassign !== null) {
            $params['reassign'] = $reassign;
        }
        return $this->makeRequest('DELETE', "customers/$id", $params);
    }
}
