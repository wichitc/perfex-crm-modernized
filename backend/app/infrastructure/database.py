import os
from typing import AsyncGenerator
from sqlalchemy.ext.asyncio import create_async_engine, async_sessionmaker, AsyncSession
from app.core.config import settings

db_url = settings.async_database_url

# Fallback to SQLite if specified or if postgres is not running locally
if "sqlite" in db_url:
    engine_kwargs = {
        "echo": False,
        "future": True,
    }
else:
    engine_kwargs = {
        "echo": False,
        "future": True,
        "pool_pre_ping": True,
        "pool_size": 10,
        "max_overflow": 20,
    }

async_engine = create_async_engine(db_url, **engine_kwargs)

# Async session maker
AsyncSessionLocal = async_sessionmaker(
    bind=async_engine,
    class_=AsyncSession,
    expire_on_commit=False,
    autocommit=False,
    autoflush=False
)

# Dependency to get DB session
async def get_db() -> AsyncGenerator[AsyncSession, None]:
    async with AsyncSessionLocal() as session:
        try:
            yield session
            await session.commit()
        except Exception:
            await session.rollback()
            raise
        finally:
            await session.close()
