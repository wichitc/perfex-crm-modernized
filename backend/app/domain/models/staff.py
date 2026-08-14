from datetime import datetime
from typing import Optional
from sqlalchemy import String, Integer, DateTime, DECIMAL, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class Role(Base):
    __tablename__ = "tblroles"
    
    roleid: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(150), nullable=False)
    permissions: Mapped[Optional[str]] = mapped_column(String, nullable=True) # JSON or serialized text
    
    staff: Mapped[list["Staff"]] = relationship(back_populates="role_rel")

class Staff(Base):
    __tablename__ = "tblstaff"
    
    staffid: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    email: Mapped[str] = mapped_column(String(100), unique=True, index=True, nullable=False)
    firstname: Mapped[str] = mapped_column(String(50), nullable=False)
    lastname: Mapped[str] = mapped_column(String(50), nullable=False)
    password: Mapped[str] = mapped_column(String(250), nullable=False)
    datecreated: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow)
    admin: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # 1 if admin, 0 otherwise
    role: Mapped[Optional[int]] = mapped_column(ForeignKey("tblroles.roleid", ondelete="SET NULL"), nullable=True)
    active: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 if active, 0 otherwise
    hourly_rate: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    
    role_rel: Mapped[Optional[Role]] = relationship(back_populates="staff")
