<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

/**
 * `tblwoocommerce_webhook_log` — every inbound delivery is recorded
 * here. The unique key on `delivery_id` (migration 240) makes the
 * `seenDeliveryId()` lookup the gate for at-least-once dedup.
 *
 * Spec refs: §7.1.8, §13.2.
 */
class WebhookLogRepository extends BaseRepository
{
    /** Default retention for webhook delivery rows (24h matches §13.2). */
    public const DEFAULT_RETENTION_DAYS = 1;

    public function __construct(object $db, string $tablePrefix = 'tbl')
    {
        parent::__construct($db, $tablePrefix . 'woocommerce_webhook_log');
    }

    /**
     * Stamp a delivery as received but not yet processed. The unique
     * key on `delivery_id` makes the second concurrent call fail at
     * the DB layer — callers should treat that as "duplicate, skip".
     */
    public function recordReceived(
        int $storeId,
        string $topic,
        string $resource,
        ?int $wooId,
        string $deliveryId,
        bool $signatureOk,
        ?string $payloadHash
    ): int {
        return $this->insert([
            'store_id'      => $storeId,
            'topic'         => $topic,
            'resource'      => $resource,
            'woo_id'        => $wooId,
            'delivery_id'   => $deliveryId,
            'received_at'   => date('Y-m-d H:i:s'),
            'signature_ok'  => $signatureOk ? 1 : 0,
            'processed'     => 0,
            'payload_hash'  => $payloadHash,
        ]);
    }

    /**
     * Mark a previously-received delivery as processed (or as failed,
     * if `$error` is set).
     */
    public function recordProcessed(string $deliveryId, ?string $error = null): bool
    {
        $this->db->where('delivery_id', $deliveryId)->update($this->table, [
            'processed' => $error === null ? 1 : 0,
            'error'     => $error,
        ]);

        return (int) $this->db->affected_rows() > 0;
    }

    public function seenDeliveryId(string $deliveryId): bool
    {
        $row = $this->db->select('id')
            ->where('delivery_id', $deliveryId)
            ->limit(1)
            ->get($this->table)
            ->row_array();

        return is_array($row) && $row !== [];
    }

    public function prune(int $days = self::DEFAULT_RETENTION_DAYS): int
    {
        $cutoff = date('Y-m-d H:i:s', time() - max(1, $days) * 86400);

        $this->db->where('received_at <', $cutoff)->delete($this->table);

        return (int) $this->db->affected_rows();
    }
}
