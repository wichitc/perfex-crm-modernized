<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use RuntimeException;

/**
 * Authenticated encryption for store credentials.
 *
 * Uses AES-256-GCM under OpenSSL. Ciphertexts carry a versioned
 * `enc_v1$` prefix so:
 *  - the post-install data-fix routine (T1.5) can detect plaintext
 *    rows from a v2 install and re-encrypt them in place;
 *  - a future bump to AES-256-OCB (or a key rotation) can ship a new
 *    `enc_v2$` prefix and dual-decrypt during the migration window.
 *
 * The constructor takes the raw application key (`APP_ENC_KEY` in
 * production) and derives a fixed-length 32-byte key via SHA-256 so
 * the cipher works regardless of how the deployer chose to encode
 * `APP_ENC_KEY`.
 */
final class CredentialCipher
{
    public const VERSION    = 'enc_v1';
    private const CIPHER    = 'aes-256-gcm';
    private const IV_LENGTH = 12;
    private const TAG_LENGTH = 16;

    private string $key;

    public function __construct(#[\SensitiveParameter] string $appKey)
    {
        if ($appKey === '') {
            throw new RuntimeException('CredentialCipher requires a non-empty key.');
        }

        // Always derive 32 bytes — accepts an APP_ENC_KEY of any length.
        $this->key = hash('sha256', $appKey, true);
    }

    /**
     * Encrypt a plaintext credential. The returned string is safe to
     * persist as VARCHAR(255).
     */
    public function encrypt(#[\SensitiveParameter] string $plaintext): string
    {
        $iv  = random_bytes(self::IV_LENGTH);
        $tag = '';

        $cipher = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::TAG_LENGTH
        );

        if ($cipher === false) {
            throw new RuntimeException('CredentialCipher encryption failed.');
        }

        return self::VERSION . '$' . base64_encode($iv . $tag . $cipher);
    }

    /**
     * Decrypt a stored credential. If the value isn't recognisably
     * encrypted (no `enc_v1$` prefix) it is treated as legacy plaintext
     * and returned unchanged — the data-fix routine in T1.5 catches
     * those rows and re-encrypts them so this branch eventually
     * disappears.
     */
    public function decrypt(string $stored): string
    {
        if (! self::isEncrypted($stored)) {
            return $stored;
        }

        $payload = base64_decode(substr($stored, strlen(self::VERSION) + 1), true);
        if ($payload === false || strlen($payload) < self::IV_LENGTH + self::TAG_LENGTH) {
            throw new RuntimeException('CredentialCipher: malformed ciphertext.');
        }

        $iv     = substr($payload, 0, self::IV_LENGTH);
        $tag    = substr($payload, self::IV_LENGTH, self::TAG_LENGTH);
        $cipher = substr($payload, self::IV_LENGTH + self::TAG_LENGTH);

        $plain = openssl_decrypt(
            $cipher,
            self::CIPHER,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($plain === false) {
            throw new RuntimeException('CredentialCipher: authentication tag mismatch — wrong key, tampered ciphertext, or corrupted row.');
        }

        return $plain;
    }

    public static function isEncrypted(string $value): bool
    {
        return str_starts_with($value, self::VERSION . '$');
    }
}
