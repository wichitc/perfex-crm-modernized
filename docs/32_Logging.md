# Logging

## Purpose
Details activity auditing logs, access logging, and framework configurations.

## Scope
Administrative audit trails, file logging, and error tracking.

## Detailed Explanation
### 1. Database Audit Logs
- Every administrative action is logged to `tblactivity_log`.
- Triggered using helper function `log_activity('Activity message description', staff_id)`.
- Records description, date, and staff ID.

### 2. File-Based Logs
- Enforced by CodeIgniter logging settings in `application/config/config.php`.
- Log files are generated in `application/logs/log-YYYY-MM-DD.php`.
- Records warning, info, and database query errors.

## References
- [Error Handling](33_Error_Handling.md)
- [Security](23_Security.md)
