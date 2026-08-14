# Technology Stack

## Purpose
Specifies the programming languages, frameworks, libraries, and runtimes used to build Perfex CRM and its modules.

## Scope
Includes backend, frontend, database, and third-party services.

## Detailed Explanation
### Backend Stack
- **Language**: PHP (7.4 to 8.2 compatible).
- **Framework**: CodeIgniter 3 (MVC Architecture).
- **Libraries**:
  - **TCPDF**: PDF generation for invoices, proposals, and estimates.
  - **elFinder**: File management and browser.
  - **PhpSpreadsheet**: Excel file imports/exports for Warehouse and Accounting.
  - **PHPass**: Secure password hashing.

### Database Stack
- **Engine**: MySQL / MariaDB (InnoDB engine for transaction support).
- **Driver**: mysqli.

### Frontend Stack
- **Structure**: HTML5.
- **Styling**: Vanilla CSS, Bootstrap 3 (admin layout), tailwindcss (in newer modules like okrs).
- **Scripting**: JavaScript (jQuery, AJAX).
- **Components**: DataTables (interactive tables), Chart.js (analytics and reports), Dropzone.js (file uploads).

### Third-Party Integrations
- **Payment Gateways**: Stripe, PayPal, Authorize.net, Mollie, Braintree.
- **Communications**: Twilio SMS, Clickatell SMS, Pusher (web socket notifications).

## References
- [System Architecture](01_System_Architecture.md)
- [Configuration](27_Configuration.md)
