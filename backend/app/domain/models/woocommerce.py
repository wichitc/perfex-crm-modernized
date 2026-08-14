from datetime import datetime
from typing import Optional
from sqlalchemy import String, Integer, DateTime
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base

class WooCommerceOrder(Base):
    __tablename__ = "tblwoocommerce_orders"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    order_id: Mapped[int] = mapped_column(Integer, nullable=False)
    order_number: Mapped[str] = mapped_column(String(50), nullable=False)
    customer_id: Mapped[int] = mapped_column(Integer, nullable=False)
    status: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    total: Mapped[Optional[str]] = mapped_column(String(30), nullable=True)
    invoice_id: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    store_id: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    last_synced_at: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
