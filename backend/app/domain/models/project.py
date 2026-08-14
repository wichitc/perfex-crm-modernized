from datetime import datetime, date
from typing import Optional, List
from sqlalchemy import String, Integer, DateTime, Date, DECIMAL, ForeignKey, SmallInteger
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.domain.models.base import Base

class Project(Base):
    __tablename__ = "tblprojects"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(191), nullable=False)
    description: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    status: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    clientid: Mapped[int] = mapped_column(ForeignKey("tblclients.userid", ondelete="CASCADE"), nullable=False)
    billing_type: Mapped[int] = mapped_column(Integer, default=1, nullable=False) # 1 = fixed, 2 = project hours, 3 = task hours
    start_date: Mapped[date] = mapped_column(Date, nullable=False)
    deadline: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    project_created: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    date_finished: Mapped[Optional[datetime]] = mapped_column(DateTime, nullable=True)
    progress: Mapped[int] = mapped_column(Integer, default=0)
    project_cost: Mapped[Optional[float]] = mapped_column(DECIMAL(15, 2), nullable=True)
    project_rate_per_hour: Mapped[Optional[float]] = mapped_column(DECIMAL(15, 2), nullable=True)
    estimated_hours: Mapped[Optional[float]] = mapped_column(DECIMAL(15, 2), nullable=True)
    addedfrom: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    
    client: Mapped["Client"] = relationship("Client", back_populates="projects")
    tasks: Mapped[List["Task"]] = relationship("Task", back_populates="project", cascade="all, delete-orphan")

class Task(Base):
    __tablename__ = "tbltasks"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String, nullable=False)
    description: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    priority: Mapped[Optional[int]] = mapped_column(Integer, nullable=True) # 1 = low, 2 = medium, 3 = high, 4 = urgent
    dateadded: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow, nullable=False)
    startdate: Mapped[date] = mapped_column(Date, default=date.today, nullable=False)
    duedate: Mapped[Optional[date]] = mapped_column(Date, nullable=True)
    datefinished: Mapped[Optional[datetime]] = mapped_column(DateTime, nullable=True)
    addedfrom: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    is_added_from_contact: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False)
    status: Mapped[int] = mapped_column(Integer, default=0, nullable=False) # 1 = not started, 2 = in progress, 3 = testing, 4 = awaiting feedback, 5 = complete
    rel_id: Mapped[Optional[int]] = mapped_column(ForeignKey("tblprojects.id", ondelete="CASCADE"), nullable=True)
    rel_type: Mapped[Optional[str]] = mapped_column(String(30), default="project", nullable=True)
    is_public: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False)
    billable: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False)
    billed: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False)
    invoice_id: Mapped[int] = mapped_column(Integer, default=0, nullable=False)
    hourly_rate: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    kanban_order: Mapped[int] = mapped_column(Integer, default=1)
    visible_to_client: Mapped[int] = mapped_column(SmallInteger, default=0, nullable=False)
    
    project: Mapped[Optional[Project]] = relationship("Project", back_populates="tasks")
    timers: Mapped[List["TaskTimer"]] = relationship("TaskTimer", back_populates="task", cascade="all, delete-orphan")

class TaskTimer(Base):
    __tablename__ = "tbltaskstimers"
    
    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    task_id: Mapped[int] = mapped_column(ForeignKey("tbltasks.id", ondelete="CASCADE"), nullable=False)
    start_time: Mapped[str] = mapped_column(String(64), nullable=False)
    end_time: Mapped[Optional[str]] = mapped_column(String(64), nullable=True)
    staff_id: Mapped[int] = mapped_column(Integer, nullable=False)
    hourly_rate: Mapped[float] = mapped_column(DECIMAL(15, 2), default=0.00, nullable=False)
    note: Mapped[Optional[str]] = mapped_column(String, nullable=True)
    
    task: Mapped["Task"] = relationship("Task", back_populates="timers")
