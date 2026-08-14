<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use WooCommerce\Repositories\LogRepository;

/**
 * Verifies inbound webhook signatures.
 *
 * Woo signs the raw request body with HMAC-SHA256 keyed on the
 * webhook's secret and sends the result base64-encoded as
 * `X-WC-Webhook-Signature`. We re-derive the signature from the
 * delivered body and `hash_equals()` against the header — constant-
 * time so a guessing attacker can't time-side-channel the bytes.
 *
 * `verifyQueryAuth()` is the v2 fallback, only used when a store has
 * `query_auth=1`. It's strictly less secure (replays trivially), so
 * every successful query-auth verification writes a `warning` log
 * row. In v3 the default for new stores is `query_auth=0`.
 *
 * Spec refs: §13.2 step 3, SEC-002.
 */
final class SignatureVerifier
{
    private LogRepository $log;

    public function __construct(LogRepository $log)
    {
        $this->log = $log;
    }

    /**
     * Constant-time HMAC verification of the raw body against the
     * signature Woo sent in the header.
     *
     * @param string $body       Exact bytes of the inbound request body.
     * @param string $headerSig  Value of `X-WC-Webhook-Signature` (base64-encoded).
     * @param string $secret     The store's webhook signing secret (decrypted).
     */
    public function verify(string $body, string $headerSig, string $secret): bool
    {
        if ($secret === '' || $headerSig === '') {
            return false;
        }

        $expected = base64_encode(hash_hmac('sha256', $body, $secret, true));

        return hash_equals($expected, $headerSig);
    }

    /**
     * Verify the legacy `?key=…&secret=…` fallback. Always succeeds
     * iff the supplied creds match — replays trivially, so callers
     * MUST also verify the request hasn't been seen before
     * (WebhookDeduplicator). Every success writes a warning so
     * support can spot tenants still relying on this path.
     *
     * @param array<string, string|int|bool|null> $query
     */
    public function verifyQueryAuth(array $query, string $key, string $secret, ?int $storeId = null, string $correlationId = ''): bool
    {
        $providedKey    = (string) ($query['consumer_key']    ?? $query['key']    ?? '');
        $providedSecret = (string) ($query['consumer_secret'] ?? $query['secret'] ?? '');

        if ($providedKey === '' || $providedSecret === '') {
            return false;
        }

        $ok = hash_equals($key, $providedKey) && hash_equals($secret, $providedSecret);

        if ($ok) {
            $this->log->write(
                LogRepository::LEVEL_WARN,
                'webhook.query_auth_used',
                ['note' => 'Query-auth fallback in use; HMAC signature is the recommended path.'],
                $storeId,
                $correlationId,
            );
        }

        return $ok;
    }
}
