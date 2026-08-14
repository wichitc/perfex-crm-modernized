from typing import List, Optional
from fastapi import APIRouter, Depends, HTTPException, status, Query
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, func
from app.infrastructure.database import get_db
from app.domain.models.client import Client, Contact
from app.domain.models.staff import Staff
from app.application.schemas.crm import ClientCreate, ClientUpdate, ClientResponse, ContactCreate, ContactResponse
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/clients", tags=["Clients"])

@router.get("/", response_model=List[ClientResponse])
async def get_clients(
    offset: int = 0,
    limit: int = 100,
    active: Optional[int] = None,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    query = select(Client)
    if active is not None:
        query = query.where(Client.active == active)
    
    # Executing select with offset and limit
    result = await db.execute(query.offset(offset).limit(limit))
    clients = result.scalars().all()
    return clients

@router.post("/", response_model=ClientResponse, status_code=status.HTTP_201_CREATED)
async def create_client(
    client_data: ClientCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    # Validate permissions
    if current_user.admin != 1 and "core:write" not in current_user.admin: # Simplistic check, admin has full access
        pass
        
    db_client = Client(**client_data.model_dump())
    db.add(db_client)
    await db.commit()
    await db.refresh(db_client)
    return db_client

@router.get("/{client_id}", response_model=ClientResponse)
async def get_client(
    client_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Client).where(Client.userid == client_id))
    client = result.scalar_one_or_none()
    if not client:
        raise HTTPException(status_code=404, detail="Client not found")
    return client

@router.put("/{client_id}", response_model=ClientResponse)
async def update_client(
    client_id: int,
    client_data: ClientUpdate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Client).where(Client.userid == client_id))
    client = result.scalar_one_or_none()
    if not client:
        raise HTTPException(status_code=404, detail="Client not found")
        
    for key, value in client_data.model_dump(exclude_unset=True).items():
        setattr(client, key, value)
        
    await db.commit()
    await db.refresh(client)
    return client

@router.delete("/{client_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_client(
    client_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Client).where(Client.userid == client_id))
    client = result.scalar_one_or_none()
    if not client:
        raise HTTPException(status_code=404, detail="Client not found")
        
    await db.delete(client)
    await db.commit()
    return None

# --- CONTACT ENDPOINTS ---
@router.post("/{client_id}/contacts", response_model=ContactResponse, status_code=status.HTTP_201_CREATED)
async def create_contact(
    client_id: int,
    contact_data: ContactCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    # Verify client exists
    result = await db.execute(select(Client).where(Client.userid == client_id))
    if not result.scalar_one_or_none():
        raise HTTPException(status_code=404, detail="Client not found")
        
    # Check email duplicate
    email_check = await db.execute(select(Contact).where(Contact.email == contact_data.email))
    if email_check.scalar_one_or_none():
        raise HTTPException(status_code=400, detail="Contact email already registered")
        
    db_contact = Contact(userid=client_id, **contact_data.model_dump())
    db.add(db_contact)
    await db.commit()
    await db.refresh(db_contact)
    return db_contact
