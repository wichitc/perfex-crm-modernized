<?php

declare(strict_types=1);

namespace WooCommerce\Services;

/**
 * Concrete `ClientGateway` against Perfex's tblclients + tblcontacts
 * tables. Wires the guest-import flow into the same rows Perfex's
 * built-in client UI reads from, so a Woo guest looks like any other
 * client once imported.
 *
 * Spec refs: §4A.1, §7.2.
 */
final class PerfexClientGateway implements ClientGateway
{
    /**
     * @param object $db CI_DB_query_builder; typed as `object` because
     *                   CodeIgniter's class isn't autoloaded for tests.
     */
    public function __construct(
        private object $db,
        private string $tablePrefix = 'tbl',
    ) {
    }

    public function findGuestByEmail(int $storeId, string $email): ?int
    {
        $row = $this->db
            ->select($this->tablePrefix . 'clients.userid')
            ->from($this->tablePrefix . 'clients')
            ->join(
                $this->tablePrefix . 'contacts',
                $this->tablePrefix . 'contacts.userid = ' . $this->tablePrefix . 'clients.userid',
                'left'
            )
            ->where($this->tablePrefix . 'clients.store_id', $storeId)
            ->where($this->tablePrefix . 'clients.is_guest', 1)
            ->where($this->tablePrefix . 'contacts.email', $email)
            ->limit(1)
            ->get()
            ->row_array();

        return is_array($row) && isset($row['userid']) ? (int) $row['userid'] : null;
    }

    public function findGuestByNameZip(int $storeId, string $firstName, string $lastName, string $zip): ?int
    {
        $row = $this->db
            ->select('userid')
            ->where('store_id', $storeId)
            ->where('is_guest', 1)
            ->where('zip', $zip)
            ->limit(1)
            ->get($this->tablePrefix . 'clients')
            ->row_array();

        // Name match on the contacts side — Perfex stores the human
        // name on tblcontacts, not tblclients.company. The triple
        // (zip + first + last) is rare enough that this two-step
        // lookup performs fine on realistic tenants.
        if (! is_array($row)) {
            return null;
        }
        $clientId = (int) $row['userid'];

        $contact = $this->db
            ->where('userid', $clientId)
            ->where('firstname', $firstName)
            ->where('lastname',  $lastName)
            ->where('is_primary', 1)
            ->limit(1)
            ->get($this->tablePrefix . 'contacts')
            ->row_array();

        return is_array($contact) ? $clientId : null;
    }

    public function createGuest(int $storeId, array $data): int
    {
        // Don't include columns Perfex's clients table doesn't have —
        // is_guest + store_id were added by our migrations; the rest
        // are core columns. `phonenumber` is intentionally also stored
        // on the contacts row by attachPrimaryContact().
        $row = [
            'company'      => (string) ($data['company']     ?? ''),
            'address'      => (string) ($data['address']     ?? ''),
            'city'         => (string) ($data['city']        ?? ''),
            'state'        => (string) ($data['state']       ?? ''),
            'zip'          => (string) ($data['zip']         ?? ''),
            'country'      => (string) ($data['country']     ?? 0),
            'phonenumber'  => (string) ($data['phonenumber'] ?? ''),
            'is_guest'     => 1,
            'store_id'     => $storeId,
            'datecreated'  => date('Y-m-d H:i:s'),
            'active'       => 1,
            'addedfrom'    => function_exists('get_staff_user_id') ? (int) get_staff_user_id() : 0,
        ];

        $this->db->insert($this->tablePrefix . 'clients', $row);
        return (int) $this->db->insert_id();
    }

    public function attachPrimaryContact(int $clientId, array $data): void
    {
        $this->db->insert($this->tablePrefix . 'contacts', [
            'userid'      => $clientId,
            'firstname'   => (string) ($data['firstname']   ?? ''),
            'lastname'    => (string) ($data['lastname']    ?? ''),
            'email'       => (string) ($data['email']       ?? ''),
            'phonenumber' => (string) ($data['phonenumber'] ?? ''),
            'is_primary'  => 1,
            'active'      => 1,
            'datecreated' => date('Y-m-d H:i:s'),
        ]);
        $contactId = (int) $this->db->insert_id();
        if ($contactId <= 0) {
            return;
        }

        // Default contact-level permissions for auto-imported Woo
        // customers: invoices ONLY. Without this, the new contact
        // can't see anything in the customer area — Perfex's
        // `has_contact_permission('invoices', $contact_id)` returns
        // false for any contact with no permission rows. We stop
        // short of granting estimates/contracts/proposals/support/
        // projects because Woo never surfaces those concepts.
        //
        // Permission ids come from
        // `application/helpers/clients_helper.php::get_contact_permissions()`:
        //   1 → invoices
        //   2 → estimates
        //   3 → contracts
        //   4 → proposals
        //   5 → support
        //   6 → projects
        $this->db->insert($this->tablePrefix . 'contact_permissions', [
            'userid'        => $contactId,    // tblcontact_permissions.userid is the CONTACT id
            'permission_id' => 1,             // invoices
        ]);

        // Mute every email channel except invoice — same defence-in-
        // depth Clients_model::add does when a permission isn't on the
        // list (lines 555-563 of application/models/Clients_model.php).
        $this->db
            ->where('id', $contactId)
            ->update($this->tablePrefix . 'contacts', [
                'invoice_emails'     => 1,
                'estimate_emails'    => 0,
                'credit_note_emails' => 0,
                'contract_emails'    => 0,
                'task_emails'        => 0,
                'project_emails'     => 0,
                'ticket_emails'      => 0,
            ]);
    }
}
