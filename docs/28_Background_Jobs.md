# Background Jobs

## Purpose
Describes background queue execution patterns for offline tasks.

## Scope
Email queues, SMS dispatchers, and WooCommerce API synchronization.

## Detailed Explanation
Perfex CRM handles async tasks using a centralized database queue structure or immediate execution hooks triggered during user action.

### Queued Tasks
- **Email Queue**: Outgoing emails are stored in `tblmail_queue` if queueing is enabled. The cron scheduler processes and sends them in batches to prevent SMTP rate limiting.
- **WooCommerce Sync Jobs**: Sync tasks are logged inside `tblwoocommerce_jobs` to sync product listings, customer mappings, and stock changes in the background.

## References
- [Scheduler](29_Scheduler.md)
- [Notification](31_Notification.md)
