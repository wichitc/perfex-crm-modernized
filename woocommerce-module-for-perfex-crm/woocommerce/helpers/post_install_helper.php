<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

use WooCommerce\Libraries\CredentialCipher;
use WooCommerce\Repositories\StoresRepository;

if (! function_exists('woocommerce_post_install')) {
    /**
     * One-shot data-fix routine for tenants upgrading from v2 to v3.
     *
     * Three jobs, all idempotent:
     *   1. Generate `webhook_secret` for any store missing one (a v2 store
     *      reached this state because v2 didn't have HMAC verification).
     *   2. Encrypt `key` / `secret` columns that look like plaintext (no
     *      `enc_v1$` prefix) — the CredentialCipher pass-through means
     *      this can run during normal operation without breaking reads.
     *   3. Default `is_active = 1` for any row that landed with NULL.
     *
     * The function is safe to call on every cron tick or on every page
     * load; the second pass is a no-op because each fix is gated by
     * "does this row still need it?".
     *
     * Returns a stats array so the caller (install.php / a future admin
     * tool) can decide whether to surface the "regenerate webhooks"
     * banner. The banner is fired here too if `set_alert` is available
     * and at least one webhook_secret was generated this pass.
     *
     * @return array{
     *     secrets_generated:int,
     *     creds_encrypted:int,
     *     is_active_set:int,
     *     rows_touched:int
     * }
     */
    function woocommerce_post_install(
        StoresRepository $repo,
        CredentialCipher $cipher
    ): array {
        $stats = [
            'secrets_generated' => 0,
            'creds_encrypted'   => 0,
            'is_active_set'     => 0,
            'rows_touched'      => 0,
        ];

        // BaseRepository::all() returns raw rows — exactly what we need
        // to inspect plaintext-vs-encrypted state on the v2 columns.
        foreach ($repo->all() as $row) {
            $changes = [];

            $rawKey           = (string) ($row['key']    ?? '');
            $rawSecret        = (string) ($row['secret'] ?? '');
            $rawWebhookSecret = $row['webhook_secret'] ?? null;

            if ($rawWebhookSecret === null || $rawWebhookSecret === '') {
                $changes['webhook_secret'] = $cipher->encrypt(bin2hex(random_bytes(32)));
                $stats['secrets_generated']++;
            }

            $credEncrypted = false;
            if ($rawKey !== '' && ! CredentialCipher::isEncrypted($rawKey)) {
                $changes['key'] = $cipher->encrypt($rawKey);
                $credEncrypted  = true;
            }
            if ($rawSecret !== '' && ! CredentialCipher::isEncrypted($rawSecret)) {
                $changes['secret'] = $cipher->encrypt($rawSecret);
                $credEncrypted     = true;
            }
            if ($credEncrypted) {
                $stats['creds_encrypted']++;
            }

            if (! array_key_exists('is_active', $row) || $row['is_active'] === null) {
                $changes['is_active'] = 1;
                $stats['is_active_set']++;
            }

            if ($changes === []) {
                continue;
            }

            $repo->update((int) $row['store_id'], $changes);
            $stats['rows_touched']++;
        }

        if ($stats['secrets_generated'] > 0 && function_exists('set_alert') && function_exists('_l')) {
            set_alert('warning', _l('woocommerce_post_install_regenerate_webhooks'));
        }

        return $stats;
    }
}
