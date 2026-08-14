from typing import Optional
from sqlalchemy import String, Integer, Float, Text
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base

class OutsourcedStaff(Base):
    __tablename__ = "tblstaff_outsourcing"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(191), nullable=False)
    role: Mapped[str] = mapped_column(String(191), nullable=False)
    rate: Mapped[str] = mapped_column(String(100), nullable=False)
    allocation: Mapped[str] = mapped_column(String(50), default="100%")
    status: Mapped[str] = mapped_column(String(50), default="Assigned") # Assigned / Available
    project: Mapped[str] = mapped_column(String(191), default="-")
