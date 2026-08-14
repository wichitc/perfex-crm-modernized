<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

use InvalidArgumentException;

/**
 * Shared base for the three Woo-side cache tables: orders, products,
 * customers. Each row is keyed by `(store_id, <woo id>)`, where the
 * woo-id column name differs per table:
 *  - woocommerce_orders.order_id
 *  - woocommerce_products.product_id
 *  - woocommerce_customers.woo_customer_id
 *
 * The unique key (store_id, <woo_id>) ships in migration 230, so
 * `upsertByWooId()` can use INSERT … ON DUPLICATE KEY UPDATE for
 * crash-safe idempotency under cron + webhook double delivery.
 *
 * Identifier interpolation: column and table names come from
 * application-controlled constants (the subclass constructor + the
 * keys of the data array passed in by services). They are validated
 * against a strict pattern before being concatenated into SQL — no
 * value from the network ever reaches an identifier.
 */
abstract class WooCacheRepository extends BaseRepository
{
    private const IDENTIFIER_RE = '/^[A-Za-z_][A-Za-z0-9_]*$/';

    protected string $wooIdColumn;

    public function __construct(object $db, string $unprefixedTable, string $wooIdColumn, string $tablePrefix = 'tbl')
    {
        self::assertSafeIdentifier($wooIdColumn, 'woo id column');

        parent::__construct($db, $tablePrefix . $unprefixedTable);
        $this->wooIdColumn = $wooIdColumn;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByWooId(int $storeId, int $wooId): ?array
    {
        return $this->findBy([
            'store_id'          => $storeId,
            $this->wooIdColumn  => $wooId,
        ]);
    }

    /**
     * Insert-or-update keyed by `(store_id, <woo id>)`. Stamps
     * `last_synced_at = NOW()` on every call. The woo id is taken as
     * an explicit argument (rather than expected inside `$data`) so a
     * stale-key bug at the call site can't silently bypass the unique
     * constraint and produce duplicate cache rows.
     *
     * @param array<string, mixed> $data Other columns to write/update.
     * @return int The row's id.
     */
    public function upsertByWooId(int $storeId, int $wooId, array $data): int
    {
        $data['store_id']         = $storeId;
        $data[$this->wooIdColumn] = $wooId;
        $data['last_synced_at']   = date('Y-m-d H:i:s');

        return $this->upsert($data, ['store_id', $this->wooIdColumn]);
    }

    /**
     * Store-scoped, paginated, filtered listing for the admin tables.
     *
     * @param array<string, mixed> $filters
     * @return list<array<string, mixed>>
     */
    public function paginate(int $storeId, array $filters, int $page, int $perPage): array
    {
        $page    = max(1, $page);
        $perPage = max(1, $perPage);

        $criteria = array_merge(['store_id' => $storeId], $filters);

        return $this->all($criteria, $perPage, ($page - 1) * $perPage);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, string> $conflictKeys columns covered by the unique key
     */
    protected function upsert(array $data, array $conflictKeys): int
    {
        if ($data === []) {
            throw new InvalidArgumentException('upsert() requires at least one column.');
        }

        foreach (array_keys($data) as $col) {
            self::assertSafeIdentifier((string) $col, 'data column');
        }
        foreach ($conflictKeys as $col) {
            self::assertSafeIdentifier($col, 'conflict key');
        }

        $cols       = array_keys($data);
        $colList    = implode(', ', array_map(static fn(string $c): string => "`$c`", $cols));
        $valList    = implode(', ', array_fill(0, count($cols), '?'));

        // Update every non-key column to its incoming value on conflict.
        $updateCols = array_values(array_diff($cols, $conflictKeys));
        if ($updateCols === []) {
            // No-op clause is required syntactically; keep the table updated.
            $updateCols = $conflictKeys;
        }
        $updateClause = implode(
            ', ',
            array_map(static fn(string $c): string => "`$c` = VALUES(`$c`)", $updateCols)
        );

        $sql = "INSERT INTO `{$this->table}` ($colList) VALUES ($valList) "
             . "ON DUPLICATE KEY UPDATE $updateClause";

        $this->db->query($sql, array_values($data));

        $insertId = (int) $this->db->insert_id();
        if ($insertId > 0) {
            return $insertId;
        }

        // Update path: insert_id() is 0 on a pure UPDATE; look the row back up.
        $existing = $this->findBy(array_intersect_key($data, array_flip($conflictKeys)));
        return $existing !== null ? (int) $existing[$this->primaryKey] : 0;
    }

    private static function assertSafeIdentifier(string $name, string $what): void
    {
        if (! preg_match(self::IDENTIFIER_RE, $name)) {
            throw new InvalidArgumentException("Unsafe $what: '$name'");
        }
    }
}
