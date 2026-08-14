import pytest
import os
import asyncio
from typing import AsyncGenerator
from sqlalchemy.ext.asyncio import create_async_engine, async_sessionmaker, AsyncSession
from sqlalchemy.pool import StaticPool
from app.domain.models.base import Base
from app.infrastructure.database import get_db
from app.infrastructure.seed import seed_data
from app.main import app
from app.domain.models.staff import Staff, Role
from app.domain.models.client import Client, Contact
from app.domain.models.lead import Lead
from app.domain.models.project import Project, Task
from app.domain.models.ticket import Ticket
from app.domain.models.invoice import Invoice, InvoiceItem, InvoicePayment
from app.domain.models.accounting import Account, JournalEntry, AccountHistory
from app.domain.models.warehouse import Warehouse
from app.domain.models.purchase import PurchaseOrder
from app.domain.models.woocommerce import WooCommerceOrder
from app.domain.models.recruitment import RecruitmentCampaign, RecruitmentCandidate
from app.domain.models.okr import OKR, OKRKeyResult
from app.domain.models.account_planning import AccountPlan
from app.domain.models.staff_outsourcing import OutsourcedStaff
from app.domain.models.estimate import Estimate, EstimateItem
from app.domain.models.settings import SystemSetting

TEST_DATABASE_URL = "sqlite+aiosqlite:///:memory:"

@pytest.fixture(scope="session")
def event_loop():
    loop = asyncio.get_event_loop_policy().new_event_loop()
    yield loop
    loop.close()

@pytest.fixture(scope="session")
async def db_engine(event_loop):
    engine = create_async_engine(
        TEST_DATABASE_URL,
        connect_args={"check_same_thread": False},
        poolclass=StaticPool,
        echo=False
    )
    async with engine.begin() as conn:
        await conn.run_sync(Base.metadata.create_all)
    
    # Run initial seed
    AsyncSessionLocal = async_sessionmaker(bind=engine, class_=AsyncSession, expire_on_commit=False)
    async with AsyncSessionLocal() as session:
        admin_role = Role(name="Administrator", permissions="all")
        session.add(admin_role)
        await session.flush()
        
        from app.core.security import get_password_hash
        admin = Staff(
            email="admin@crm.com",
            firstname="System",
            lastname="Administrator",
            password=get_password_hash("admin_password"),
            admin=1,
            role=admin_role.roleid,
            active=1
        )
        session.add(admin)
        await session.commit()

    yield engine
    await engine.dispose()

@pytest.fixture
async def db_session(db_engine) -> AsyncGenerator[AsyncSession, None]:
    async_session = async_sessionmaker(db_engine, expire_on_commit=False, class_=AsyncSession)
    async with async_session() as session:
        yield session

@pytest.fixture(autouse=True)
async def override_db(db_engine):
    async_session = async_sessionmaker(db_engine, expire_on_commit=False, class_=AsyncSession)
    async def _get_db():
        async with async_session() as session:
            try:
                yield session
                await session.commit()
            except Exception:
                await session.rollback()
                raise
    app.dependency_overrides[get_db] = _get_db
    yield
    app.dependency_overrides.pop(get_db, None)

@pytest.fixture
def auth_headers():
    from app.core.security import create_access_token
    token = create_access_token(subject=1, email="admin@crm.com", role="Admin", scopes=["admin"])
    return {"Authorization": f"Bearer {token}"}
