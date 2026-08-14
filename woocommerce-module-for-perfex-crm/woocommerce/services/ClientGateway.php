<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Abstraction over Perfex's `Clients_model` / `Contacts_model` so the
 * guest-client logic can be unit-tested without a live Perfex tenant.
 *
 * Production wiring uses `PerfexClientGateway` (delegates to the
 * model classes); tests pass an in-memory fake.
 */
interface ClientGateway
{
    /** @return int|null client id, or null on miss */
    public function findGuestByEmail(int $storeId, string $email): ?int;

    /** @return int|null client id, or null on miss */
    public function findGuestByNameZip(int $storeId, string $firstName, string $lastName, string $zip): ?int;

    /**
     * Create a new client tagged is_guest=1 + store_id; return its id.
     *
     * @param array<string, mixed> $data
     */
    public function createGuest(int $storeId, array $data): int;

    /**
     * Attach a primary contact to the new client.
     *
     * @param array<string, mixed> $data
     */
    public function attachPrimaryContact(int $clientId, array $data): void;
}
