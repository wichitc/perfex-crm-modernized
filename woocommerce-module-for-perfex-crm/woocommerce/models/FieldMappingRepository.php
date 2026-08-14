<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

/**
 * Shared base for the three field-mapping tables. Each subclass binds
 * a specific table; the algorithm is identical otherwise.
 *
 *   - getStoreMappings(int $storeId): all override + custom rows for
 *     a store. Predefined rows live in config/predefined_mappings.php
 *     and are merged in by `MappingResolver`, not by the DB.
 *   - saveMappings(int $storeId, list $rows): atomic replace of the
 *     store's rows (delete-then-insert) so the UI's "save" never
 *     produces a half-written state.
 *   - overridePredefinedMapping(...) clones a predefined row into the
 *     DB tagged `is_overridden=1` with the original pair preserved.
 *   - removeOverride(...) deletes the override; resolution falls back
 *     to the predefined value automatically on the next read.
 */
abstract class FieldMappingRepository extends BaseRepository
{
    public function __construct(object $db, string $unprefixedTable, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, $tablePrefix . $unprefixedTable);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getStoreMappings(int $storeId): array
    {
        return $this->all(['store_id' => $storeId]);
    }

    /**
     * Replace all rows for a store atomically.
     *
     * @param list<array<string, mixed>> $rows
     */
    public function saveMappings(int $storeId, array $rows): void
    {
        $this->db->trans_start();

        $this->db->where('store_id', $storeId)->delete($this->table);

        foreach ($rows as $row) {
            $row['store_id'] = $storeId;
            $this->db->insert($this->table, $row);
        }

        $this->db->trans_complete();
    }

    /**
     * Record a predefined row's override. The original pair is stored
     * so we can spring back to the predefined value on revert.
     *
     * @param array<string, mixed> $newValues
     */
    public function overridePredefinedMapping(
        int $storeId,
        string $wcField,
        string $perfexField,
        array $newValues
    ): int {
        $row = array_merge($newValues, [
            'store_id'              => $storeId,
            'is_predefined'         => 0,
            'is_overridden'         => 1,
            'original_wc_field'     => $wcField,
            'original_perfex_field' => $perfexField,
        ]);

        return $this->insert($row);
    }

    public function removeOverride(int $storeId, string $wcField, string $perfexField): bool
    {
        $this->db->where('store_id', $storeId)
            ->where('original_wc_field', $wcField)
            ->where('original_perfex_field', $perfexField)
            ->where('is_overridden', 1)
            ->delete($this->table);

        return (int) $this->db->affected_rows() > 0;
    }
}
