from typing import Optional, List
from datetime import date
from sqlalchemy import String, Integer, Float, Date, Text, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class Estimate(Base):
    __tablename__ = "tblestimates"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True, autoincrement=True)
    clientid: Mapped[int] = mapped_column(Integer, ForeignKey("tblclients.userid"), nullable=False)
    number: Mapped[int] = mapped_column(Integer, nullable=False)
    prefix: Mapped[str] = mapped_column(String(50), default="EST-2026-")
    date: Mapped[date] = mapped_column(Date, nullable=False)
    expirydate: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    subtotal: Mapped[float] = mapped_column(Float, default=0.0)
    total: Mapped[float] = mapped_column(Float, default=0.0)
    status: Mapped[int] = mapped_column(Integer, default=1) # 1=Draft, 2=Sent, 3=Accepted, 4=Declined
    clientnote: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    
    client: Mapped["Client"] = relationship("Client")
    items: Mapped[List["EstimateItem"]] = relationship("EstimateItem", back_populates="estimate", cascade="all, delete-orphan")

class EstimateItem(Base):
    __tablename__ = "tblestimate_items"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True, autoincrement=True)
    estimate_id: Mapped[int] = mapped_column(Integer, ForeignKey("tblestimates.id", ondelete="CASCADE"), nullable=False)
    description: Mapped[str] = mapped_column(Text, nullable=False)
    qty: Mapped[float] = mapped_column(Float, default=1.0)
    rate: Mapped[float] = mapped_column(Float, default=0.0)

    estimate: Mapped["Estimate"] = relationship("Estimate", back_populates="items")
