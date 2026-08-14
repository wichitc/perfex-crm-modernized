# Error Handling

## Purpose
Documents application error interceptions, database transaction rollbacks, and recovery structures.

## Scope
CodeIgniter exception templates, DB query intercepts, and graceful crashes.

## Detailed Explanation
### 1. Database Transactions Rollback
- All multi-table updates (e.g. creating an invoice with items, ledger posts) use CodeIgniter Active Record transaction wrappers:
  ```php
  $this->db->trans_start();
  // Queries...
  $this->db->trans_complete();
  if ($this->db->trans_status() === FALSE) {
      // Transaction failed, rollback automatic.
  }
  ```

### 2. HTTP Exception Interception
- Managed by CodeIgniter error templates located in `application/views/errors/`.
- Displays friendly 404 and 500 error pages to clients, while outputting full traceback logs to server log files for administrators.

## References
- [Logging](32_Logging.md)
- [Database Analysis](15_Database_Analysis.md)
