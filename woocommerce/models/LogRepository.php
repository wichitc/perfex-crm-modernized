<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

/**
 * Structured-log table at `tblwoocommerce_log`. Used by every service
 * that needs to record context for support tickets — every API call,
 * every cron tick, every conversion. Rows are queryable by store /
 * level / event / time / correlation_id from the Logs UI (T6.11).
 */
class LogRepository extends BaseRepository
{
    public const LEVEL_INFO  = 'info';
    public const LEVEL_WARN  = 'warn';
    public const LEVEL_ERROR = 'error';

    private const VALID_LEVELS = [self::LEVEL_INFO, self::LEVEL_WARN, self::LEVEL_ERROR];

    /** Default retention per REBUILD_SPEC §15.3 (overridable by the cron). */
    public const DEFAULT_RETENTION_DAYS = 30;

    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, $tablePrefix . 'woocommerce_log');
    }

    /**
     * @param array<string, mixed> $context
     * @return int the new row id
     */
    public function write(
        string $level,
        string $event,
        array $context,
        ?int $storeId = null,
        string $correlationId = ''
    ): int {
        if (! in_array($level, self::VALID_LEVELS, true)) {
            $level = self::LEVEL_INFO;
        }

        return $this->insert([
            'store_id'       => $storeId,
            'level'          => $level,
            'event'          => $event,
            'context_json'   => $context === [] ? null : json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'correlation_id' => $correlationId,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param array<string, mixed> $filters
     *     Recognised keys: store_id, level, event, correlation_id, since (Y-m-d H:i:s), until.
     * @return list<array<string, mixed>>
     */
    public function query(array $filters, int $page, int $perPage): array
    {
        $page    = max(1, $page);
        $perPage = max(1, $perPage);

        $simple = array_intersect_key($filters, array_flip([
            'store_id', 'level', 'event', 'correlation_id',
        ]));

        $criteria = $simple;
        $rows = []; // declared so the static analyser sees it

        // Range filters need where('created_at >=', ...) etc. — apply them
        // by hand because BaseRepository's applyCriteria only does equality.
        if (isset($filters['since'])) {
            $this->db->where('created_at >=', (string) $filters['since']);
        }
        if (isset($filters['until'])) {
            $this->db->where('created_at <=', (string) $filters['until']);
        }

        $this->db->order_by('created_at', 'DESC');

        return $this->all($criteria, $perPage, ($page - 1) * $perPage);
    }

    public function prune(int $days = self::DEFAULT_RETENTION_DAYS): int
    {
        $cutoff = date('Y-m-d H:i:s', time() - max(1, $days) * 86400);

        $this->db->where('created_at <', $cutoff)->delete($this->table);

        return (int) $this->db->affected_rows();
    }
}
