<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Services\PaymentModeService;

if (! function_exists('ensureWoocommercePaymentMode')) {
    /**
     * Helper wrapper called from install.php / cron / webhook so any
     * code path that needs the WooCommerce payment-mode id can get one
     * without re-implementing the lookup. Returns the mode id.
     */
    function ensureWoocommercePaymentMode(): int
    {
        if (! function_exists('get_instance')) {
            return 0;
        }

        $CI =& get_instance();
        $service = new PaymentModeService($CI->db);
        $modeId  = $service->ensure();
        $service->cacheOnStores($modeId);

        return $modeId;
    }
}
