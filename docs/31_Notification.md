# Notification

## Purpose
Documents system alerts, email templates, and SMS settings layouts.

## Scope
Admin pop-ups, client emails, Pusher integrations, and SMS triggers.

## Detailed Explanation
### 1. Email Templates
- Managed in administrative settings screen and stored in `tblemailtemplates`.
- Supports dynamic variables (merge fields) replaced at execution (e.g. `{contact_firstname}`, `{invoice_link}`).
- Managed by CodeIgniter's email library (transmits via mail(), sendmail, or SMTP).

### 2. SMS Notifications
- Supports triggers like invoice overdue, contract signed, task assigned.
- Gateways include Twilio, Clickatell, and custom SMS gateways.

### 3. Real-Time Web Notifications
- Staff get instant alerts on dashboard when events occur (e.g. lead assigned, project task completed).
- Real-time websocket propagation is supported by configuring Pusher credentials.

## References
- [Background Jobs](28_Background_Jobs.md)
- [External API](37_External_API.md)
