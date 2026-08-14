<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\LogRepository;

/**
 * Anonymous opt-in telemetry (T7.7).
 *
 * Reports a tiny, PII-free fingerprint of the install so Boxvibe
 * can answer "how long is the median time-to-first-sync?" (Goal §22.1)
 * without snooping. Off by default; turned on via the setup welcome
 * checkbox which writes `woocommerce_telemetry_opt_in` to `tbloptions`.
 *
 * What's collected (and nothing else):
 *   - module_version
 *   - perfex_version (from get_app_version())
 *   - php_version
 *   - store_count        (how many woocommerce_stores rows exist)
 *   - last_cron_tick     (unix ts of newest woocommerce_log row)
 *   - install_id         (random UUID minted on first send; lets the
 *                         backend dedupe pings without identifying you)
 *
 * What is NEVER collected:
 *   - Store URLs / hostnames
 *   - Consumer keys / secrets / webhook secrets
 *   - Customer / order / product data
 *   - Staff names or emails
 *   - Perfex license / activation key
 *
 * The list above is the complete set. `buildPayload()` is unit-tested
 * to refuse to send anything outside that allow-list.
 *
 * Spec ref: §22.1.
 */
final class Telemetry
{
    public const OPT_IN_OPTION    = 'woocommerce_telemetry_opt_in';
    public const INSTALL_ID_OPTION = 'woocommerce_telemetry_install_id';
    public const ENDPOINT_DEFAULT = 'https://telemetry.boxvibe.com/woocommerce/v1/ping';

    /**
     * Shared `X-Boxvibe-Token` value the receiver authenticates against.
     * Empty in source → not committed to git, not present in dev. The
     * release Makefile substitutes the real value into the staged copy
     * from the BOXVIBE_WOOCOMMERCE_PING_TOKEN env var. When the constant
     * is empty at runtime, `maybeSend()` omits the header (the receiver
     * runs in open mode in that case).
     */
    public const PING_TOKEN_DEFAULT = '';

    /** @var array<int, string> The complete allow-list of payload keys. */
    public const ALLOWED_KEYS = [
        'module_version',
        'perfex_version',
        'php_version',
        'store_count',
        'last_cron_tick',
        'install_id',
    ];

    public function __construct(
        private object $db,
        private LogRepository $log,
        private string $endpoint = self::ENDPOINT_DEFAULT,
        private string $pingToken = self::PING_TOKEN_DEFAULT,
        private string $tablePrefix = 'tbl',
    ) {
    }

    /** Returns true if the admin has opted in. */
    public function isOptedIn(): bool
    {
        $row = $this->db
            ->select('value')
            ->where('name', self::OPT_IN_OPTION)
            ->limit(1)
            ->get($this->tablePrefix . 'options')
            ->row_array();
        return is_array($row) && (string) ($row['value'] ?? '') === '1';
    }

    /**
     * Build the payload. Idempotent + side-effect-free apart from
     * minting + persisting the install_id on first call.
     *
     * @return array<string, scalar>  PII-free, allow-listed.
     */
    public function buildPayload(): array
    {
        $moduleVersion = defined('WOOCOMMERCE_MODULE_VERSION')
            ? (string) WOOCOMMERCE_MODULE_VERSION
            : 'unknown';
        $perfexVersion = function_exists('get_app_version')
            ? (string) get_app_version()
            : 'unknown';

        $storeCount = (int) $this->db->count_all_results($this->tablePrefix . 'woocommerce_stores');

        $latest = $this->db
            ->select_max('created_at', 'mx')
            ->get($this->tablePrefix . 'woocommerce_log')
            ->row_array();
        $lastCronTick = is_array($latest) && ! empty($latest['mx'])
            ? (string) $latest['mx']
            : '';

        $payload = [
            'module_version' => $moduleVersion,
            'perfex_version' => $perfexVersion,
            'php_version'    => PHP_VERSION,
            'store_count'    => $storeCount,
            'last_cron_tick' => $lastCronTick,
            'install_id'     => $this->ensureInstallId(),
        ];

        // Defence-in-depth: refuse to leak anything outside the allow list,
        // even if a future caller decorates the payload before sending.
        foreach (array_keys($payload) as $k) {
            if (! in_array($k, self::ALLOWED_KEYS, true)) {
                unset($payload[$k]);
            }
        }
        return $payload;
    }

    /**
     * POST the payload to the telemetry endpoint. No-op when the admin
     * hasn't opted in. Failures are swallowed + logged at info level
     * so they never surface as a user-facing error.
     */
    public function maybeSend(): void
    {
        if (! $this->isOptedIn()) {
            return;
        }

        $payload = $this->buildPayload();

        $ch = \curl_init($this->endpoint);
        if ($ch === false) {
            return;
        }
        $headers = ['Content-Type: application/json'];
        if ($this->pingToken !== '') {
            $headers[] = 'X-Boxvibe-Token: ' . $this->pingToken;
        }
        \curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);
        $body  = \curl_exec($ch);
        $code  = (int) \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = \curl_error($ch);
        \curl_close($ch);

        $this->log->write(
            LogRepository::LEVEL_INFO,
            'telemetry_sent',
            [
                'http'  => $code,
                'error' => $error !== '' ? $error : null,
            ],
            null,
        );
    }

    private function ensureInstallId(): string
    {
        $row = $this->db
            ->select('value')
            ->where('name', self::INSTALL_ID_OPTION)
            ->limit(1)
            ->get($this->tablePrefix . 'options')
            ->row_array();

        if (is_array($row) && ! empty($row['value'])) {
            return (string) $row['value'];
        }

        // Random 32-hex chars — never derived from any user / store /
        // staff data, so it cannot be reversed to a tenant identity.
        $id = bin2hex(random_bytes(16));
        $this->db->insert($this->tablePrefix . 'options', [
            'name'      => self::INSTALL_ID_OPTION,
            'value'     => $id,
            'autoload'  => 0,
        ]);
        return $id;
    }
}
