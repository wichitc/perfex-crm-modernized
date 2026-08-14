from sqlalchemy import String, Integer, Text
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base
from typing import Optional

class Warehouse(Base):
    __tablename__ = "tblwarehouse"
    
    warehouse_id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    warehouse_code: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    warehouse_name: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    warehouse_address: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    order: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    display: Mapped[Optional[int]] = mapped_column(Integer, default=1, nullable=True)
    note: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
