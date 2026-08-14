# Scheduler

## Purpose
Documents system cron schedules and automated operational tasks.

## Scope
Daily cron triggers, ticket email pipes, and subscription updates.

## Detailed Explanation
Automated tasks are orchestrated by setting up a crontab schedule calling `pipe.php` or `cron.php` (configured on the hosting server to run every minute).

### Cron Tasks Schedule
- **Every Minute**: Checks email queues (`tblmail_queue`) and transmits pending emails. Logs background syncing jobs.
- **Hourly**: Syncs WooCommerce store orders, checks for ticket imports via piping.
- **Daily (Midnight)**:
  - Updates overdue invoice statuses to "Overdue" (status 5).
  - Sends automatic invoice payment reminders.
  - Automatically generates recurring subscription invoices (`tblsubscriptions`).
  - Purges temporary directories and system caches.

## References
- [Background Jobs](28_Background_Jobs.md)
- [Logging](32_Logging.md)
