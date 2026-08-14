# System Workflow & State Transition Specification

This document provides a 100% complete specification of state machines, workflow transitions, role-based action permissions, and status lifecycle flows across all modules.

---

## 1. Invoice Lifecycle Workflow

### 1.1 State Machine
```text
  [1] Unpaid ─────────► [3] Partially Paid ─────────► [2] Paid
      │                          │
      │ (Due Date Passed)        │ (Due Date Passed)
      ▼                          ▼
  [4] Overdue ────────► [3] Partially Paid ─────────► [2] Paid
      │
      ▼
  [5] Cancelled
```

### 1.2 Transition Matrix

| Initial State | Allowed Action | Next State | Allowed Roles | Trigger Condition / Validation |
|---|---|---|---|---|
| Unpaid (1) | Record Payment | Partially Paid (3) | Admin, Accounting | $0 < \text{Payment Amount} < \text{Invoice Total}$ |
| Unpaid (1) | Record Full Payment | Paid (2) | Admin, Accounting | $\text{Payment Amount} \ge \text{Invoice Total}$ |
| Unpaid (1) | Cron Due Check | Overdue (4) | System Cron | $\text{DueDate} < \text{Today}$ and Status != Paid |
| Overdue (4) | Record Full Payment | Paid (2) | Admin, Accounting | $\text{Payment Amount} \ge \text{Invoice Total}$ |
| Unpaid (1) | Cancel Invoice | Cancelled (5) | Admin | No payments recorded |

---

## 2. Lead Conversion & Sales Pipeline Workflow

### 2.1 State Machine
```text
  [1] New / Prospect ──► [2] Contacted ──► [3] Proposal Sent ──► [4] Qualified
                                                                      │
                                                                      ▼
  [Junk / Lost] ◄────────────────────────────────────────────── [Converted to Client]
```

### 2.2 Transition Matrix

| Initial State | Allowed Action | Next State | Allowed Roles | Validation / Side Effects |
|---|---|---|---|---|
| New (1) | Contact Lead | Contacted (2) | Sales Staff, Admin | Log activity |
| Contacted (2) | Send Proposal | Proposal Sent (3) | Sales Staff, Admin | Proposal document attached |
| Proposal Sent (3) | Qualify Lead | Qualified (4) | Sales Staff, Admin | Budget & timeline verified |
| Qualified (4) | Convert Lead | Converted | Sales Staff, Admin | Transactionally creates `Client` & primary `Contact` |
| Any Active | Mark Lost | Lost | Sales Staff, Admin | Reason note required |
| Any Active | Mark Junk | Junk | Sales Staff, Admin | Flagged as spam |

---

## 3. Support Ticket Workflow

### 3.1 State Machine
```text
  [1] Open ──────────► [2] In Progress ──────────► [3] Answered ──────────► [4] Closed
    ▲                         │                          │                      │
    └─────────────────────────┴──────────────────────────┴──────────────────────┘
                                (Re-opened on client reply)
```

### 3.2 Transition Matrix

| Initial State | Action | Next State | Role | Condition |
|---|---|---|---|---|
| Open (1) | Assign Staff | In Progress (2) | Admin, Support | `assigned_staff_id > 0` |
| In Progress (2) | Post Reply | Answered (3) | Support Staff | Reply message non-empty |
| Answered (3) | Close Ticket | Closed (4) | Client, Admin | Issue resolved |
| Closed (4) | Client Message | Open (1) | Client | New incoming client reply |

---

## 4. Task & Project Lifecycle Workflow

### 4.1 State Machine
```text
  [1] Not Started ──────► [2] In Progress ──────► [3] Testing ──────► [5] Complete
         │                                                                ▲
         └──────────────────────► [4] Awaiting Feedback ──────────────────┘
```

### 4.2 Transition Matrix

| Initial State | Action | Next State | Role | Side Effect |
|---|---|---|---|---|
| Not Started (1) | Start Timer / Task | In Progress (2) | Assigned Staff | Start `TaskTimer` record |
| In Progress (2) | Submit for Testing | Testing (3) | Assigned Staff | Notify QA Lead |
| Testing (3) | Request Feedback | Awaiting Feedback (4) | QA Staff | Client notification |
| Any Active | Mark Complete | Complete (5) | Admin, Lead | Set `datefinished = datetime.utcnow()` |

---

## 5. Purchase Order Workflow

### 5.1 State Machine
```text
  [1] Draft ──────► [2] Pending Approval ──────► [3] Approved ──────► [4] Received
                         │
                         ▼
                    [5] Rejected
```

### 5.2 Transition Matrix

| Initial State | Action | Next State | Role | Condition |
|---|---|---|---|---|
| Draft (1) | Submit PO | Pending Approval (2) | Procurement Staff | Total calculated |
| Pending (2) | Approve PO | Approved (3) | Admin, Manager | Total $\le$ Manager limit |
| Pending (2) | Reject PO | Rejected (5) | Manager | Rejection reason required |
| Approved (3) | Log Receipt | Received (4) | Warehouse Manager | Stock levels incremented |

---

## 6. OKRs & Strategy Workflow

### 6.1 State Machine
```text
  [1] Active Quarter ──────► [2] In Progress ──────► [3] Closed & Evaluated
```

### 6.2 Key Result Update Transition
- Updating `current_value` on a Key Result automatically re-evaluates the parent OKR `progress` percentage ($0\% - 100\%$).
