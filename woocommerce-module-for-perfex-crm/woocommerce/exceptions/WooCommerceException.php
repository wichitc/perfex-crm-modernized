<?php

declare(strict_types=1);

namespace WooCommerce\Exceptions;

use RuntimeException;

/**
 * Base class for every exception thrown by the WooCommerce module.
 *
 * Concrete subclasses (`ApiException`, `EntityNotFoundException`,
 * `ValidationException`) carry their own context. Catching this class
 * catches everything the module deliberately throws — useful for the
 * top-of-controller fallback that turns it into a flash + log line.
 */
class WooCommerceException extends RuntimeException
{
}
