# Frontend API Inventory & Functional Mapping

This document provides a 100% complete inventory of every frontend screen, component, hook, service call, HTTP method, request/response structure, authentication requirement, and validation rule within the modernized Next.js 16 frontend application.

---

## 1. Inventory Summary

- **Total Frontend Screens**: 17 Pages
- **Total Frontend Service Endpoints Identified**: 24 Endpoints
- **Authentication Protocol**: JWT Bearer Token + HttpOnly Cookies (`refreshtoken`)
- **API Base URL**: `/api/v1`

---

## 2. Comprehensive Screen-by-Screen API Inventory

### 2.1 Authentication & User Profile
- **Screen**: Login Page (`frontend/src/app/(auth)/login/page.tsx`)
  - **Hook / Service**: `useAuth` (`frontend/src/hooks/use-auth.ts`)
  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/auth/login`
  - **Request Body**: `{ "email": "admin@crm.com", "password": "admin_password" }`
  - **Response Body**: `{ "access_token": "JWT_STRING", "token_type": "bearer" }`
  - **Cookie Set**: `refreshtoken` (HttpOnly, SameSite=Strict)
  - **Validation**: Email format, required fields
  - **Auth Required**: No

- **Screen**: Current User Check (Global Layout Header)
  - **Hook / Service**: `useAuth` -> `apiClient.get("/auth/me")`
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/auth/me`
  - **Headers**: `Authorization: Bearer <access_token>`
  - **Response Body**: `{ "staffid": 1, "email": "admin@crm.com", "firstname": "System", "lastname": "Administrator", "admin": 1, "role": 1, "scopes": ["admin:all"] }`
  - **Auth Required**: Yes

- **Screen**: Token Refresh (Axios Interceptor `api-client.ts`)
  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/auth/refresh`
  - **Cookie Sent**: `refreshtoken`
  - **Response Body**: `{ "access_token": "NEW_JWT_STRING", "token_type": "bearer" }`
  - **Auth Required**: No (Uses Refresh Cookie)

- **Screen**: Sign Out (`frontend/src/components/layout/header.tsx`)
  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/auth/logout`
  - **Response Body**: `{ "detail": "Successfully logged out" }`
  - **Auth Required**: Yes

---

### 2.2 Executive Dashboard Overview
- **Screen**: Main Dashboard (`frontend/src/app/(dashboard)/page.tsx`)
  - **Component**: `DashboardPage`
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/dashboard/stats`
  - **Response Body**: 
    ```json
    {
      "totalRevenue": 148920.00,
      "revenueChange": "+14.2%",
      "activeClients": 48,
      "clientsChange": "+4",
      "pendingInvoices": 12,
      "invoicesAmount": 34500.00,
      "openLeads": 27,
      "leadsConverted": "68%",
      "revenueChart": [ { "month": "Jan", "revenue": 12400, "expenses": 8200 } ],
      "activeTasks": [ { "id": 1, "title": "Deploy Next.js 16 Multi-Theme Switcher", "priority": "High", "status": "In Progress", "dueDate": "2026-07-30", "assignee": "Frontend Agent" } ]
    }
    ```
  - **Auth Required**: Yes

---

### 2.3 Clients Directory & Contact Management
- **Screen**: Clients Directory (`frontend/src/app/(dashboard)/clients/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/clients`
  - **Query Params**: `offset=0`, `limit=100`, `active=1`
  - **Response Body**: Array of client objects containing corporate details, VAT, phone, city, and nested `contacts`.

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/clients`
  - **Request Body**: `{ "company": "Siam Tech Co., Ltd.", "vat": "TH01055...", "phonenumber": "+66 2 123 4567", "city": "Bangkok", "active": 1 }`
  - **Response Body**: Created Client Object with `userid` and `datecreated`.

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/clients/{client_id}/contacts`
  - **Request Body**: `{ "firstname": "Somchai", "lastname": "Jaidee", "email": "somchai@company.com", "phonenumber": "+66 81...", "title": "IT Director", "is_primary": 1 }`
  - **Response Body**: Created Contact Object.

---

### 2.4 Leads Pipeline & Conversion
- **Screen**: Leads Pipeline (`frontend/src/app/(dashboard)/leads/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/leads`
  - **Response Body**: Array of leads (ID, name, email, company, status, lead_value, source, assigned).

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/leads`
  - **Request Body**: `{ "name": "Supatra Corp", "email": "contact@supatra.com", "status": 1, "source": 1, "lead_value": 45000 }`

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/leads/{lead_id}/convert`
  - **Response Body**: Converted Lead Object containing linked `client_id`.

---

### 2.5 Active Tasks & Project Management
- **Screen**: Tasks & Projects (`frontend/src/app/(dashboard)/tasks/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/tasks`
  - **Response Body**: Array of tasks (id, name, priority, status, startdate, duedate, billable, hourly_rate).

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/tasks`
  - **Request Body**: `{ "name": "Deploy API", "priority": 3, "startdate": "2026-08-14", "status": 2 }`

---

### 2.6 Invoices & Billing Engine
- **Screen**: Invoices Directory (`frontend/src/app/(dashboard)/invoices/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/invoices`
  - **Response Body**: Array of invoice objects (id, clientid, number, prefix, date, duedate, subtotal, total, status, items, payments).

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/invoices`
  - **Request Body**: `{ "clientid": 101, "number": 1004, "prefix": "INV-2026-", "date": "2026-08-14", "subtotal": 50000, "total": 53500, "items": [...] }`

  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/invoices/{invoice_id}/payments`
  - **Request Body**: `{ "amount": 53500, "paymentmode": 1, "paymentmethod": "Bank Transfer", "date": "2026-08-14" }`
  - **Trigger**: Double-Entry Posting Engine updates Chart of Accounts ledger balances automatically.

---

### 2.7 Estimates & Quotations
- **Screen**: Estimates (`frontend/src/app/(dashboard)/estimates/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/estimates`
  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/estimates`

---

### 2.8 Double-Entry Accounting & Finance
- **Screen**: Accounting & Finance (`frontend/src/app/(dashboard)/accounting/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/accounting/summary`
  - **Response Body**: 
    ```json
    {
      "summary": { "assets": 1850000.00, "liabilities": 420000.00, "equity": 1430000.00, "netIncome": 345000.00 },
      "accounts": [ { "code": "1010", "name": "Cash on Hand & Bank", "type": "Asset", "balance": 650000.00 } ]
    }
    ```
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/accounting/accounts`

---

### 2.9 Warehouse & Inventory Management
- **Screen**: Warehouse & Stock (`frontend/src/app/(dashboard)/warehouse/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/warehouse/items`
  - **Response Body**: Array of items (id SKU-xxx, name, location, category, stock, minStock, unitPrice).

---

### 2.10 Purchase Management & Procurement
- **Screen**: Purchase Orders (`frontend/src/app/(dashboard)/purchase/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/purchase/orders`
  - **Response Body**: Array of purchase orders (poNumber, vendor, date, totalAmount, status, expectedDelivery).

---

### 2.11 Recruitment & HR
- **Screen**: Recruitment (`frontend/src/app/(dashboard)/recruitment/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/recruitment/overview`
  - **Response Body**: Object containing `jobOpenings` and `candidates`.

---

### 2.12 OKRs & Goal Management
- **Screen**: OKRs & Goals (`frontend/src/app/(dashboard)/okrs/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/okrs`
  - **Response Body**: Array of OKR objects (id, title, period, owner, progress, keyResults).

---

### 2.13 WooCommerce Connector Sync
- **Screen**: WooCommerce Sync (`frontend/src/app/(dashboard)/woocommerce/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/woocommerce/status`
  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/woocommerce/sync`

---

### 2.14 Account Planning
- **Screen**: Account Planning (`frontend/src/app/(dashboard)/account-planning/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/account-planning`
  - **Response Body**: Array of account plans (client, accountManager, tier, swot).

---

### 2.15 Staff Outsourcing
- **Screen**: Staff Outsourcing (`frontend/src/app/(dashboard)/staff-outsourcing/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/staff-outsourcing`
  - **Response Body**: Array of outsourced resources (name, role, rate, allocation, status, project).

---

### 2.16 Support Ticket Desk
- **Screen**: Support Desk (`frontend/src/app/(dashboard)/tickets/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/tickets`
  - **Response Body**: Array of support tickets (id, subject, client, priority, status, date).

---

### 2.17 System Settings
- **Screen**: System Settings (`frontend/src/app/(dashboard)/settings/page.tsx`)
  - **HTTP Method**: `GET`
  - **Endpoint**: `/api/v1/settings`
  - **HTTP Method**: `POST`
  - **Endpoint**: `/api/v1/settings`
