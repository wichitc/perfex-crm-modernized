# Configuration

## Purpose
Details application configs, option settings, and environment properties.

## Scope
MySQL configuration, SMTP definitions, and options table preferences.

## Detailed Explanation
### System Configurations
- **Database Connection**: Configured inside `perfex_crm/application/config/database.php`.
- **Core Parameters**: Configured inside `perfex_crm/application/config/config.php`.

### Dynamic Option Options
- Global settings are stored inside `tbloptions` (key-value schema).
- Read using helper function `get_option('option_name')`.
- Updated using `update_option('option_name', 'value')`.
- Examples of options:
  - `smtp_host`, `smtp_user`, `smtp_pass` (Email setup)
  - `acc_accounting_method` (Accounting defaults)
  - `warehouse_default_shipping_fee` (Inventory defaults)

## References
- [System Architecture](01_System_Architecture.md)
- [Technology Stack](02_Technology_Stack.md)
