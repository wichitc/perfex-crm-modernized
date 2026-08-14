<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use InvalidArgumentException;
use WooCommerce\Repositories\CustomerFieldMappingRepository;
use WooCommerce\Repositories\FieldMappingRepository;
use WooCommerce\Repositories\OrderFieldMappingRepository;
use WooCommerce\Repositories\ProductFieldMappingRepository;

/**
 * Thin pure-logic seam behind the Stores controller's mapping
 * endpoints. Routes (entity → repo) and delegates to the override /
 * revert primitives the repos already expose (T1.7), so the
 * controller stays small and the routing logic is unit-testable.
 *
 * Spec refs: §4.4, §5.5, §11.1.
 */
final class MappingService
{
    public function __construct(
        private CustomerFieldMappingRepository $customerRepo,
        private ProductFieldMappingRepository  $productRepo,
        private OrderFieldMappingRepository    $orderRepo,
    ) {
    }

    /**
     * @param array<string, mixed> $newValues The wc_field, perfex_field,
     *     is_required, default_value the override row should carry.
     * @return int The id of the new override row.
     */
    public function override(int $storeId, string $entity, string $originalWcField, string $originalPerfexField, array $newValues): int
    {
        return $this->repoFor($entity)
            ->overridePredefinedMapping($storeId, $originalWcField, $originalPerfexField, $newValues);
    }

    public function revert(int $storeId, string $entity, string $originalWcField, string $originalPerfexField): bool
    {
        return $this->repoFor($entity)
            ->removeOverride($storeId, $originalWcField, $originalPerfexField);
    }

    private function repoFor(string $entity): FieldMappingRepository
    {
        return match ($entity) {
            'customer', 'contact' => $this->customerRepo,
            'product'             => $this->productRepo,
            'order'               => $this->orderRepo,
            default               => throw new InvalidArgumentException("Unknown mapping entity: $entity"),
        };
    }
}
