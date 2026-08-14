<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * WooCommerce module v3 — install / activation entry point.
 *
 * 1. Pre-flight: refuse activation on Perfex < 3.4.1 or PHP < 8.0 (T0.6).
 * 2. Apply install.sql — full v3 schema + idempotent core-table column adds.
 * 3. Migration runner — bumps tblmodules.installed_version for upgraders.
 */

require_once __DIR__ . '/helpers/preflight_helper.php';

$perfexVersion = function_exists('get_app_version') ? (string) get_app_version() : '0.0.0';
$preflight     = woocommerce_preflight($perfexVersion, PHP_VERSION_ID, PHP_VERSION);

if (! $preflight['ok']) {
    $message = woocommerce_preflight_format_message($preflight);

    if (function_exists('log_activity')) {
        log_activity('WooCommerce module pre-flight failed: ' . $message);
    }

    if (function_exists('set_alert')) {
        set_alert('danger', $message);
    }

    if (function_exists('redirect') && function_exists('admin_url')) {
        redirect(admin_url('modules'));
    }

    exit($message);
}

if (function_exists('get_instance')) {
    $CI = &get_instance();

    /*
     * Apply install.sql. Statements are split on `;` at end-of-line and
     * dispatched one-by-one because CodeIgniter's $CI->db->query() rejects
     * multi-statement input. Comments (`--`) and blank lines are stripped.
     * `__PFX__` is replaced with db_prefix() so the file stays portable
     * across tenants with custom prefixes.
     */
    $sqlPath = __DIR__ . '/install.sql';
    if (is_readable($sqlPath)) {
        $raw = (string) file_get_contents($sqlPath);
        $raw = str_replace('__PFX__', db_prefix(), $raw);

        $statements = [];
        $buffer     = '';
        foreach (preg_split('/\R/', $raw) ?: [] as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '--')) {
                continue;
            }

            $buffer .= $line . "\n";

            if (substr(rtrim($line), -1) === ';') {
                $statement = trim(rtrim(trim($buffer), ';'));
                if ($statement !== '') {
                    $statements[] = $statement;
                }
                $buffer = '';
            }
        }

        if (trim($buffer) !== '') {
            $statements[] = trim(rtrim(trim($buffer), ';'));
        }

        foreach ($statements as $statement) {
            try {
                $CI->db->query($statement);
            } catch (\Throwable $e) {
                if (function_exists('log_activity')) {
                    log_activity('WooCommerce install.sql failed: '
                        . $e->getMessage() . ' [' . substr($statement, 0, 120) . '...]');
                }
            }
        }
    }

    /*
     * Run migrations after install.sql so tblmodules.installed_version
     * advances correctly and any tenant-specific migrations beyond the
     * baseline schema still apply.
     */
    if (isset($CI->app_modules) && method_exists($CI->app_modules, 'upgrade_database')) {
        $migrationResult = $CI->app_modules->upgrade_database(WOOCOMMERCE_MODULE_NAME);

        if ($migrationResult !== true && function_exists('log_activity')) {
            log_activity('WooCommerce module migration failed: ' . (string) $migrationResult);
        }
    }
}
