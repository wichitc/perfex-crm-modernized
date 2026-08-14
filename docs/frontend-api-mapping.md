# Frontend to RESTful API Mapping Matrix

## UI Action to Endpoint Traceability Matrix

| UI Action | Component Location | HTTP Method | RESTful Endpoint | Response Data Model |
| :--- | :--- | :---: | :--- | :--- |
| Submit Login Form | `src/app/(auth)/login/page.tsx` | `POST` | `/api/v1/auth/login` | `{ access_token, user }` |
| Load Dashboard KPIs | `src/app/(dashboard)/page.tsx` | `GET` | `/api/v1/dashboard/stats` | `DashboardStatsResponse` |
| Fetch Clients List | `src/app/(dashboard)/clients/page.tsx` | `GET` | `/api/v1/clients` | `Client[]` |
| Save Client Account | `src/app/(dashboard)/clients/page.tsx` | `POST` | `/api/v1/clients` | `Client` |
| Fetch Leads Kanban | `src/app/(dashboard)/leads/page.tsx` | `GET` | `/api/v1/leads` | `Lead[]` |
| Convert Lead | `src/app/(dashboard)/leads/page.tsx` | `POST` | `/api/v1/leads/{id}/convert` | `Client` |
| Fetch Invoices Table | `src/app/(dashboard)/invoices/page.tsx` | `GET` | `/api/v1/invoices` | `Invoice[]` |
| Record Payment | `src/app/(dashboard)/invoices/page.tsx` | `POST` | `/api/v1/invoices/{id}/payments` | `Payment` |
| Fetch Accounting Ledger | `src/app/(dashboard)/accounting/page.tsx` | `GET` | `/api/v1/accounting/accounts` | `Account[]` |
| Stock Transfer | `src/app/(dashboard)/warehouse/page.tsx` | `POST` | `/api/v1/warehouse/transfer` | `StockTransferResult` |
| Fetch PO Orders | `src/app/(dashboard)/purchase/page.tsx` | `GET` | `/api/v1/purchase/orders` | `PurchaseOrder[]` |
| Trigger WooCommerce Sync | `src/app/(dashboard)/woocommerce/page.tsx` | `POST` | `/api/v1/woocommerce/sync` | `SyncResult` |
| Fetch Candidates | `src/app/(dashboard)/recruitment/page.tsx` | `GET` | `/api/v1/recruitment/overview` | `RecruitmentData` |
| Fetch OKRs Tree | `src/app/(dashboard)/okrs/page.tsx` | `GET` | `/api/v1/okrs` | `Objective[]` |
| Fetch Active Tasks | `src/app/(dashboard)/tasks/page.tsx` | `GET` | `/api/v1/tasks` | `Task[]` |
| Reply to Ticket | `src/app/(dashboard)/tickets/page.tsx` | `POST` | `/api/v1/tickets/{id}/reply` | `TicketReply` |
| Book Contractor | `src/app/(dashboard)/staff-outsourcing/page.tsx` | `POST` | `/api/v1/staff-outsourcing/book` | `BookingResult` |
| Save Account SWOT | `src/app/(dashboard)/account-planning/page.tsx` | `POST` | `/api/v1/account-planning` | `SWOTResult` |
| Save System Theme | `src/app/(dashboard)/settings/page.tsx` | `POST` | `/api/v1/settings` | `Settings` |
