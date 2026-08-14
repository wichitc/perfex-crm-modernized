from contextlib import asynccontextmanager
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.api.v1 import (
    auth,
    dashboard,
    clients,
    leads,
    tasks,
    projects,
    tickets,
    invoices,
    estimates,
    accounting,
    warehouse,
    purchase,
    woocommerce,
    recruitment,
    hr,
    okrs,
    account_planning,
    staff_outsourcing,
    settings as settings_router
)
from app.core.config import settings
from app.infrastructure.seed import seed_data

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Auto initialize DB tables and seed data on startup
    try:
        await seed_data()
    except Exception as e:
        print(f"Startup DB seed log: {e}")
    yield

app = FastAPI(
    title="NOVIXA CRM Modernized API",
    description="Modernized AI-Ready Clean Architecture API for NOVIXA CRM (Python FastAPI & Next.js)",
    version="1.0.0",
    lifespan=lifespan
)

# CORS configurations to allow frontend connections
origins = [
    "http://localhost:3000",
    "http://127.0.0.1:3000",
    "*"
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Include routers
app.include_router(auth.router, prefix="/api/v1")
app.include_router(dashboard.router, prefix="/api/v1")
app.include_router(clients.router, prefix="/api/v1")
app.include_router(leads.router, prefix="/api/v1")
app.include_router(tasks.router, prefix="/api/v1")
app.include_router(projects.router, prefix="/api/v1")
app.include_router(tickets.router, prefix="/api/v1")
app.include_router(invoices.router, prefix="/api/v1")
app.include_router(estimates.router, prefix="/api/v1")
app.include_router(accounting.router, prefix="/api/v1")
app.include_router(warehouse.router, prefix="/api/v1")
app.include_router(purchase.router, prefix="/api/v1")
app.include_router(woocommerce.router, prefix="/api/v1")
app.include_router(recruitment.router, prefix="/api/v1")
app.include_router(hr.router, prefix="/api/v1")
app.include_router(okrs.router, prefix="/api/v1")
app.include_router(account_planning.router, prefix="/api/v1")
app.include_router(staff_outsourcing.router, prefix="/api/v1")
app.include_router(settings_router.router, prefix="/api/v1")

@app.get("/")
async def root():
    return {
        "status": "online",
        "message": "Welcome to NOVIXA CRM Modernized API",
        "docs_url": "/docs"
    }
