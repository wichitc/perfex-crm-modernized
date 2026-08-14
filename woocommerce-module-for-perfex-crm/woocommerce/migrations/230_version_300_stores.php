<?php

declare(strict_types=1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * v3.0.0 — store columns, cache `last_synced_at`, and idempotency-critical
 * unique keys.
 *
 * Idempotent: every column add is field_exists-guarded; every unique key is
 * INFORMATION_SCHEMA-checked and pre-dedupes by keeping the highest `id` row
 * per duplicate group (the spec's "select the most recent" rule).
 *
 * Spec refs: §7.1.1, §7.1.3, §7.1.4, §7.1.5, §7.2.
 */
class Migration_Version_300_stores extends App_module_migration
{
    public function up(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        // -------------------------- woocommerce_stores -----------------------
        $stores = 'woocommerce_stores';

        $this->addColumnIfMissing($stores, 'verify_ssl', [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'null'       => false,
            'default'    => 1,
        ]);

        // VARCHAR(255) — long enough to hold the AES-256-GCM ciphertext that
        // CredentialCipher emits (~131 chars: 'enc_v1$' + base64(IV+TAG+CIPHER))
        // for a 64-char plaintext webhook secret. Originally drafted at
        // VARCHAR(64), which would silently truncate ciphertext under
        // non-strict MySQL or reject the insert under strict mode — either
        // way producing a permanently undecryptable secret.
        $this->addColumnIfMissing($stores, 'webhook_secret', [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
        ]);

        $this->addColumnIfMissing($stores, 'pages_per_tick', [
            'type'       => 'TINYINT',
            'constraint' => 3,
            'null'       => false,
            'default'    => 3,
        ]);

        $this->addColumnIfMissing($stores, 'is_active', [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'null'       => false,
            'default'    => 1,
        ]);

        $this->addColumnIfMissing($stores, 'date_modified', [
            'type' => 'DATETIME',
            'null' => true,
        ]);

        // -------------- last_synced_at on the cache tables --------------------
        foreach (['woocommerce_orders', 'woocommerce_products', 'woocommerce_customers'] as $table) {
            $this->addColumnIfMissing($table, 'last_synced_at', [
                'type' => 'DATETIME',
                'null' => true,
            ]);
        }

        // ---------------------------- unique keys ----------------------------
        $this->dedupeAndAddUniqueKey('woocommerce_orders',    ['store_id', 'order_id'],         'uq_woo_orders_store_order');
        $this->dedupeAndAddUniqueKey('woocommerce_products',  ['store_id', 'product_id'],       'uq_woo_products_store_product');
        $this->dedupeAndAddUniqueKey('woocommerce_customers', ['store_id', 'woo_customer_id'],  'uq_woo_customers_store_customer');

        // tblclients and tblinvoices columns are added by legacy install.php;
        // here we only stamp the unique keys that close BUG-002 / BUG-202.
        if ($this->coreColumnExists('tblclients', 'store_id') && $this->coreColumnExists('tblclients', 'woo_id')) {
            $this->dedupeAndAddUniqueKeyForCore('tblclients', ['store_id', 'woo_id'], 'uq_tblclients_store_woo');
        }

        if ($this->coreColumnExists('tblinvoices', 'store_id') && $this->coreColumnExists('tblinvoices', 'wco_id')) {
            $this->dedupeAndAddUniqueKeyForCore('tblinvoices', ['store_id', 'wco_id'], 'uq_tblinvoices_store_wco');
        }
    }

    public function down(): void
    {
        $CI =& get_instance();
        $CI->load->dbforge();

        $this->dropUniqueKeyIfPresent('woocommerce_orders',    'uq_woo_orders_store_order');
        $this->dropUniqueKeyIfPresent('woocommerce_products',  'uq_woo_products_store_product');
        $this->dropUniqueKeyIfPresent('woocommerce_customers', 'uq_woo_customers_store_customer');
        $this->dropUniqueKeyIfPresent('tblclients',            'uq_tblclients_store_woo',  /*coreTable=*/ true);
        $this->dropUniqueKeyIfPresent('tblinvoices',           'uq_tblinvoices_store_wco', /*coreTable=*/ true);

        foreach (['woocommerce_orders', 'woocommerce_products', 'woocommerce_customers'] as $table) {
            $this->dropColumnIfPresent($table, 'last_synced_at');
        }

        foreach (['date_modified', 'is_active', 'pages_per_tick', 'webhook_secret', 'verify_ssl'] as $col) {
            $this->dropColumnIfPresent('woocommerce_stores', $col);
        }
    }

    // -------------------------------------------------------------------------
    //                                helpers
    // -------------------------------------------------------------------------

    /**
     * @param array<string, mixed> $definition
     */
    private function addColumnIfMissing(string $table, string $column, array $definition): void
    {
        if (! $this->ci->db->field_exists($column, $table)) {
            $this->ci->dbforge->add_column($table, [$column => $definition]);
        }
    }

    private function dropColumnIfPresent(string $table, string $column): void
    {
        if ($this->ci->db->field_exists($column, $table)) {
            $this->ci->dbforge->drop_column($table, $column);
        }
    }

    /**
     * @param array<int, string> $columns
     */
    private function dedupeAndAddUniqueKey(string $table, array $columns, string $indexName): void
    {
        $this->dedupeAndAddUniqueKeyForCore(db_prefix() . $table, $columns, $indexName, /*alreadyPrefixed=*/ true);
    }

    /**
     * @param array<int, string> $columns
     */
    private function dedupeAndAddUniqueKeyForCore(string $table, array $columns, string $indexName, bool $alreadyPrefixed = false): void
    {
        $fullTable = $alreadyPrefixed ? $table : (db_prefix() . $table);

        if ($this->indexExists($fullTable, $indexName)) {
            return;
        }

        // For each duplicate group, keep the row with the **highest id**
        // and delete every other row in the group. On the v2 cache tables
        // (woocommerce_orders / products / customers) and on tblclients /
        // tblinvoices, `id` is AUTO_INCREMENT, so highest-id == most-recent
        // INSERT — the only well-defined "most recent" notion the v2
        // schema exposes (date_modified is not consistently populated by
        // the legacy code paths).
        $on = implode(' AND ', array_map(static fn(string $c): string => "t1.`$c` = t2.`$c`", $columns));
        $deleteSql = "DELETE t1 FROM `$fullTable` t1 "
                   . "INNER JOIN `$fullTable` t2 ON $on "
                   . "WHERE t1.`id` < t2.`id`";
        $this->ci->db->query($deleteSql);

        $cols = implode(',', array_map(static fn(string $c): string => "`$c`", $columns));
        $this->ci->db->query("ALTER TABLE `$fullTable` ADD UNIQUE KEY `$indexName` ($cols)");
    }

    private function dropUniqueKeyIfPresent(string $table, string $indexName, bool $coreTable = false): void
    {
        $fullTable = $coreTable ? (db_prefix() . $table) : (db_prefix() . $table);

        if (! $this->indexExists($fullTable, $indexName)) {
            return;
        }

        $this->ci->db->query("ALTER TABLE `$fullTable` DROP INDEX `$indexName`");
    }

    private function indexExists(string $fullTable, string $indexName): bool
    {
        $row = $this->ci->db->query(
            'SELECT COUNT(*) AS c FROM information_schema.statistics
              WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
            [$fullTable, $indexName]
        )->row();

        return $row && (int) $row->c > 0;
    }

    private function coreColumnExists(string $coreTable, string $column): bool
    {
        return $this->ci->db->field_exists($column, str_replace(db_prefix(), '', $coreTable));
    }
}
