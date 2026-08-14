<?php

declare(strict_types=1);

namespace WooCommerce\Exceptions;

/**
 * Thrown by repositories when a primary-key lookup misses.
 */
final class EntityNotFoundException extends WooCommerceException
{
    public static function forIdInTable(int|string $id, string $table): self
    {
        return new self(sprintf('No row in `%s` matches id `%s`.', $table, (string) $id));
    }
}
