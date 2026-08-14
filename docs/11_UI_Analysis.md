# UI Analysis

## Purpose
Exposes layout grids, design aesthetics, widgets, and styles for core and custom dashboards.

## Scope
Admin themes, client portals, recruitment portals, and supplier screens.

## Detailed Explanation
Perfex CRM uses a classic, highly responsive two-column admin layout.
- **Sidebar**: Nested navigation tree containing links to core and hooks-injected modules.
- **Top bar**: Quick search bar, profile options, staff task timer tracker widget, and system announcements.
- **Main Area**: Dashboard widgets (charts, counters, calendar) or listing tables.

### Design System
- **Grid Layout**: Bootstrap 3 grid (12 columns).
- **Interactive Tables**: Enforced using jQuery DataTables (serverside processing for large datasets).
- **Modals**: Used for quick item additions, edit fields, and attachments details.
- **Forms**: Enforced styling using custom bootstrap inputs, selectpicker components, and datepicker fields.

## References
- [Screen Flow](12_Screen_Flow.md)
- [Menu Structure](13_Menu_Structure.md)
