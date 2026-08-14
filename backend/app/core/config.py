import os
from pydantic_settings import BaseSettings, SettingsConfigDict
from pydantic import Field

class Settings(BaseSettings):
    # Try loading from .env relative to this file, or workspace root
    model_config = SettingsConfigDict(
        env_file=os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(os.path.dirname(__file__)))), ".env"),
        env_file_encoding="utf-8",
        extra="ignore"
    )

    POSTGRES_USER: str = "postgres"
    POSTGRES_PASSWORD: str = "postgres"
    POSTGRES_DB: str = "perfexcrm"
    
    # We default to local DB URL during development outside of Docker container
    DATABASE_URL_DEV: str = os.getenv("DATABASE_URL_DEV", "sqlite+aiosqlite:///./perfexcrm.db")
    DATABASE_URL: str = "postgresql+asyncpg://postgres:postgres@db:5432/perfexcrm"
    
    REDIS_URL_DEV: str = "redis://localhost:6379/0"
    REDIS_URL: str = "redis://redis:6379/0"
    
    SECRET_KEY: str = "super_secret_jwt_key_for_perfex_crm_rebuild_2026_07_22"
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 600
    REFRESH_TOKEN_EXPIRE_DAYS: int = 7
    
    @property
    def sync_database_url(self) -> str:
        url = self.DATABASE_URL_DEV if os.environ.get("RUNNING_IN_DOCKER") != "true" else self.DATABASE_URL
        return url.replace("+asyncpg", "").replace("+aiosqlite", "")

    @property
    def async_database_url(self) -> str:
        return self.DATABASE_URL_DEV if os.environ.get("RUNNING_IN_DOCKER") != "true" else self.DATABASE_URL

    @property
    def get_redis_url(self) -> str:
        return self.REDIS_URL_DEV if os.environ.get("RUNNING_IN_DOCKER") != "true" else self.REDIS_URL

settings = Settings()
