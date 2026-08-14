from datetime import datetime, date
from typing import Optional, List
from sqlalchemy import String, Integer, DateTime, Date, DECIMAL, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class Account(Base):
    __tablename__ = "tblacc_accounts"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(255), nullable=False)
    key_name: Mapped[Optional[str]] = mapped_column(String(255), nullable=True)
    number: Mapped[Optional[str]] = mapped_column(String(45), nullable=True)
    parent_account: Mapped[Optional[int]] = mapped_column(ForeignKey("tblacc_accounts.id", ondelete="SET NULL"), nullable=True)
    account_type_id: Mapped[int] = mapped_column(Integer, nullable=False) # 1 = Asset, 2 = Liability, 3 = Equity, 4 = Income, 5 = Expense
    account_detail_type_id: Mapped[int] = mapped_column(Integer, default=1, nullable=False)
    balance: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    balance_as_of: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    description: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    
    history_entries: Mapped[List["AccountHistory"]] = relationship("AccountHistory", back_populates="account_rel", cascade="all, delete-orphan")

class JournalEntry(Base):
    __tablename__ = "tblacc_journal_entries"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    number: Mapped[Optional[str]] = mapped_column(String(45), nullable=True)
    description: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    journal_date: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    amount: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    addedfrom: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)

class AccountHistory(Base):
    __tablename__ = "tblacc_account_history"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    account: Mapped[int] = mapped_column(ForeignKey("tblacc_accounts.id", ondelete="CASCADE"), nullable=False)
    debit: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    credit: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    description: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    rel_id: Mapped[Optional[int]] = mapped_column(Integer, nullable=True) # ID of invoice or journal entry
    rel_type: Mapped[Optional[str]] = mapped_column(String(45), nullable=True) # "invoice", "journal_entry"
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    addedfrom: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    customer: Mapped[Optional[int]] = mapped_column(ForeignKey("tblclients.userid", ondelete="SET NULL"), nullable=True)
    
    account_rel: Mapped["Account"] = relationship("Account", back_populates="history_entries")
