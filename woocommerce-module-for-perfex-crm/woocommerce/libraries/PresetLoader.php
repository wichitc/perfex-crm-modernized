<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use InvalidArgumentException;
use RuntimeException;
use WooCommerce\Repositories\CustomerFieldMappingRepository;
use WooCommerce\Repositories\OrderFieldMappingRepository;
use WooCommerce\Repositories\ProductFieldMappingRepository;
use WooCommerce\Repositories\FieldMappingRepository;

/**
 * Loads `config/predefined_mappings.php` into a store's mapping table.
 *
 * Idempotent: a `(store_id, wc_field, perfex_field, is_predefined=1)`
 * row that already exists is skipped, so the "Load Preset" button on
 * the field-mapping editor can be clicked twice with no duplicate
 * rows.
 *
 * Spec §4.4 promotes "contact" to a sub-scope of "customer" — preset
 * rows under either key go through `CustomerFieldMappingRepository`;
 * the transformer / converter is what decides which fields land on
 * the client row vs. the primary contact.
 *
 * Spec refs: §4.4, §11.1.
 */
final class PresetLoader
{
    private string $configPath;

    public function __construct(
        private CustomerFieldMappingRepository $customerRepo,
        private ProductFieldMappingRepository  $productRepo,
        private OrderFieldMappingRepository    $orderRepo,
        ?string $configPath = null,
    ) {
        $this->configPath = $configPath ?? __DIR__ . '/../config/predefined_mappings.php';
    }

    /**
     * Insert the predefined rows for `$entity` on store `$storeId`.
     * Returns the number of rows actually inserted (0 on the second
     * pass).
     */
    public function load(int $storeId, string $entity): int
    {
        $repo  = $this->repoFor($entity);
        $rows  = $this->presetRowsFor($entity);
        if ($rows === []) {
            return 0;
        }

        // Index existing predefined rows by (wc_field, perfex_field)
        // so the dedup decision is one lookup per candidate row.
        $existing = $repo->getStoreMappings($storeId);
        $seen     = [];
        foreach ($existing as $row) {
            if ((int) ($row['is_predefined'] ?? 0) !== 1) {
                continue;
            }
            $key = ((string) ($row['wc_field'] ?? '')) . '|' . ((string) ($row['perfex_field'] ?? ''));
            $seen[$key] = true;
        }

        $inserted = 0;
        foreach ($rows as $row) {
            $wc     = (string) ($row['wc_field']     ?? '');
            $perfex = (string) ($row['perfex_field'] ?? '');
            if ($wc === '' || $perfex === '') {
                continue;
            }
            if (isset($seen[$wc . '|' . $perfex])) {
                continue;
            }

            $repo->insert([
                'store_id'      => $storeId,
                'wc_field'      => $wc,
                'perfex_field'  => $perfex,
                'is_required'   => (int) ($row['is_required']   ?? 0),
                'default_value' => (string) ($row['default_value'] ?? ''),
                'is_predefined' => 1,
                'is_overridden' => 0,
            ]);
            $inserted++;
        }

        return $inserted;
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    public function readPresets(): array
    {
        if (! is_file($this->configPath)) {
            throw new RuntimeException("Preset config not found: $this->configPath");
        }

        $data = require $this->configPath;
        if (! is_array($data)) {
            throw new RuntimeException("Preset config did not return an array: $this->configPath");
        }

        /** @var array<string, list<array<string, mixed>>> $data */
        return $data;
    }

    private function repoFor(string $entity): FieldMappingRepository
    {
        return match ($entity) {
            'customer', 'contact' => $this->customerRepo,
            'product'             => $this->productRepo,
            'order'               => $this->orderRepo,
            default               => throw new InvalidArgumentException("Unknown preset entity: $entity"),
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function presetRowsFor(string $entity): array
    {
        $presets = $this->readPresets();
        $rows    = $presets[$entity] ?? [];

        return is_array($rows) ? array_values($rows) : [];
    }
}
