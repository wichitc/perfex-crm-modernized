# Authorization

## Purpose
Documents authorization checks, access control rules, and hook levels permission enforcements.

## Scope
Staff capabilities, client view restrictions, and REST API scopes.

## Detailed Explanation
Permissions are enforced at the Controller constructor level or before specific action execution.

### Permission Checks
- Core helper function `has_permission($permission, $staff_id, $action)` queries `tblstaff_permissions` to check capabilities.
- Controllers block access:
  ```php
  if (!has_permission('accounting', '', 'view')) {
      access_denied('accounting');
  }
  ```
- Customer portal authorizations check mapped permissions inside `tblcontact_permissions` (e.g. permission value 1 allows viewing invoices, 2 allows viewing projects).

## References
- [User Roles](07_User_Roles.md)
- [Authentication](24_Authentication.md)
