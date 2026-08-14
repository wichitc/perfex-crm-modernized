from datetime import datetime, date
from typing import Optional, List
from sqlalchemy import String, Integer, DateTime, Date, DECIMAL, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class Invoice(Base):
    __tablename__ = "tblinvoices"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    clientid: Mapped[int] = mapped_column(ForeignKey("tblclients.userid", ondelete="CASCADE"), nullable=False)
    number: Mapped[int] = mapped_column(Integer, nullable=False)
    prefix: Mapped[Optional[str]] = mapped_column(String(50), nullable=True)
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    date: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    duedate: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    subtotal: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    total_tax: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    total: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    adjustment: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    status: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 = unpaid, 2 = paid, 3 = partially paid, 4 = cancelled, 5 = overdue
    hash: Mapped[str] = mapped_column(String(32), nullable=False)
    clientnote: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    adminnote: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    addedfrom: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    
    client: Mapped["Client"] = relationship("Client", lazy="selectin")
    items: Mapped[List["InvoiceItem"]] = relationship("InvoiceItem", back_populates="invoice", cascade="all, delete-orphan", lazy="selectin")
    payments: Mapped[List["InvoicePayment"]] = relationship("InvoicePayment", back_populates="invoice", cascade="all, delete-orphan", lazy="selectin")

class InvoiceItem(Base):
    __tablename__ = "tblitemable"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    rel_id: Mapped[int] = mapped_column(ForeignKey("tblinvoices.id", ondelete="CASCADE"), nullable=False)
    rel_type: Mapped[str] = mapped_column(String(15), default="invoice", nullable=False)
    description: Mapped[str] = mapped_column(String, nullable=False) # Item name
    long_description: Mapped[Optional[str]] = mapped_column(String, nullable=True) # Item details
    qty: Mapped[float] = mapped_column(DECIMAL(15, 2), default=1.00, nullable=False)
    rate: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    unit: Mapped[Optional[str]] = mapped_column(String(40), nullable=True)
    item_order: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    
    invoice: Mapped["Invoice"] = relationship("Invoice", back_populates="items")

class InvoicePayment(Base):
    __tablename__ = "tblinvoicepaymentrecords"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    invoiceid: Mapped[int] = mapped_column(ForeignKey("tblinvoices.id", ondelete="CASCADE"), nullable=False)
    amount: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    paymentmode: Mapped[Optional[str]] = mapped_column(String(40), nullable=True)
    paymentmethod: Mapped[Optional[str]] = mapped_column(String(191), nullable=True)
    date: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    daterecorded: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    note: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    transactionid: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    
    invoice: Mapped["Invoice"] = relationship("Invoice", back_populates="payments")
