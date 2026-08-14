from datetime import datetime
from typing import Optional
from sqlalchemy import String, Integer, DateTime, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base

class TicketStatus(Base):
    __tablename__ = "tbltickets_status"
    
    ticketstatusid: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(50), nullable=False)
    isdefault: Mapped[int] = mapped_column(Integer, default=0)
    statusorder: Mapped[int] = mapped_column(Integer, default=1)

class TicketPriority(Base):
    __tablename__ = "tbltickets_priorities"
    
    priorityid: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(50), nullable=False)

class Ticket(Base):
    __tablename__ = "tbltickets"
    
    ticketid: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    adminreplying: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    userid: Mapped[int] = mapped_column(ForeignKey("tblclients.userid", ondelete="CASCADE"), nullable=False)
    contactid: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # FK to contacts
    email: Mapped[Optional[str]] = mapped_column(String(100), nullable=True)
    name: Mapped[Optional[str]] = mapped_column(String(191), nullable=True)
    department: Mapped[int] = mapped_column(Integer, default=1, nullable=False)
    priority: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # FK to TicketPriority
    status: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # FK to TicketStatus
    ticketkey: Mapped[str] = mapped_column(String(32), nullable=False)
    subject: Mapped[str] = mapped_column(String(191), nullable=False)
    message: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    date: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    project_id: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    lastreply: Mapped[Optional[datetime]] = mapped_column(DateTime, nullable=True)
    assigned: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # FK to staffid
