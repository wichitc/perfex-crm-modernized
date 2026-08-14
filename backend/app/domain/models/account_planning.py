from typing import Optional
from sqlalchemy import String, Integer, Text, ForeignKey, JSON
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class AccountPlan(Base):
    __tablename__ = "tblaccount_plans"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True, autoincrement=True)
    client_name: Mapped[str] = mapped_column(String(191), nullable=False)
    account_manager: Mapped[str] = mapped_column(String(191), nullable=False)
    tier: Mapped[str] = mapped_column(String(100), default="Strategic Platinum")
    strengths: Mapped[Optional[dict]] = mapped_column(JSON, nullable=True)
    weaknesses: Mapped[Optional[dict]] = mapped_column(JSON, nullable=True)
    opportunities: Mapped[Optional[dict]] = mapped_column(JSON, nullable=True)
    threats: Mapped[Optional[dict]] = mapped_column(JSON, nullable=True)
