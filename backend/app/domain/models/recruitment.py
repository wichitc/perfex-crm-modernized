from sqlalchemy import String, Integer, Text, Date
from sqlalchemy.orm import Mapped, mapped_column
from app.domain.models.base import Base
from datetime import date
from typing import Optional

class RecruitmentCampaign(Base):
    __tablename__ = "tblrec_campaign"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    campaign_name: Mapped[str] = mapped_column(String(255), nullable=False)
    position: Mapped[str] = mapped_column(String(255), nullable=False)
    department_id: Mapped[Optional[int]] = mapped_column(Integer, nullable=True)
    start_date: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    end_date: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    status: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 = active, 2 = closed
    description: Mapped[Optional[str]] = mapped_column(Text, nullable=True)

class RecruitmentCandidate(Base):
    __tablename__ = "tblrec_candidate"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    candidate_name: Mapped[str] = mapped_column(String(255), nullable=False)
    email: Mapped[str] = mapped_column(String(100), nullable=False)
    phonenumber: Mapped[Optional[str]] = mapped_column(String(50), nullable=True)
    campaign_id: Mapped[int] = mapped_column(Integer, nullable=False)
    status: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 = applied, 2 = interview scheduled, 3 = offered, 4 = rejected
    evaluation: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
