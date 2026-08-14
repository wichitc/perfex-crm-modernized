# System Architecture & Technical Design

This document details the software architecture, layer dependencies, directory layout, dual DB engine fallback strategy, and design patterns of the modernized Perfex CRM platform.

---

## 1. High-Level Architecture Diagram

```text
┌──────────────────────────────────────────────────────────────────┐
│                   Next.js 16 App Router Frontend                 │
│         (React 19, Tailwind CSS, TanStack Query, Axios)          │
└─────────────────────────────────┬────────────────────────────────┘
                                  │ HTTPS REST v1 API Requests
                                  ▼
┌──────────────────────────────────────────────────────────────────┐
│                      FastAPI Application                         │
│   ┌──────────────────────────────────────────────────────────┐   │
│   │  Presentation Layer (app/api/v1/*.py Routers)            │   │
│   └─────────────────────────────┬────────────────────────────┘   │
│                                 │ Dependency Injection           │
│   ┌─────────────────────────────▼────────────────────────────┐   │
│   │  Application Layer (app/application/schemas & services) │   │
│   └─────────────────────────────┬────────────────────────────┘   │
│                                 │ Business Logic & Validation    │
│   ┌─────────────────────────────▼────────────────────────────┐   │
│   │  Domain Layer (app/domain/models/*.py SQLAlchemy ORM)    │   │
│   └─────────────────────────────┬────────────────────────────┘   │
│                                 │ Async Engine & Sessions        │
│   ┌─────────────────────────────▼────────────────────────────┐   │
│   │  Infrastructure Layer (app/infrastructure/database.py)   │   │
│   └─────────────────────────────┬────────────────────────────┘   │
└─────────────────────────────────┼────────────────────────────────┘
                                  │
                  ┌───────────────┴───────────────┐
                  ▼                               ▼
       PostgreSQL (Asyncpg)            SQLite (aiosqlite)
     (Production & Docker)          (Dev / Out-of-the-Box Fallback)
```

---

## 2. Directory Layout & Layer Responsibilities

```text
backend/app/
├── api/
│   ├── dependencies.py          # Auth & session dependencies
│   └── v1/                      # API Endpoints (17 Domain Routers)
│       ├── auth.py
│       ├── dashboard.py
│       ├── clients.py
│       ├── leads.py
│       ├── tasks.py
│       ├── projects.py
│       ├── tickets.py
│       ├── invoices.py
│       ├── estimates.py
│       ├── accounting.py
│       ├── warehouse.py
│       ├── purchase.py
│       ├── woocommerce.py
│       ├── recruitment.py
│       ├── okrs.py
│       ├── account_planning.py
│       ├── staff_outsourcing.py
│       └── settings.py
│
├── application/
│   ├── schemas/                 # Pydantic v2 DTO Request/Response Schemas
│   └── services/                # Use case services & posting engine
│
├── core/
│   ├── config.py                # Pydantic Settings & Environment
│   └── security.py              # JWT & BCrypt functions
│
├── domain/
│   └── models/                  # Declarative SQLAlchemy ORM Entities
│
└── infrastructure/
    ├── database.py              # Dual DB async engine (PostgreSQL/SQLite)
    └── seed.py                  # Auto-seed startup initial data
```

---

## 3. Dual Database Engine Strategy
To guarantee seamless execution in both production Docker environments and local developer machines:
- Default `async_database_url` connects to PostgreSQL via `asyncpg`.
- If PostgreSQL is offline during development outside Docker, the system seamlessly falls back to async SQLite (`sqlite+aiosqlite:///./perfexcrm.db`).
- Database metadata creation (`Base.metadata.create_all`) and initial seeding (`seed_data()`) run automatically on FastAPI startup.
