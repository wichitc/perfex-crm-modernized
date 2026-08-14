from typing import List
from fastapi import APIRouter, Depends, status
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
            OutsourcedStaffResponse(id=1, name="Phukhao Tech Consulting", role="React / Next.js Specialist", rate="฿1,800/hr", allocation="100%", status="Assigned", project="Perfex CRM Upgrade"),
            OutsourcedStaffResponse(id=2, name="Siam Cloud Solutions", role="DevOps Architect", rate="฿2,200/hr", allocation="50%", status="Assigned", project="AWS Infrastructure Migration"),
            OutsourcedStaffResponse(id=3, name="Innovate Design Studio", role="UI/UX Designer", rate="฿1,500/hr", allocation="0%", status="Available", project="-"),
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
