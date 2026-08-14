<?php

declare(strict_types=1);

namespace WooCommerce\Exceptions;

/**
 * Thrown when a transformer / mapping / payload fails validation
 * before the work would have a side effect. Carries the offending
 * field path so the admin UI can highlight the right input.
 */
final class ValidationException extends WooCommerceException
{
    /** @var array<int, string> */
    public readonly array $fieldPaths;

    /**
     * @param array<int, string> $fieldPaths
     */
    public function __construct(string $message, array $fieldPaths = [])
    {
        parent::__construct($message);
        $this->fieldPaths = $fieldPaths;
    }
}
