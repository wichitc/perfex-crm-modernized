<?php

declare(strict_types=1);

namespace WooCommerce\Services;

use WooCommerce\Repositories\LogRepository;

/**
 * §4A.1 — Guest checkout (`customer_id = 0`) must produce a placeholder
 * Perfex client tagged `is_guest=1`. Two guests on the same email
 * collapse into one client. Two guests with no email but matching
 * name+postcode also collapse. Two guests with no email/name/zip get
 * separate clients (no false matches).
 *
 * Fires `after_wc_guest_client_created($clientId, $orderPayload)` on
 * create so extensions can react.
 *
 * Spec refs: §4A.1, US-9.
 */
final class GuestClientFactory
{
    public function __construct(
        private ClientGateway $gateway,
        private ?LogRepository $log = null,
    ) {
    }

    /**
     * @param array<string, mixed> $orderPayload The decoded Woo order body.
     * @return int Existing or newly-created client id.
     */
    public function findOrCreate(array $orderPayload, int $storeId, string $correlationId = ''): int
    {
        $billing = is_array($orderPayload['billing'] ?? null) ? $orderPayload['billing'] : [];

        $email = strtolower(trim((string) ($billing['email'] ?? '')));
        $first = trim((string) ($billing['first_name'] ?? ''));
        $last  = trim((string) ($billing['last_name']  ?? ''));
        $zip   = trim((string) ($billing['postcode']   ?? ''));

        if ($email !== '') {
            $existingId = $this->gateway->findGuestByEmail($storeId, $email);
            if ($existingId !== null && $existingId > 0) {
                return $existingId;
            }
        }

        // Fallback dedup: only useful when we have *all three* signals.
        // Two anonymous guests with no email + no name + no zip must NOT
        // collapse (can't tell them apart) — so we require the full triple.
        if ($email === '' && $first !== '' && $last !== '' && $zip !== '') {
            $existingId = $this->gateway->findGuestByNameZip($storeId, $first, $last, $zip);
            if ($existingId !== null && $existingId > 0) {
                return $existingId;
            }
        }

        $clientId = $this->gateway->createGuest($storeId, [
            'firstname'   => $first,
            'lastname'    => $last,
            'company'     => (string) ($billing['company'] ?? ''),
            'address'     => self::renderAddress($billing),
            'city'        => (string) ($billing['city']     ?? ''),
            'state'       => (string) ($billing['state']    ?? ''),
            'zip'         => $zip,
            'country'     => (string) ($billing['country']  ?? ''),
            'phonenumber' => (string) ($billing['phone']    ?? ''),
            'is_guest'    => 1,
            'store_id'    => $storeId,
        ]);

        $this->gateway->attachPrimaryContact($clientId, [
            'firstname'   => $first,
            'lastname'    => $last,
            'email'       => $email,
            'phonenumber' => (string) ($billing['phone'] ?? ''),
            'is_primary'  => 1,
        ]);

        $this->log?->write(
            LogRepository::LEVEL_INFO,
            'guest_client_created',
            [
                'client_id' => $clientId,
                'has_email' => $email !== '',
                'has_name'  => $first !== '' || $last !== '',
            ],
            $storeId,
            $correlationId,
        );

        if (function_exists('hooks')) {
            hooks()->do_action('after_wc_guest_client_created', [$clientId, $orderPayload]);
        }

        return $clientId;
    }

    /**
     * @param array<string, mixed> $billing
     */
    private static function renderAddress(array $billing): string
    {
        $parts = [];
        foreach (['address_1', 'address_2'] as $k) {
            if (! empty($billing[$k])) {
                $parts[] = (string) $billing[$k];
            }
        }
        return implode(', ', $parts);
    }
}
