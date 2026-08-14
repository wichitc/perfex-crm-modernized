<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use InvalidArgumentException;
use WooCommerce\Exceptions\ValidationException;
use WooCommerce\Repositories\BaseRepository;
use WooCommerce\Repositories\CustomersRepository;
use WooCommerce\Repositories\OrdersRepository;
use WooCommerce\Repositories\ProductsRepository;

/**
 * Dry-runs a proposed mapping set against the most recent cached
 * rows of an entity so the field-mapping editor can warn the admin
 * BEFORE save: "if you save these mappings, rows X / Y / Z would
 * fail validation — fix them or save anyway".
 *
 * Pure of side effects: never writes to the DB; never calls the API.
 *
 * Spec refs: §4A.12.
 */
final class MappingPreflight
{
    public const DEFAULT_SAMPLE_SIZE = 10;

    public function __construct(
        private OrdersRepository    $ordersRepo,
        private ProductsRepository  $productsRepo,
        private CustomersRepository $customersRepo,
        private WooToPerfexTransformer $transformer,
    ) {
    }

    /**
     * @param list<array<string, mixed>> $proposedMappings
     * @return array{
     *     entity: string,
     *     sample_size: int,
     *     ok_count: int,
     *     errors: list<array{row_id:int, woo_id:?int, field_paths:list<string>, message:string}>
     * }
     */
    public function dryRun(int $storeId, string $entity, array $proposedMappings, int $sampleSize = self::DEFAULT_SAMPLE_SIZE): array
    {
        $rows = $this->fetchRecent($storeId, $entity, max(1, $sampleSize));

        $errors  = [];
        $okCount = 0;

        foreach ($rows as $row) {
            $rowId  = (int) ($row['id'] ?? 0);
            $wooId  = self::extractWooId($entity, $row);
            $payload = self::reconstructPayload($entity, $row);

            try {
                $this->transformer->transform($entity, $proposedMappings, $payload, [
                    'store_id' => $storeId,
                    'entity'   => $entity,
                ]);
                $okCount++;
            } catch (ValidationException $e) {
                $errors[] = [
                    'row_id'      => $rowId,
                    'woo_id'      => $wooId,
                    'field_paths' => $e->fieldPaths,
                    'message'     => $e->getMessage(),
                ];
            }
        }

        return [
            'entity'      => $entity,
            'sample_size' => count($rows),
            'ok_count'    => $okCount,
            'errors'      => $errors,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function fetchRecent(int $storeId, string $entity, int $sampleSize): array
    {
        $repo = $this->repoFor($entity);

        // Most recent first — `id DESC` is the cheapest "most recent" we
        // can do without knowing whether `last_synced_at` is populated.
        $rows = $repo->all(['store_id' => $storeId], $sampleSize);

        // BaseRepository::all returns insertion order; for "recent first"
        // we'd want id DESC, but ordering is order_by-driven not on
        // BaseRepository. We sort here to keep the contract simple.
        usort($rows, static fn(array $a, array $b): int => (int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0));

        return array_slice($rows, 0, $sampleSize);
    }

    private function repoFor(string $entity): BaseRepository
    {
        return match ($entity) {
            'order'              => $this->ordersRepo,
            'product'            => $this->productsRepo,
            'customer', 'contact' => $this->customersRepo,
            default              => throw new InvalidArgumentException("Unknown preflight entity: $entity"),
        };
    }

    /** @param array<string, mixed> $row */
    private static function extractWooId(string $entity, array $row): ?int
    {
        $col = match ($entity) {
            'order'              => 'order_id',
            'product'            => 'product_id',
            'customer', 'contact' => 'woo_customer_id',
            default              => null,
        };

        if ($col === null || ! isset($row[$col])) {
            return null;
        }
        return (int) $row[$col];
    }

    /**
     * The cache row is a flat projection of the original Woo payload.
     * For pre-flight we need the same shape the live transformer sees,
     * so we fold the cache columns back into a nested structure that
     * the dot-path resolver can walk. Best-effort: anything that wasn't
     * cached (line_items, meta_data) is simply absent from the dry run,
     * which is the most useful signal — the admin sees their proposed
     * `meta_data.<key>` mapping flag *every* row, exactly when the key
     * is genuinely missing from the source.
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function reconstructPayload(string $entity, array $row): array
    {
        if ($entity === 'order') {
            return [
                'id'           => isset($row['order_id']) ? (int) $row['order_id'] : null,
                'number'       => $row['order_number'] ?? null,
                'customer_id'  => isset($row['customer_id']) ? (int) $row['customer_id'] : 0,
                'status'       => $row['status']        ?? null,
                'currency'     => $row['currency']      ?? null,
                'total'        => $row['total']         ?? null,
                'date_created' => $row['date_created']  ?? null,
                'date_modified' => $row['date_modified'] ?? null,
                'billing'      => [
                    'address_1' => $row['address'] ?? null,
                    'phone'     => $row['phone']   ?? null,
                ],
            ];
        }

        if ($entity === 'product') {
            return [
                'id'           => isset($row['product_id']) ? (int) $row['product_id'] : null,
                'name'         => $row['name']          ?? null,
                'permalink'    => $row['permalink']     ?? null,
                'type'         => $row['type']          ?? null,
                'status'       => $row['status']        ?? null,
                'sku'          => $row['sku']           ?? null,
                'price'        => $row['price']         ?? null,
                'total_sales'  => $row['sales']         ?? null,
                'images'       => isset($row['picture']) && $row['picture'] !== ''
                    ? [['src' => $row['picture']]]
                    : [],
                'date_created'  => $row['date_created']  ?? null,
                'date_modified' => $row['date_modified'] ?? null,
            ];
        }

        // customer / contact
        return [
            'id'         => isset($row['woo_customer_id']) ? (int) $row['woo_customer_id'] : null,
            'email'      => $row['email']      ?? null,
            'first_name' => $row['first_name'] ?? null,
            'last_name'  => $row['last_name']  ?? null,
            'role'       => $row['role']       ?? null,
            'username'   => $row['username']   ?? null,
            'avatar_url' => $row['avatar_url'] ?? null,
            'billing'    => [
                'phone'      => $row['phone']      ?? null,
                'first_name' => $row['first_name'] ?? null,
                'last_name'  => $row['last_name']  ?? null,
            ],
        ];
    }
}
