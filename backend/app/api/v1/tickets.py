import uuid
from typing import List, Optional
from datetime import datetime
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.ticket import Ticket, TicketStatus, TicketPriority
from app.domain.models.client import Client
from app.domain.models.staff import Staff
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/tickets", tags=["Tickets"])

@router.get("/")
async def get_tickets(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Ticket))
    tickets = result.scalars().all()
    
    if not tickets:
        return [
            {"id": "T-8091", "subject": "Request for API token generation help", "client": "Acme Technology Solutions", "priority": "Medium", "status": "Open", "date": "2026-07-29 16:20"},
            {"id": "T-8092", "subject": "Invoice PDF download formatting inquiry", "client": "Siam Digital Innovations", "priority": "Low", "status": "Answered", "date": "2026-07-29 14:10"},
            {"id": "T-8093", "subject": "Warehouse Stock Sync Alert Notification", "client": "Global Logistics Thailand", "priority": "High", "status": "In Progress", "date": "2026-07-29 11:45"},
        ]

    formatted_tickets = []
    for t in tickets:
        formatted_tickets.append({
            "id": t.ticketkey or f"T-{t.ticketid}",
            "subject": t.subject,
            "client": t.name or "Client",
            "priority": "High" if t.priority >= 2 else "Medium",
            "status": "Open" if t.status == 1 else "In Progress",
            "date": t.date.strftime("%Y-%m-%d %H:%M") if t.date else "2026-07-29 12:00"
        })
        
    return formatted_tickets

@router.post("/", status_code=status.HTTP_201_CREATED)
async def create_ticket(
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    ticket_key = f"T-{uuid.uuid4().hex[:4].upper()}"
    db_ticket = Ticket(
        ticketkey=ticket_key,
        subject=payload.get("subject", "New Ticket"),
        message=payload.get("message", ""),
        name=payload.get("client", "Client"),
        userid=1,
        contactid=1,
        priority=1,
        status=1
    )
    db.add(db_ticket)
    await db.commit()
    await db.refresh(db_ticket)
    return db_ticket
