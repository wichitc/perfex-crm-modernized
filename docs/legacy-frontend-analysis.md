# Legacy Frontend Analysis - Perfex CRM

## 1. Executive Summary
This document provides a comprehensive reverse-engineering audit of the legacy **Perfex CRM** frontend application structure located in `perfex_crm/application/views/admin/` and `perfex_crm/assets/`.

---

## 2. Tech Stack & Architecture Audit

```text
LEGACY FRONTEND STACK
├── Framework: CodeIgniter PHP Views (HTML5 / PHP Templates)
├── UI Styling: Bootstrap 3.x / Custom CSS Assets (style.css, reset.css)
├── Dynamic Interactivity: jQuery, DataTables.js, Select2, Moment.js, Chart.js
├── Iconography: FontAwesome 4.7 / LineAwesome
└── Theme Engines: Classic Teal (#28b8da), Dark, Light, Green, Orange, Purple
```

---

## 3. Directory Structure Mapping

```text
perfex_crm/application/views/admin/
├── dashboard/               # Main Dashboard overview, widgets, quick stats
├── clients/                 # Client directory, contact management, vault, statement
├── leads/                   # Kanban pipeline, lead status, lead conversion, web-to-lead forms
├── invoices/                # Invoice builder, payment recording, recurring invoices, PDF view
├── estimates/               # Quotation estimates, estimate requests
├── proposals/               # Interactive proposals, client e-signatures
├── credit_notes/            # Credit note issue and refund logs
├── expenses/                # Expenses logging, billable status, recurring expenses
├── contracts/               # Contract agreements, attachments, e-signatures
├── projects/                # Project overview, Gantt chart, milestones, task lists, timesheets
├── tasks/                   # Task Kanban board, checklists, time tracking loggers
├── tickets/                 # Support desk queue, ticket replies, canned responses
├── knowledge_base/          # Knowledge base articles, group categories
├── subscriptions/          # Recurring subscription billing plans
├── staff/                   # Staff profiles, permissions, department assignments
├── reports/                 # Sales, invoices, expenses, lead conversion reports
├── settings/                # System settings, company profile, email templates, theme options
└── includes/                # Shared header, sidebar, footer, navigation scripts
```

---

## 4. UI Layout & Component Architecture

### Layout Grid & Regions
- **Left Navigation Sidebar**: Fixed 240px width collapsible sidebar with multi-level dropdowns and status badges.
- **Top Bar**: Search trigger, notification popover, quick create dropdown, staff avatar, theme switcher.
- **Content Container**: Fluid container with breadcrumbs, action toolbar, data tables, and modal overlays.
- **Modals & Drawers**: Slide-over panels for quick-edit leads, tasks, and client contact forms.

---

## 5. Reverse Engineering Strategy for Next.js 16

1. **Strict 1:1 Parity**: Replicate exact layout boundaries, colors, typography, icon mappings, and modal behavior.
2. **Zero UX Degradation**: Ensure legacy Perfex CRM users experience 100% feature and layout familiarity.
3. **Decoupled Architecture**: Transition from PHP rendered templates to React 19 Client/Server components connected via RESTful API.
