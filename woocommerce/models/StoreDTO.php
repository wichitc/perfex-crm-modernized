<?php

declare(strict_types=1);

namespace WooCommerce\Repositories;

/**
 * Immutable view of a single `woocommerce_stores` row with the key /
 * secret / webhook_secret already decrypted. The repository (`StoresRepository`)
 * owns the encrypt-on-write / decrypt-on-read transitions; consumers
 * see plaintext through this DTO and never reach into the raw row.
 *
 * `__toString()` masks every secret so a `log_activity($store)` call
 * cannot leak credentials to the audit log. Audit-friendly default.
 */
final class StoreDTO
{
    public function __construct(
        public readonly ?int    $storeId,
        public readonly string  $name,
        public readonly string  $url,
        public readonly string  $key,
        public readonly string  $secret,
        public readonly ?string $webhookSecret = null,
        public readonly bool    $verifySsl     = true,
        public readonly bool    $isActive      = true,
        public readonly int     $pagesPerTick  = 3,
        public readonly bool    $autoConvertCustomer = false,
        public readonly bool    $autoConvertProduct  = false,
        public readonly bool    $autoConvertOrder    = false,
        public readonly ?string $autoInvoiceStatuses = null,
        public readonly bool    $queryAuth     = false,
        public readonly int     $productPage   = 1,
        public readonly int     $orderPage     = 1,
        public readonly int     $customerPage  = 1,
        public readonly ?int    $woocommercePaymentModeId = null,
        public readonly ?string $dateCreated   = null,
        public readonly ?string $dateModified  = null,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'StoreDTO(id=%s, name=%s, url=%s, key=%s, secret=%s, webhook_secret=%s)',
            $this->storeId === null ? '<new>' : (string) $this->storeId,
            $this->name,
            $this->url,
            self::mask($this->key),
            self::mask($this->secret),
            $this->webhookSecret === null ? '<unset>' : self::mask($this->webhookSecret),
        );
    }

    /**
     * Returns a copy with one or more fields replaced. Useful for
     * "edit just the cursors" or "rotate the webhook_secret" flows.
     *
     * @param array<string, mixed> $changes
     */
    public function withChanges(array $changes): self
    {
        $defaults = [
            'storeId'                  => $this->storeId,
            'name'                     => $this->name,
            'url'                      => $this->url,
            'key'                      => $this->key,
            'secret'                   => $this->secret,
            'webhookSecret'            => $this->webhookSecret,
            'verifySsl'                => $this->verifySsl,
            'isActive'                 => $this->isActive,
            'pagesPerTick'             => $this->pagesPerTick,
            'autoConvertCustomer'      => $this->autoConvertCustomer,
            'autoConvertProduct'       => $this->autoConvertProduct,
            'autoConvertOrder'         => $this->autoConvertOrder,
            'autoInvoiceStatuses'      => $this->autoInvoiceStatuses,
            'queryAuth'                => $this->queryAuth,
            'productPage'              => $this->productPage,
            'orderPage'                => $this->orderPage,
            'customerPage'             => $this->customerPage,
            'woocommercePaymentModeId' => $this->woocommercePaymentModeId,
            'dateCreated'              => $this->dateCreated,
            'dateModified'             => $this->dateModified,
        ];

        $merged = array_merge($defaults, $changes);

        return new self(
            $merged['storeId'],
            (string) $merged['name'],
            (string) $merged['url'],
            (string) $merged['key'],
            (string) $merged['secret'],
            $merged['webhookSecret'],
            (bool)  $merged['verifySsl'],
            (bool)  $merged['isActive'],
            (int)   $merged['pagesPerTick'],
            (bool)  $merged['autoConvertCustomer'],
            (bool)  $merged['autoConvertProduct'],
            (bool)  $merged['autoConvertOrder'],
            $merged['autoInvoiceStatuses'],
            (bool)  $merged['queryAuth'],
            (int)   $merged['productPage'],
            (int)   $merged['orderPage'],
            (int)   $merged['customerPage'],
            $merged['woocommercePaymentModeId'],
            $merged['dateCreated'],
            $merged['dateModified'],
        );
    }

    private static function mask(string $value): string
    {
        $len = strlen($value);
        if ($len === 0) {
            return '<empty>';
        }
        if ($len <= 4) {
            return str_repeat('*', $len);
        }
        return substr($value, 0, 2) . str_repeat('*', $len - 4) . substr($value, -2);
    }
}
