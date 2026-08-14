<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

use WooCommerce\Exceptions\EntityNotFoundException;

/**
 * Single gate to the database for every entity repository.
 *
 * Every CRUD verb routes through CodeIgniter's `$db->where()` /
 * `->insert()` / `->update()` / `->delete()` so callers never build
 * SQL with string interpolation. Concrete repositories extend this
 * class and add table-specific methods that compose the same
 * primitives.
 *
 * The constructor takes `$db` as `object` rather than a class
 * type-hint because CI3 doesn't expose an interface for the QB; the
 * actual runtime type is `CI_DB_query_builder`. Tests stub the same
 * surface with a PHPUnit mock so unit tests don't need a live DB.
 */
abstract class BaseRepository
{
    /** @phpstan-var object */
    protected object $db;
    protected string $table;
    protected string $primaryKey;

    public function __construct(object $db, string $table, string $primaryKey = 'id')
    {
        $this->db         = $db;
        $this->table      = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Expose the underlying CI DB driver for callers that need to step
     * outside the repo's primary table (e.g. cross-table writes during
     * auto-convert). Keeps callers from having to be wired with their
     * own \$db reference.
     */
    public function db(): object
    {
        return $this->db;
    }

    /**
     * Look up a single row by primary key.
     *
     * @return array<string, mixed>
     * @throws EntityNotFoundException If no row matches.
     */
    public function find(int|string $id): array
    {
        $row = $this->db->where($this->primaryKey, $id)
            ->get($this->table)
            ->row_array();

        if (! is_array($row) || $row === []) {
            throw EntityNotFoundException::forIdInTable($id, $this->table);
        }

        return $row;
    }

    /**
     * Single-row lookup by arbitrary criteria. Returns null on miss
     * (use `find()` when a miss is exceptional).
     *
     * @param array<string, mixed> $criteria
     * @return array<string, mixed>|null
     */
    public function findBy(array $criteria): ?array
    {
        $this->applyCriteria($criteria);

        $row = $this->db->limit(1)
            ->get($this->table)
            ->row_array();

        return is_array($row) && $row !== [] ? $row : null;
    }

    /**
     * Filtered, paginated multi-row lookup.
     *
     * @param array<string, mixed> $criteria
     * @return list<array<string, mixed>>
     */
    public function all(array $criteria = [], ?int $limit = null, ?int $offset = null): array
    {
        $this->applyCriteria($criteria);

        if ($limit !== null) {
            $this->db->limit($limit, $offset ?? 0);
        }

        $rows = $this->db->get($this->table)->result_array();

        return is_array($rows) ? array_values($rows) : [];
    }

    /**
     * @param array<string, mixed> $data
     * @return int The new row's id.
     */
    public function insert(array $data): int
    {
        $this->db->insert($this->table, $data);

        return (int) $this->db->insert_id();
    }

    /**
     * @param array<string, mixed> $data
     * @return bool true when at least one row was changed.
     */
    public function update(int|string $id, array $data): bool
    {
        $this->db->where($this->primaryKey, $id)
            ->update($this->table, $data);

        return (int) $this->db->affected_rows() > 0;
    }

    public function delete(int|string $id): bool
    {
        $this->db->where($this->primaryKey, $id)
            ->delete($this->table);

        return (int) $this->db->affected_rows() > 0;
    }

    /**
     * @param array<string, mixed> $criteria
     */
    public function count(array $criteria = []): int
    {
        $this->applyCriteria($criteria);

        return (int) $this->db->count_all_results($this->table);
    }

    /**
     * @param array<string, mixed> $criteria
     */
    protected function applyCriteria(array $criteria): void
    {
        foreach ($criteria as $key => $value) {
            if (is_array($value)) {
                $this->db->where_in($key, $value);
            } else {
                $this->db->where($key, $value);
            }
        }
    }
}
