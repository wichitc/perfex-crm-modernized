from typing import List
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.staff_outsourcing import OutsourcedStaff
from app.domain.models.staff import Staff
from app.application.schemas.staff_outsourcing import OutsourcedStaffCreate, OutsourcedStaffResponse
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/staff-outsourcing", tags=["Staff Outsourcing"])

@router.get("/", response_model=List[OutsourcedStaffResponse])
async def get_outsourced_staff(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(OutsourcedStaff))
    staff_list = result.scalars().all()
    if not staff_list:
        return [
            OutsourcedStaffResponse(id=1, name="Phukhao Tech Consulting", role="React / Next.js Specialist", rate="฿1,800/hr", allocation="100%", status="Assigned", project="NOVIXA CRM Upgrade"),
            OutsourcedStaffResponse(id=2, name="Siam Cloud Solutions", role="DevOps Architect", rate="฿2,200/hr", allocation="50%", status="Assigned", project="AWS Infrastructure Migration"),
        ]
    return staff_list

@router.post("/", response_model=OutsourcedStaffResponse, status_code=status.HTTP_201_CREATED)
async def create_outsourced_staff(
    staff_data: OutsourcedStaffCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    staff = OutsourcedStaff(**staff_data.model_dump())
    db.add(staff)
    await db.commit()
    await db.refresh(staff)
    return staff

@router.put("/{staff_id}", response_model=OutsourcedStaffResponse)
async def update_outsourced_staff(
    staff_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(OutsourcedStaff).where(OutsourcedStaff.id == staff_id))
    staff = result.scalar_one_or_none()
    if not staff:
        raise HTTPException(status_code=404, detail="Outsourced staff not found")
    for k, v in payload.items():
        if hasattr(staff, k):
            setattr(staff, k, v)
    await db.commit()
    await db.refresh(staff)
    return staff

@router.delete("/{staff_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_outsourced_staff(
    staff_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(OutsourcedStaff).where(OutsourcedStaff.id == staff_id))
    staff = result.scalar_one_or_none()
    if not staff:
        raise HTTPException(status_code=404, detail="Outsourced staff not found")
    await db.delete(staff)
    await db.commit()
    return None
