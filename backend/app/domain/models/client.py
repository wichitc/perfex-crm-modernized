from datetime import datetime
from typing import Optional, List
from sqlalchemy import String, Integer, DateTime, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class Client(Base):
    __tablename__ = "tblclients"
    
    userid: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    company: Mapped[Optional[str]] = mapped_column(String(191), nullable=True)
    vat: Mapped[Optional[str]] = mapped_column(String(50), nullable=True)
    phonenumber: Mapped[Optional[str]] = mapped_column(String(30), nullable=True)
    country: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    city: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    zip: Mapped[Optional[str]] = mapped_column(String(15), nullable=True)
    state: Mapped[Optional[str]] = mapped_column(String(50), nullable=True)
    address: Mapped[Optional[str]] = mapped_column(String(191), nullable=True)
    website: Mapped[Optional[str]] = mapped_column(String(150), nullable=True)
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    active: Mapped[int] = mapped_column(Integer, default=1, nullable=False)
    leadid: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    billing_street: Mapped[Optional[str]] = mapped_column(String(200), nullable=True)
    billing_city: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    billing_state: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    billing_zip: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    billing_country: Mapped[Optional[int]] = mapped_column(Integer, default=0, nullable=True)
    
    contacts: Mapped[List["Contact"]] = relationship("Contact", back_populates="client", cascade="all, delete-orphan", lazy="selectin")
    projects: Mapped[List["Project"]] = relationship("Project", back_populates="client", cascade="all, delete-orphan", lazy="selectin")

class Contact(Base):
    __tablename__ = "tblcontacts"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    userid: Mapped[int] = mapped_column(ForeignKey("tblclients.userid", ondelete="CASCADE"), nullable=False)
    is_primary: Mapped[int] = mapped_column(Integer, default=1, nullable=False)
    firstname: Mapped[str] = mapped_column(String(191), nullable=False)
    lastname: Mapped[str] = mapped_column(String(191), nullable=False)
    email: Mapped[str] = mapped_column(String(100), unique=True, index=True, nullable=False)
    phonenumber: Mapped[str] = mapped_column(String(100), nullable=False)
    title: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    password: Mapped[Optional[str]] = mapped_column(String(255), nullable=True)
    is_not_staff: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 if client contact
    
    client: Mapped["Client"] = relationship("Client", back_populates="contacts")
