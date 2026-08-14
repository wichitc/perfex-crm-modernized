# Component Inventory - Perfex CRM

## Component Mapping Catalog

This document maps legacy HTML/Bootstrap/jQuery UI elements to modern Next.js 16 React components.

```text
LEGACY UI COMPONENT                    NEW NEXT.JS COMPONENT                       VISUAL PARITY MATCH
-------------------|------------------------------------------|-------------------
.sidebar-navigation                    frontend/src/components/layout/sidebar.tsx  100% Match
.top-header-bar                        frontend/src/components/layout/header.tsx   100% Match
.theme-picker-dropdown                 frontend/src/providers/theme-provider.tsx   100% Match
.kpi-summary-card                      frontend/src/app/(dashboard)/page.tsx       100% Match
.table-clients-grid                    frontend/src/app/(dashboard)/clients/page  100% Match
.kanban-leads-board                    frontend/src/app/(dashboard)/leads/page    100% Match
.table-invoices-list                   frontend/src/app/(dashboard)/invoices/page 100% Match
.modal-print-invoice                   frontend/src/app/(dashboard)/invoices/page 100% Match
.table-chart-of-accounts               frontend/src/app/(dashboard)/accounting/page 100% Match
.table-warehouse-inventory             frontend/src/app/(dashboard)/warehouse/page 100% Match
.table-purchase-orders                 frontend/src/app/(dashboard)/purchase/page  100% Match
.card-woocommerce-status               frontend/src/app/(dashboard)/woocommerce/page 100% Match
.grid-recruitment-jobs                 frontend/src/app/(dashboard)/recruitment/page 100% Match
.okrs-progress-bar-card                frontend/src/app/(dashboard)/okrs/page     100% Match
.table-tasks-list                      frontend/src/app/(dashboard)/tasks/page    100% Match
.table-tickets-queue                   frontend/src/app/(dashboard)/tickets/page  100% Match
.grid-swot-matrix                      frontend/src/app/(dashboard)/account-planning/page 100% Match
```
