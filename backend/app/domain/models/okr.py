from datetime import datetime
from typing import Optional, List
from sqlalchemy import String, Integer, DateTime, Text, DECIMAL, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class OKR(Base):
    __tablename__ = "tblokrs"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(Text, nullable=False) # Objective title
    circulation: Mapped[int] = mapped_column(Integer, default=1, nullable=False)
    okr_superior: Mapped[Optional[str]] = mapped_column(Text, nullable=True) # Superior Objective Linkage
    your_target: Mapped[str] = mapped_column(String(250), nullable=False) # Target metric summary
    okr_cross: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    display: Mapped[Optional[int]] = mapped_column(Integer, default=1, nullable=True)
    creator: Mapped[int] = mapped_column(Integer, default=1, nullable=False)
    datecreator: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    
    key_results: Mapped[List["OKRKeyResult"]] = relationship("OKRKeyResult", back_populates="okr", cascade="all, delete-orphan")

class OKRKeyResult(Base):
    __tablename__ = "tblokrs_key_result"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    okrs_id: Mapped[int] = mapped_column(ForeignKey("tblokrs.id", ondelete="CASCADE"), nullable=False)
    key_result_title: Mapped[str] = mapped_column(Text, nullable=False)
    target_value: Mapped[float] = mapped_column(DECIMAL(15, 2), default=100.00, nullable=False)
    current_value: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    confidence_level: Mapped[int] = mapped_column(Integer, default=5, nullable=False) # 1 to 10
    
    okr: Mapped["OKR"] = relationship("OKR", back_populates="key_results")
