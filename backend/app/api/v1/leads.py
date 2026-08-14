import uuid
from typing import List, Optional
from datetime import datetime
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.lead import Lead, LeadStatus, LeadSource
from app.domain.models.client import Client, Contact
from app.domain.models.staff import Staff
from app.application.schemas.crm import LeadCreate, LeadUpdate, LeadResponse
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/leads", tags=["Leads"])

@router.get("/", response_model=List[LeadResponse])
async def get_leads(
    offset: int = 0,
    limit: int = 100,
    status_id: Optional[int] = None,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    query = select(Lead)
    if status_id is not None:
        query = query.where(Lead.status == status_id)
        
    result = await db.execute(query.offset(offset).limit(limit))
    return result.scalars().all()

@router.post("/", response_model=LeadResponse, status_code=status.HTTP_201_CREATED)
async def create_lead(
    lead_data: LeadCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    db_lead = Lead(
        hash=str(uuid.uuid4()),
        addedfrom=current_user.staffid,
        **lead_data.model_dump()
    )
    db.add(db_lead)
    await db.commit()
    await db.refresh(db_lead)
    return db_lead

@router.get("/{lead_id}", response_model=LeadResponse)
async def get_lead(
    lead_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Lead).where(Lead.id == lead_id))
    lead = result.scalar_one_or_none()
    if not lead:
        raise HTTPException(status_code=404, detail="Lead not found")
    return lead

@router.put("/{lead_id}", response_model=LeadResponse)
async def update_lead(
    lead_id: int,
    lead_data: LeadUpdate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Lead).where(Lead.id == lead_id))
    lead = result.scalar_one_or_none()
    if not lead:
        raise HTTPException(status_code=404, detail="Lead not found")
        
    for key, value in lead_data.model_dump(exclude_unset=True).items():
        setattr(lead, key, value)
        
    if "status" in lead_data.model_dump(exclude_unset=True):
        lead.last_status_change = datetime.utcnow()
        
    await db.commit()
    await db.refresh(lead)
    return lead

@router.delete("/{lead_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_lead(
    lead_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Lead).where(Lead.id == lead_id))
    lead = result.scalar_one_or_none()
    if not lead:
        raise HTTPException(status_code=404, detail="Lead not found")
        
    await db.delete(lead)
    await db.commit()
    return None

@router.post("/{lead_id}/convert", response_model=LeadResponse)
async def convert_lead_to_client(
    lead_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Lead).where(Lead.id == lead_id))
    lead = result.scalar_one_or_none()
    if not lead:
        raise HTTPException(status_code=404, detail="Lead not found")
        
    if lead.client_id > 0:
        raise HTTPException(status_code=400, detail="Lead has already been converted")
        
    # Create new client/contact transactionally
    client = Client(
        company=lead.company or f"Company-{lead.name}",
        phonenumber=lead.phonenumber,
        country=lead.country,
        city=lead.city,
        zip=lead.zip,
        state=lead.state,
        address=lead.address,
        website=lead.website,
        active=1,
        leadid=lead.id
    )
    db.add(client)
    await db.flush() # Populate userid
    
    # Split name into first and last name
    names = lead.name.split(" ", 1)
    firstname = names[0]
    lastname = names[1] if len(names) > 1 else "Converted"
    
    contact = Contact(
        userid=client.userid,
        firstname=firstname,
        lastname=lastname,
        email=lead.email or f"contact-{client.userid}@crm.com",
        phonenumber=lead.phonenumber or "",
        is_primary=1,
        is_not_staff=1
    )
    db.add(contact)
    
    # Update lead status
    lead.client_id = client.userid
    lead.date_converted = datetime.utcnow()
    
    await db.commit()
    await db.refresh(lead)
    return lead
