from datetime import datetime, date
from typing import Optional
from sqlalchemy import String, Integer, DateTime, Date, DECIMAL, ForeignKey, SmallInteger
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base

class LeadStatus(Base):
    __tablename__ = "tblleads_status"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(50), nullable=False)
    statusorder: Mapped[int] = mapped_column(Integer, default=1)
    color: Mapped[str] = mapped_column(String(10), default="#757575")

class LeadSource(Base):
    __tablename__ = "tblleads_sources"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(150), nullable=False)

class Lead(Base):
    __tablename__ = "tblleads"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    hash: Mapped[Optional[str]] = mapped_column(String(65), nullable=True)
    name: Mapped[str] = mapped_column(String(191), nullable=False)
    title: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    company: Mapped[Optional[str]] = mapped_column(String(191), nullable=True)
    description: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    country: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    zip: Mapped[Optional[str]] = mapped_column(String(15), nullable=True)
    city: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    state: Mapped[Optional[str]] = mapped_column(String(50), nullable=True)
    address: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    assigned: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # FK to staffid
    dateadded: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    status: Mapped[int] = mapped_column(Integer, nullable=False) # FK to LeadStatus
    source: Mapped[int] = mapped_column(Integer, nullable=False) # FK to LeadSource
    lastcontact: Mapped[Optional[datetime]] = mapped_column(DateTime, nullable=True)
    dateassigned: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    last_status_change: Mapped[Optional[datetime]] = mapped_column(DateTime, nullable=True)
    addedfrom: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    email: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    website: Mapped[Optional[str]] = mapped_column(String(150), nullable=True)
    leadorder: Mapped[int] = mapped_column(Integer, default=1)
    phonenumber: Mapped[Optional[str]] = mapped_column(String(50), nullable=True)
    date_converted: Mapped[Optional[datetime]] = mapped_column(DateTime, nullable=True)
    lost: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False) # 1 if lost
    junk: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # 1 if junk
    is_public: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False)
    client_id: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # maps to Client userid when converted
    lead_value: Mapped[Optional[float]] = mapped_column(DECIMAL(15, 2), nullable=True)
