<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Key-rotation upgrade story (placeholder).
 *
 * If a deployer ever changes `APP_ENC_KEY`, every encrypted credential
 * in `woocommerce_stores` becomes unreadable. This helper is the
 * documented path to recover:
 *
 *   1. Set the OLD key in env var WOOCOMMERCE_PREVIOUS_ENC_KEY before
 *      starting Perfex.
 *   2. Set the NEW key in APP_ENC_KEY as usual.
 *   3. Run `woocommerce_rotate_credentials()` — it decrypts every row
 *      with the previous key, re-encrypts with the new key, and
 *      transactionally writes them back.
 *   4. Unset WOOCOMMERCE_PREVIOUS_ENC_KEY.
 *
 * Implementation TBD (T8.x). Today this file exists so the upgrade
 * runbook has a stable function name to point at.
 */

if (! function_exists('woocommerce_rotate_credentials')) {
    /**
     * @return array{rotated:int, skipped:int, errors:list<string>}
     */
    function woocommerce_rotate_credentials(): array
    {
        // TODO(T8.x): implement once credential rotation is requested.
        // The shape is fixed so callers can pin against it now.
        return [
            'rotated' => 0,
            'skipped' => 0,
            'errors'  => ['woocommerce_rotate_credentials() is a placeholder; see docs/key_rotation.md (TBD).'],
        ];
    }
}
