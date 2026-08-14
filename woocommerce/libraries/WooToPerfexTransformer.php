<?php

declare(strict_types=1);

namespace WooCommerce\Libraries;

use WooCommerce\Exceptions\ValidationException;
use WooCommerce\Repositories\LogRepository;

/**
 * Pure function: take (mapping rows, raw Woo payload) and produce a
 * Perfex-shaped associative array. No DB. No HTTP. Every input is an
 * argument; every output is the return value or the (logged) warning
 * channel for unknown destinations.
 *
 * Implements the §11 contract:
 *  - Source resolution via dot-path with `meta_data.<key>` special
 *    case and `[n]` / `[*]` array access (§11.2).
 *  - Coercion table per §11.4 — strings trimmed and NUL-stripped,
 *    booleans → 0/1, list-of-objects → joined name CSV, prices kept
 *    as strings (never `floatval`).
 *  - Validation per §11.5 — required-with-empty raises
 *    `ValidationException`; unknown destination is logged and dropped.
 *
 * The class is instantiable with a `LogRepository` so warnings can
 * cite a `correlation_id` and `store_id` matching the surrounding
 * sync/webhook flow. Callers passing `null` for the log get a no-op
 * warning channel — useful for tests.
 */
final class WooToPerfexTransformer
{
    /**
     * @param array<int, string> $knownDestinations The Perfex columns
     *     the caller considers valid for this entity. Anything else
     *     triggers a `mapping_unknown_destination` warning. Pass an
     *     empty array to disable destination validation.
     */
    public function __construct(
        private ?LogRepository $log = null,
        private array $knownDestinations = [],
    ) {
    }

    /**
     * @param list<array<string, mixed>> $mappings Each row carries
     *     `wc_field`, `perfex_field`, `is_required`, `default_value`.
     * @param array<string, mixed>       $payload  Raw Woo response object,
     *     decoded as an associative array.
     * @param array<string, mixed>       $context  Optional log/correlation
     *     bag — `store_id`, `correlation_id`, `entity`.
     *
     * @return array<string, mixed> Perfex-shaped output, keyed by `perfex_field`.
     *
     * @throws ValidationException When at least one required mapping
     *                             resolves to empty AND has no default —
     *                             the caller is expected to abort the
     *                             entity creation per §11.5.
     */
    public function transform(string $entity, array $mappings, array $payload, array $context = []): array
    {
        $out          = [];
        $missing      = [];
        $storeId      = isset($context['store_id']) ? (int) $context['store_id'] : null;
        $correlationId = (string) ($context['correlation_id'] ?? '');

        foreach ($mappings as $row) {
            $wcField     = (string) ($row['wc_field']      ?? '');
            $perfexField = (string) ($row['perfex_field']  ?? '');
            $required    = (int) ($row['is_required']      ?? 0) === 1;
            $default     = (string) ($row['default_value'] ?? '');

            if ($wcField === '' || $perfexField === '') {
                continue;
            }

            $value = self::resolveDotPath($payload, $wcField);

            if (self::isEmpty($value)) {
                if ($default !== '') {
                    $value = $default;
                } elseif ($required) {
                    $missing[] = $wcField . ' → ' . $perfexField;
                    continue;
                } else {
                    continue;
                }
            }

            if ($this->knownDestinations !== [] && ! in_array($perfexField, $this->knownDestinations, true)) {
                $this->log?->write(
                    LogRepository::LEVEL_WARN,
                    'mapping_unknown_destination',
                    [
                        'entity'      => $entity,
                        'wc_field'    => $wcField,
                        'perfex_field' => $perfexField,
                    ],
                    $storeId,
                    $correlationId,
                );
                continue;
            }

            $out[$perfexField] = self::coerce($value, $perfexField);
        }

        if ($missing !== []) {
            $this->log?->write(
                LogRepository::LEVEL_WARN,
                'mapping_required_missing',
                [
                    'entity'  => $entity,
                    'missing' => $missing,
                ],
                $storeId,
                $correlationId,
            );

            throw new ValidationException(
                sprintf('Required mapping(s) have empty values: %s', implode(', ', $missing)),
                $missing,
            );
        }

        return $out;
    }

    // -------------------------------------------------------------------------
    //                            dot-path resolver
    // -------------------------------------------------------------------------

    /**
     * Resolves a path inside the Woo payload:
     *   - `email`                → $p['email']
     *   - `billing.address_1`    → $p['billing']['address_1']
     *   - `images[0].src`        → $p['images'][0]['src']
     *   - `categories[*].name`   → list of all names in $p['categories']
     *   - `meta_data.<key>`      → value of meta entry whose key === <key>
     */
    private static function resolveDotPath(mixed $payload, string $path): mixed
    {
        if ($path === '' || $payload === null) {
            return null;
        }

        if (str_starts_with($path, 'meta_data.')) {
            return self::findInMetaData($payload, substr($path, 10));
        }

        return self::walk($payload, self::tokenize($path));
    }

    /**
     * @return list<array{type:string, name?:string, index?:int}>
     */
    private static function tokenize(string $path): array
    {
        $tokens    = [];
        $remaining = $path;

        while ($remaining !== '') {
            if (preg_match('/^([A-Za-z_][A-Za-z0-9_]*)(.*)$/s', $remaining, $m)) {
                $tokens[]  = ['type' => 'key', 'name' => $m[1]];
                $remaining = $m[2];
                continue;
            }
            if (preg_match('/^\[(\d+|\*)\](.*)$/s', $remaining, $m)) {
                $tokens[]  = $m[1] === '*'
                    ? ['type' => 'wildcard']
                    : ['type' => 'index', 'index' => (int) $m[1]];
                $remaining = $m[2];
                continue;
            }
            if ($remaining[0] === '.') {
                $remaining = substr($remaining, 1);
                continue;
            }
            return []; // malformed path → resolve to null upstream
        }

        return $tokens;
    }

    /**
     * @param list<array{type:string, name?:string, index?:int}> $tokens
     */
    private static function walk(mixed $data, array $tokens): mixed
    {
        if ($tokens === []) {
            return $data;
        }
        if ($data === null) {
            return null;
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            return null;
        }

        $token     = array_shift($tokens);
        $type      = $token['type'];

        if ($type === 'key') {
            $name = $token['name'] ?? '';
            if (! array_key_exists($name, $data)) {
                return null;
            }
            return self::walk($data[$name], $tokens);
        }
        if ($type === 'index') {
            $idx = $token['index'] ?? -1;
            if (! array_key_exists($idx, $data)) {
                return null;
            }
            return self::walk($data[$idx], $tokens);
        }
        if ($type === 'wildcard') {
            $result = [];
            foreach ($data as $element) {
                $sub = self::walk($element, $tokens);
                if ($sub !== null) {
                    $result[] = $sub;
                }
            }
            return $result;
        }

        return null;
    }

    /**
     * Woo's `meta_data` is `[{key: "...", value: "..."}, ...]` — find
     * the matching entry's `value`, or null when absent.
     */
    private static function findInMetaData(mixed $payload, string $key): mixed
    {
        if (is_object($payload)) {
            $payload = (array) $payload;
        }
        if (! is_array($payload)) {
            return null;
        }

        $meta = $payload['meta_data'] ?? null;
        if (! is_array($meta)) {
            return null;
        }

        foreach ($meta as $entry) {
            if (is_object($entry)) {
                $entry = (array) $entry;
            }
            if (is_array($entry) && (string) ($entry['key'] ?? '') === $key) {
                return $entry['value'] ?? null;
            }
        }
        return null;
    }

    // -------------------------------------------------------------------------
    //                                coercion
    // -------------------------------------------------------------------------

    private static function coerce(mixed $value, string $perfexField): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        if (is_array($value)) {
            return self::coerceArray($value);
        }

        if (is_string($value)) {
            // §11.4 string row: trim, drop NUL bytes. Length clamp is
            // the caller's job — they know the destination column width.
            return str_replace("\0", '', trim($value));
        }

        if (is_int($value) || is_float($value)) {
            // Stringify numerics so accounting fields stay precise.
            // Per §11.4: "preserve string, never floatval() for storage".
            return is_float($value) ? rtrim(rtrim(number_format($value, 8, '.', ''), '0'), '.') : (string) $value;
        }

        return $value;
    }

    /**
     * Coerce an array value:
     *   - list of scalars            → join with `, `
     *   - list of {name|title|slug}  → extract names + join
     *   - other shapes               → JSON encode (for meta_data fall-throughs)
     *
     * @param array<int|string, mixed> $value
     */
    private static function coerceArray(array $value): mixed
    {
        if ($value === []) {
            return '';
        }

        $allScalar = true;
        $allNamed  = true;

        foreach ($value as $item) {
            if (is_object($item)) {
                $item = (array) $item;
            }

            if (is_scalar($item) || $item === null) {
                $allNamed = false;
            } elseif (is_array($item)) {
                $allScalar = false;
                if (! isset($item['name']) && ! isset($item['title']) && ! isset($item['slug'])) {
                    $allNamed = false;
                }
            } else {
                $allScalar = false;
                $allNamed  = false;
            }
        }

        if ($allScalar) {
            $strings = [];
            foreach ($value as $item) {
                if ($item === null) {
                    continue;
                }
                if (is_bool($item)) {
                    $strings[] = $item ? '1' : '0';
                } else {
                    $strings[] = (string) $item;
                }
            }
            return implode(', ', $strings);
        }

        if ($allNamed) {
            $names = [];
            foreach ($value as $item) {
                if (is_object($item)) {
                    $item = (array) $item;
                }
                if (is_array($item)) {
                    $names[] = (string) ($item['name'] ?? $item['title'] ?? $item['slug'] ?? '');
                }
            }
            return implode(', ', array_filter($names, static fn(string $n): bool => $n !== ''));
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private static function isEmpty(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }
        if (is_string($value)) {
            return trim($value) === '';
        }
        if (is_array($value)) {
            return $value === [];
        }
        return false;
    }
}
