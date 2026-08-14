# Technical Architecture & Security Changes

## 1. Architectural Modernization
- **Legacy Stack**: PHP 7.4/8.1, CodeIgniter 3.x, MySQL 5.7/8.0, Server-Side Monolith Views (Bootstrap 3/4 + AdminLTE + jQuery DataTables).
- **Target Architecture**: Next.js 16.2 (React 19, TypeScript), FastAPI (Python 3.13, Async SQLAlchemy 2.0), PostgreSQL 16, Celery & Redis Task Queue.

---

## 2. Security Improvements (No Loss of Functionality)
- **Authentication**: Replaced PHP session cookies (`ci_session`) with OAuth2 JWT Bearer Tokens (`access_token`) and secure HTTP-Only Refresh Cookies.
- **SQL Injection Prevention**: Replaced legacy active-record string concatenations with Async SQLAlchemy ORM parameterized queries.
- **XSS Protection**: React 19 JSX auto-escaping eliminates inline JavaScript vulnerability risks.
- **WCAG AAA Color Contrast**: Scoped Dark & Light mode CSS variables in `globals.css` ensuring high contrast (`#ffffff`) text on dark action buttons across all views.

---

## 3. Performance Enhancements
- **Client-Side Cache**: React Query (`@tanstack/react-query`) handles background re-validation and optimistic UI updates.
- **Asynchronous Execution**: FastAPI `asyncdef` handles high-concurrency database I/O using PostgreSQL connection pooling.
- **Celery Task Offloading**: Heavy background jobs (e.g. WooCommerce inventory sync) offloaded to Celery background workers.
