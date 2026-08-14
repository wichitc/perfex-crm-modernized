# Frontend Test Plan & E2E Specifications - Perfex CRM

## E2E Playwright / Cypress Test Suite Specification

### Test Suite 1: Authentication & Navigation
- `TC-001`: Render Login form with validation error handling.
- `TC-002`: Successful sign-in and redirect to Dashboard (`/`).
- `TC-003`: Verify active sidebar link highlighted correctly across all 16 routes.

### Test Suite 2: Client Management E2E
- `TC-004`: Render Client Directory list.
- `TC-005`: Open 'Add New Client' modal, validate required fields.
- `TC-006`: Filter client list by keyword search term.

### Test Suite 3: Leads Kanban & Conversion E2E
- `TC-007`: Toggle between Kanban view and Table view.
- `TC-008`: Drag lead card between pipeline stages.
- `TC-009`: Trigger Lead-to-Client conversion wizard.

### Test Suite 4: Invoicing & Payment E2E
- `TC-010`: Render Invoices table with status filter badges.
- `TC-011`: Open Invoice Detail view and trigger PDF print preview.
- `TC-012`: Record payment against unpaid invoice.

### Test Suite 5: Theme Switcher E2E
- `TC-013`: Open Topbar Theme dropdown selector.
- `TC-014`: Select each theme (Teal, Dark, Light, Green, Orange, Purple) and verify DOM `data-perfex-theme` attribute update.
