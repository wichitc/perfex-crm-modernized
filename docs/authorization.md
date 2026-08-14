# Authorization & Role-Based Access Control (RBAC)

This document defines the Role-Based Access Control (RBAC) rules, permission scopes, resource-level ownership policies, and IDOR prevention controls.

---

## 1. Role Hierarchy & Permission Scopes

System access is governed by staff role assignments (`tblroles` & `tblstaff.admin` flag):

```text
               ┌────────────────────────┐
               │    System Admin        │  (admin == 1, scopes: ["admin:all"])
               └───────────┬────────────┘
                           │
             ┌─────────────┴─────────────┐
             ▼                           ▼
  ┌────────────────────┐      ┌────────────────────┐
  │ Department Manager │      │    Staff User      │  (scopes: ["core:read", "core:write"])
  └────────────────────┘      └────────────────────┘
```

---

## 2. Permission Scopes Catalog

| Permission Scope | Allowed Actions | Resource Domain |
|---|---|---|
| `admin` | Full unrestricted access | Global System |
| `core:read` | View clients, leads, tasks, projects, invoices, tickets | CRM Core |
| `core:write` | Create & edit assigned clients, leads, tasks, invoices | CRM Core |
| `accounting:all` | Access Chart of Accounts, post journal entries, view profit/loss | Financials |
| `hrm:all` | Manage recruitment campaigns, candidates, staff outsourcing | Human Resources |
| `recruitment:all` | View & update applicant hiring stages | HR Recruitment |

---

## 3. IDOR & Data Isolation Safeguards

1. **Resource Access Validation**:
   - Endpoints `/clients/{id}`, `/invoices/{id}`, `/tasks/{id}`, `/tickets/{id}` verify client/staff authorization before dereferencing resources.
2. **Staff Assignment Checks**:
   - Staff without admin scope can only view/edit leads, tasks, and projects assigned to their `staffid`.
3. **Double-Entry Modification Restrictions**:
   - Financial ledger entries (`tblacc_account_history`) are append-only. Existing journal entries cannot be deleted without an reversing entry.
