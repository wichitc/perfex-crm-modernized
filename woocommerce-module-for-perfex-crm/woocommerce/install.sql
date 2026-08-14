-- =========================================================================
-- WooCommerce module v3 — install schema.
--
-- Executed by the activation hook (install.php). Statements are split on
-- ';' at end of line and run one-by-one via $CI->db->query(). The literal
-- token `__PFX__` is replaced with db_prefix() at runtime.
--
-- Idempotency:
--   * Module-owned tables use CREATE TABLE IF NOT EXISTS.
--   * Column adds against core Perfex tables (clients, invoices, staff,
--     payment_modes) and unique-key adds use the prepared-statement
--     INFORMATION_SCHEMA pattern so re-running is a no-op.
--   * Seed inserts use NOT EXISTS guards.
--
-- After this file finishes, install.php still runs the migration runner
-- so tblmodules.installed_version stamps correctly for upgraders.
-- =========================================================================


-- -------------------------------------------------------------------------
-- woocommerce_stores  (consolidated from migrations 220, 222, 228, 230, 250)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_stores` (
    `store_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `secret` VARCHAR(255) NOT NULL,
    `productPage` INT(5) DEFAULT 1,
    `orderPage` INT(5) DEFAULT 1,
    `customerPage` INT(5) DEFAULT 1,
    `date_created` DATETIME NOT NULL,
    `query_auth` INT DEFAULT 1,
    `auto_convert_customer` TINYINT(1) NOT NULL DEFAULT 0,
    `auto_convert_product` TINYINT(1) NOT NULL DEFAULT 0,
    `auto_convert_order` TINYINT(1) NOT NULL DEFAULT 0,
    `auto_invoice_statuses` TEXT NULL,
    `verify_ssl` TINYINT(1) NOT NULL DEFAULT 1,
    `webhook_secret` VARCHAR(255) NULL,
    `pages_per_tick` TINYINT(3) NOT NULL DEFAULT 3,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `date_modified` DATETIME NULL,
    `woocommerce_payment_mode_id` INT NULL,
    PRIMARY KEY (`store_id`),
    KEY `idx_woo_stores_auto_convert` (`auto_convert_customer`, `auto_convert_product`, `auto_convert_order`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_assigned  (staff <-> store mapping; migration 220)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_assigned` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT NOT NULL,
    `staff_id` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_orders  (migrations 200, 221, 230, 270 — cache + soft delete)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_orders` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) NOT NULL,
    `order_number` VARCHAR(50) NOT NULL,
    `customer_id` INT(11) NOT NULL,
    `address` TEXT DEFAULT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `status` VARCHAR(100) DEFAULT NULL,
    `currency` VARCHAR(10) DEFAULT NULL,
    `date_created` DATETIME DEFAULT NULL,
    `date_modified` DATETIME DEFAULT NULL,
    `total` VARCHAR(30) DEFAULT NULL,
    `invoice_id` INT(30) DEFAULT NULL,
    `store_id` INT(5) DEFAULT NULL,
    `last_synced_at` DATETIME NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_woo_orders_store_order` (`store_id`, `order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_products
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_products` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT(11) NOT NULL,
    `itemid` INT(11) DEFAULT NULL,
    `name` VARCHAR(500) DEFAULT NULL,
    `permalink` VARCHAR(500) DEFAULT NULL,
    `type` VARCHAR(50) DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT NULL,
    `sku` VARCHAR(50) DEFAULT NULL,
    `price` VARCHAR(20) DEFAULT NULL,
    `sales` VARCHAR(20) DEFAULT NULL,
    `picture` TEXT DEFAULT NULL,
    `category` TEXT DEFAULT NULL,
    `date_created` DATETIME DEFAULT NULL,
    `date_modified` DATETIME DEFAULT NULL,
    `store_id` INT(5) DEFAULT NULL,
    `last_synced_at` DATETIME NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_woo_products_store_product` (`store_id`, `product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_customers
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_customers` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `woo_customer_id` INT(11) NOT NULL,
    `userid` INT(11) DEFAULT NULL,
    `email` VARCHAR(190) DEFAULT NULL,
    `first_name` VARCHAR(100) DEFAULT NULL,
    `last_name` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `role` VARCHAR(50) DEFAULT NULL,
    `username` VARCHAR(100) DEFAULT NULL,
    `avatar_url` TEXT DEFAULT NULL,
    `store_id` INT(5) DEFAULT NULL,
    `last_synced_at` DATETIME NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_woo_customers_store_customer` (`store_id`, `woo_customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_summary  (legacy dashboard counters)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_summary` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT(5) DEFAULT NULL,
    `customers` TEXT DEFAULT NULL,
    `orders` TEXT DEFAULT NULL,
    `products` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- field-mapping tables  (migrations 224, 225, 226, 227)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_customer_field_mapping` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT(10) UNSIGNED NOT NULL,
    `wc_field` VARCHAR(191) NOT NULL,
    `perfex_field` VARCHAR(191) NOT NULL,
    `is_required` TINYINT(1) DEFAULT 0,
    `default_value` VARCHAR(191) DEFAULT NULL,
    `is_predefined` TINYINT(1) NOT NULL DEFAULT 0,
    `is_overridden` TINYINT(1) NOT NULL DEFAULT 0,
    `original_wc_field` VARCHAR(191) NULL,
    `original_perfex_field` VARCHAR(191) NULL,
    PRIMARY KEY (`id`),
    KEY `idx_store_id` (`store_id`),
    KEY `idx_wc_field` (`wc_field`),
    KEY `idx_perfex_field` (`perfex_field`),
    KEY `idx_woocommerce_customer_field_mapping_is_predefined` (`is_predefined`),
    KEY `idx_woocommerce_customer_field_mapping_is_overridden` (`is_overridden`),
    CONSTRAINT `fk_customer_field_mapping_store`
        FOREIGN KEY (`store_id`) REFERENCES `__PFX__woocommerce_stores`(`store_id`)
        ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_product_field_mapping` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT(10) UNSIGNED NOT NULL,
    `wc_field` VARCHAR(191) NOT NULL,
    `perfex_field` VARCHAR(191) NOT NULL,
    `is_required` TINYINT(1) DEFAULT 0,
    `default_value` VARCHAR(191) DEFAULT NULL,
    `is_predefined` TINYINT(1) NOT NULL DEFAULT 0,
    `is_overridden` TINYINT(1) NOT NULL DEFAULT 0,
    `original_wc_field` VARCHAR(191) NULL,
    `original_perfex_field` VARCHAR(191) NULL,
    PRIMARY KEY (`id`),
    KEY `idx_store_id` (`store_id`),
    KEY `idx_wc_field` (`wc_field`),
    KEY `idx_perfex_field` (`perfex_field`),
    KEY `idx_woocommerce_product_field_mapping_is_predefined` (`is_predefined`),
    KEY `idx_woocommerce_product_field_mapping_is_overridden` (`is_overridden`),
    CONSTRAINT `fk_product_field_mapping_store`
        FOREIGN KEY (`store_id`) REFERENCES `__PFX__woocommerce_stores`(`store_id`)
        ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_order_field_mapping` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT(10) UNSIGNED NOT NULL,
    `wc_field` VARCHAR(191) NOT NULL,
    `perfex_field` VARCHAR(191) NOT NULL,
    `is_required` TINYINT(1) DEFAULT 0,
    `default_value` VARCHAR(191) DEFAULT NULL,
    `is_predefined` TINYINT(1) NOT NULL DEFAULT 0,
    `is_overridden` TINYINT(1) NOT NULL DEFAULT 0,
    `original_wc_field` VARCHAR(191) NULL,
    `original_perfex_field` VARCHAR(191) NULL,
    PRIMARY KEY (`id`),
    KEY `idx_store_id` (`store_id`),
    KEY `idx_wc_field` (`wc_field`),
    KEY `idx_perfex_field` (`perfex_field`),
    KEY `idx_woocommerce_order_field_mapping_is_predefined` (`is_predefined`),
    KEY `idx_woocommerce_order_field_mapping_is_overridden` (`is_overridden`),
    CONSTRAINT `fk_order_field_mapping_store`
        FOREIGN KEY (`store_id`) REFERENCES `__PFX__woocommerce_stores`(`store_id`)
        ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_webhook_log  (migration 240, §7.1.8 — replay-protection dedup)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_webhook_log` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT UNSIGNED NOT NULL,
    `topic` VARCHAR(64) NOT NULL,
    `resource` VARCHAR(32) NOT NULL,
    `woo_id` INT UNSIGNED NULL,
    `delivery_id` VARCHAR(64) NOT NULL,
    `received_at` DATETIME NOT NULL,
    `signature_ok` TINYINT(1) NOT NULL DEFAULT 0,
    `processed` TINYINT(1) NOT NULL DEFAULT 0,
    `error` TEXT NULL,
    `payload_hash` CHAR(64) NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_webhook_delivery` (`delivery_id`),
    KEY `idx_webhook_store_topic` (`store_id`, `topic`),
    KEY `idx_webhook_received_at` (`received_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_log  (migration 240, §7.1.9 — generic structured log)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_log` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `store_id` INT UNSIGNED NULL,
    `level` ENUM('info','warn','error') NOT NULL DEFAULT 'info',
    `event` VARCHAR(128) NOT NULL,
    `context_json` MEDIUMTEXT NULL,
    `correlation_id` VARCHAR(64) NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_log_store_event_time` (`store_id`, `event`, `created_at`),
    KEY `idx_log_level_time` (`level`, `created_at`),
    KEY `idx_log_correlation` (`correlation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_rate_limit  (migration 260 — per-store token bucket)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_rate_limit` (
    `store_id` INT UNSIGNED NOT NULL,
    `tokens` DOUBLE NOT NULL DEFAULT 0,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- woocommerce_jobs  (migration 280 — background job queue, §16)
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `__PFX__woocommerce_jobs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(64) NOT NULL,
    `store_id` INT UNSIGNED NULL,
    `payload_json` MEDIUMTEXT NOT NULL,
    `status` ENUM('pending','in_progress','done','failed','quarantined') NOT NULL DEFAULT 'pending',
    `attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `max_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `last_error` TEXT NULL,
    `scheduled_for` DATETIME NOT NULL,
    `locked_at` DATETIME NULL,
    `last_run_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_jobs_pending` (`status`, `scheduled_for`),
    KEY `idx_jobs_store_status` (`store_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================================
-- Core-table column adds (idempotent via INFORMATION_SCHEMA prepared stmts).
-- Each block: SET @s = IF(...col missing..., 'ALTER...', 'SELECT 1');
--             PREPARE / EXECUTE / DEALLOCATE.
-- =========================================================================

-- tblclients.woo_id
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__clients' AND column_name = 'woo_id') = 0, 'ALTER TABLE `__PFX__clients` ADD COLUMN `woo_id` INT(9) NULL', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblclients.store_id
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__clients' AND column_name = 'store_id') = 0, 'ALTER TABLE `__PFX__clients` ADD COLUMN `store_id` INT(9) NULL', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblclients.is_guest  (§4A.1 guest checkout)
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__clients' AND column_name = 'is_guest') = 0, 'ALTER TABLE `__PFX__clients` ADD COLUMN `is_guest` TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblinvoices.wco_id
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__invoices' AND column_name = 'wco_id') = 0, 'ALTER TABLE `__PFX__invoices` ADD COLUMN `wco_id` INT(9) NULL', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblinvoices.store_id
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__invoices' AND column_name = 'store_id') = 0, 'ALTER TABLE `__PFX__invoices` ADD COLUMN `store_id` INT(9) NULL', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblstaff.store_id
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__staff' AND column_name = 'store_id') = 0, 'ALTER TABLE `__PFX__staff` ADD COLUMN `store_id` INT(9) NULL', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblpayment_modes.is_system_managed  (provenance marker, §4A.3)
SET @s = (SELECT IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__payment_modes' AND column_name = 'is_system_managed') = 0, 'ALTER TABLE `__PFX__payment_modes` ADD COLUMN `is_system_managed` TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =========================================================================
-- Cross-table unique keys (close BUG-002 / BUG-202). Add only when both
-- referenced columns exist (the ALTERs above land them on first install).
-- =========================================================================

-- tblclients (store_id, woo_id) — unique per store
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = DATABASE() AND table_name = '__PFX__clients' AND index_name = 'uq_tblclients_store_woo') = 0
    AND (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__clients' AND column_name IN ('store_id','woo_id')) = 2,
    'ALTER TABLE `__PFX__clients` ADD UNIQUE KEY `uq_tblclients_store_woo` (`store_id`, `woo_id`)',
    'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- tblinvoices (store_id, wco_id) — unique per store
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = DATABASE() AND table_name = '__PFX__invoices' AND index_name = 'uq_tblinvoices_store_wco') = 0
    AND (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = DATABASE() AND table_name = '__PFX__invoices' AND column_name IN ('store_id','wco_id')) = 2,
    'ALTER TABLE `__PFX__invoices` ADD UNIQUE KEY `uq_tblinvoices_store_wco` (`store_id`, `wco_id`)',
    'SELECT 1'));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =========================================================================
-- Seeds.
-- =========================================================================

-- WooCommerce payment mode row (provenance marker; kept inactive + system-managed)
INSERT INTO `__PFX__payment_modes` (`name`, `description`, `show_on_pdf`, `invoices_only`, `expenses_only`, `selected_by_default`, `active`, `is_system_managed`)
SELECT 'WooCommerce', 'Auto-tag for invoices and payments imported from WooCommerce. Do not enable for manual use.', 0, 0, 0, 0, 0, 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `__PFX__payment_modes` WHERE `name` = 'WooCommerce');
