from datetime import datetime, date
from typing import Optional
from sqlalchemy import String, Integer, DateTime, Date, DECIMAL, Text
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base

class PurchaseOrder(Base):
    __tablename__ = "tblpur_orders"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    pur_order_name: Mapped[str] = mapped_column(String(100), nullable=False)
    vendor: Mapped[int] = mapped_column(Integer, nullable=False) # Vendor ID
    pur_order_number: Mapped[str] = mapped_column(String(30), nullable=False)
    order_date: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    status: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 = drafted, 2 = ordered, 3 = closed
    approve_status: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 = pending, 2 = approved, 3 = rejected
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    subtotal: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    total: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    addedfrom: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    vendornote: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
