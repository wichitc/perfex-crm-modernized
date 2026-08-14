<?php

declare(strict_types=1);

namespace WooCommerce\Services;

class ProductApiClient extends BaseApiClient
{
    public function getByWooId(int $id): mixed
    {
        return $this->makeRequest('GET', "products/$id");
    }

    /** @param array<string, mixed> $params */
    public function getAll(array $params = []): mixed
    {
        return $this->makeRequest('GET', 'products', $params);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): mixed
    {
        return $this->makeRequest('POST', 'products', $data);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): mixed
    {
        return $this->makeRequest('PUT', "products/$id", $data);
    }

    public function delete(int $id, bool $force = true): mixed
    {
        return $this->makeRequest('DELETE', "products/$id", ['force' => $force]);
    }
}
