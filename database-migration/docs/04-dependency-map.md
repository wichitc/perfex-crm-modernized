# Phase 2: Database & Application Dependency Map

```text
[ tblclients ] <──── (1:N) ──── [ tblcontacts ]
      │
      ├─────── (1:N) ──── [ tblinvoices ] ──── (1:N) ──── [ tblinvoicepaymentrecords ]
      │                         │
      │                         └──────── (1:N) ──── [ tblitems_in ]
      │
      ├─────── (1:N) ──── [ tblestimates ]
      │
      └─────── (1:N) ──── [ tblprojects ] ──── (1:N) ──── [ tbltasks ]
```

## Application Dependency Layer
- **Backend Framework**: Python 3.14 FastAPI Modernization API (`backend/app`)
- **ORM / Driver**: SQLAlchemy + AsyncPG (PostgreSQL native driver)
- **API Models**: Pydantic v2 domain schemas mapped directly to PostgreSQL types
