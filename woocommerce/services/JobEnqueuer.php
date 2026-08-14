<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Skeleton interface the WebhookDispatcher uses to enqueue follow-up
 * work (auto-convert order to invoice, auto-import customer, auto-link
 * product to a Perfex sales item). The real implementation lands in
 * T3.5 (`JobQueue`); until then `NullJobEnqueuer` no-ops.
 */
interface JobEnqueuer
{
    public const JOB_CONVERT_ORDER_TO_INVOICE   = 'convert.order_to_invoice';
    public const JOB_CONVERT_CUSTOMER_TO_CLIENT = 'convert.customer_to_client';
    public const JOB_LINK_PRODUCT_TO_ITEM       = 'link.product_to_item';

    /**
     * @param array<string, mixed> $payload
     */
    public function enqueue(string $type, int $storeId, array $payload): void;
}
