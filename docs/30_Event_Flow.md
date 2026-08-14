# Event Flow

## Purpose
Deep-dive into the Actions/Filters Hook System.

## Scope
Custom actions, filters, event listeners, and dynamic module updates.

## Detailed Explanation
Perfex CRM implements a custom PHP hook mechanism (similar to WordPress) managed by the `App_Hooks` library class.

### Actions
Allows executing custom code at specific system events.
- **Syntax**:
  - Register: `hooks()->add_action('hook_name', 'callback_function', priority_integer);`
  - Trigger: `hooks()->do_action('hook_name', $args);`
- **Examples**:
  - `admin_init`: Triggered when admin area boots; used to inject side menus.
  - `after_invoice_added`: Used by accounting and warehouse modules to sync invoices.

### Filters
Allows filtering data arrays/strings before rendering or saving.
- **Syntax**:
  - Register: `hooks()->add_filter('filter_name', 'callback_function', priority_integer);`
  - Trigger: `hooks()->apply_filters('filter_name', $value, $args);`
- **Examples**:
  - `before_invoice_added`: Filters invoice item taxes or fields.

## References
- [System Architecture](01_System_Architecture.md)
- [Component Diagram](20_Component_Diagram.md)
