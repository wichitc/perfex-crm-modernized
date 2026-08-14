<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use WooCommerce\Repositories\FieldMappingRepository;

/**
 * Resolves the effective field mapping for one (store, entity) pair
 * by merging three sources per REBUILD_SPEC §11.1:
 *
 *   - **Predefined** rows from `config/predefined_mappings.php`.
 *   - **Override** rows in the DB (`is_overridden=1`); each one
 *     replaces the predefined row whose `(wc_field, perfex_field)`
 *     pair matches its `(original_wc_field, original_perfex_field)`.
 *   - **Custom** rows in the DB (`is_predefined=0` and `is_overridden=0`).
 *
 * Resolution is deterministic: predefined-not-overridden → overrides
 * → custom, in that order. The output rows carry the same shape as
 * the DB rows (wc_field, perfex_field, is_required, default_value,
 * plus origin metadata) so the transformer doesn't need to know
 * which path produced each row.
 */
class MappingResolver
{
    /** @var array<string, list<array<string, mixed>>> */
    private array $presets;

    private FieldMappingRepository $customerRepo;
    private FieldMappingRepository $productRepo;
    private FieldMappingRepository $orderRepo;

    /**
     * @param array<string, list<array<string, mixed>>> $presets keyed by entity
     */
    public function __construct(
        array $presets,
        FieldMappingRepository $customerRepo,
        FieldMappingRepository $productRepo,
        FieldMappingRepository $orderRepo
    ) {
        $this->presets      = $presets;
        $this->customerRepo = $customerRepo;
        $this->productRepo  = $productRepo;
        $this->orderRepo    = $orderRepo;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function resolve(int $storeId, string $entity): array
    {
        $repo = $this->repoFor($entity);
        $stored = $repo->getStoreMappings($storeId);

        $overrides = array_values(array_filter(
            $stored,
            static fn(array $r): bool => (int) ($r['is_overridden'] ?? 0) === 1
        ));
        $customs = array_values(array_filter(
            $stored,
            static fn(array $r): bool =>
                (int) ($r['is_overridden'] ?? 0) === 0
                && (int) ($r['is_predefined'] ?? 0) === 0
        ));

        $overriddenPairs = [];
        foreach ($overrides as $o) {
            $key = self::pairKey(
                (string) ($o['original_wc_field']     ?? ''),
                (string) ($o['original_perfex_field'] ?? '')
            );
            $overriddenPairs[$key] = true;
        }

        $predefined = array_values(array_filter(
            $this->presets[$entity] ?? [],
            static function (array $row) use ($overriddenPairs): bool {
                $key = MappingResolver::pairKey(
                    (string) ($row['wc_field']     ?? ''),
                    (string) ($row['perfex_field'] ?? '')
                );
                return ! isset($overriddenPairs[$key]);
            }
        ));

        return array_merge($predefined, $overrides, $customs);
    }

    public static function pairKey(string $wcField, string $perfexField): string
    {
        return $wcField . '|' . $perfexField;
    }

    private function repoFor(string $entity): FieldMappingRepository
    {
        return match ($entity) {
            'customer' => $this->customerRepo,
            'product'  => $this->productRepo,
            'order'    => $this->orderRepo,
            default    => throw new \InvalidArgumentException("Unknown mapping entity: $entity"),
        };
    }
}
