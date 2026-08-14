<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

const WOOCOMMERCE_MIN_PERFEX_VERSION = '3.4.1';
const WOOCOMMERCE_MIN_PHP_VERSION_ID = 80000;
const WOOCOMMERCE_MIN_PHP_VERSION    = '8.0.0';

if (! function_exists('woocommerce_preflight')) {
    /**
     * Pure pre-flight check for the WooCommerce module.
     *
     * Returns a structured result rather than calling _l() / die() directly so
     * the function is unit-testable without a live Perfex tenant on the
     * include path. The caller (install.php on activation, woocommerce.php on
     * every load) decides how to surface the failure.
     *
     * @param string $perfexVersion The running Perfex version (`get_app_version()`).
     * @param int    $phpVersionId  Numeric PHP version (`PHP_VERSION_ID`).
     * @param string $phpVersion    Human PHP version string (`PHP_VERSION`).
     *
     * @return array{
     *     ok: bool,
     *     code: ?string,
     *     lang_key: ?string,
     *     lang_args: array<int, string>
     * }
     */
    function woocommerce_preflight(
        string $perfexVersion,
        int $phpVersionId,
        string $phpVersion
    ): array {
        if ($phpVersionId < WOOCOMMERCE_MIN_PHP_VERSION_ID) {
            return [
                'ok'        => false,
                'code'      => 'php_too_old',
                'lang_key'  => 'woocommerce_preflight_php_too_old',
                'lang_args' => [WOOCOMMERCE_MIN_PHP_VERSION, $phpVersion],
            ];
        }

        if (version_compare($perfexVersion, WOOCOMMERCE_MIN_PERFEX_VERSION, '<')) {
            return [
                'ok'        => false,
                'code'      => 'perfex_too_old',
                'lang_key'  => 'woocommerce_preflight_perfex_too_old',
                'lang_args' => [WOOCOMMERCE_MIN_PERFEX_VERSION, $perfexVersion],
            ];
        }

        return [
            'ok'        => true,
            'code'      => null,
            'lang_key'  => null,
            'lang_args' => [],
        ];
    }
}

if (! function_exists('woocommerce_preflight_format_message')) {
    /**
     * Resolve a pre-flight failure into a human-readable message.
     *
     * Pulled out of `woocommerce_preflight()` so the latter stays pure.
     * Falls back to the English template if Perfex's `_l()` is unavailable
     * (e.g. when a check runs before the language helper is loaded).
     *
     * @param array{ok: bool, code: ?string, lang_key: ?string, lang_args: array<int, string>} $result
     */
    function woocommerce_preflight_format_message(array $result): string
    {
        if ($result['ok'] || $result['lang_key'] === null) {
            return '';
        }

        $template = function_exists('_l')
            ? _l($result['lang_key'])
            : woocommerce_preflight_english_fallback($result['lang_key']);

        return vsprintf($template, $result['lang_args']);
    }
}

if (! function_exists('woocommerce_preflight_english_fallback')) {
    /**
     * English-only fallback templates for use before the language helper
     * is wired (preflight runs at the very top of install.php).
     */
    function woocommerce_preflight_english_fallback(string $langKey): string
    {
        $fallbacks = [
            'woocommerce_preflight_php_too_old'    => 'WooCommerce module requires PHP %1$s or newer. Your PHP is %2$s. Please upgrade PHP first.',
            'woocommerce_preflight_perfex_too_old' => 'WooCommerce module requires Perfex CRM %1$s or newer. Your Perfex is %2$s. Please upgrade Perfex first.',
        ];

        return $fallbacks[$langKey] ?? $langKey;
    }
}
