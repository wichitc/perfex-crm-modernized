from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.staff import Staff
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/hr", tags=["Human Resources"])

@router.get("/overview")
async def get_hr_overview(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Staff))
    staff_list = result.scalars().all()

    employees = [
        {
            "id": f"EMP-{s.staffid:03d}",
            "name": f"{s.firstname} {s.lastname}",
            "department": "Engineering" if s.staffid % 2 == 1 else "Human Resources",
            "position": "Lead Architect" if s.admin == 1 else "Senior Specialist",
            "type": "Full-Time",
            "salary": f"฿{80000 + (s.staffid * 5000):,}",
            "status": "Active" if s.active == 1 else "On Leave",
            "email": s.email
        }
        for s in staff_list
    ]

    if not employees:
        employees = [
            { "id": "EMP-001", "name": "Somchai Jaidee", "department": "Engineering", "position": "Lead Architect", "type": "Full-Time", "salary": "฿120,000", "status": "Active", "email": "somchai@novixacrm.com" },
            { "id": "EMP-002", "name": "Ananya Srisuk", "department": "Human Resources", "position": "HR Manager", "type": "Full-Time", "salary": "฿85,000", "status": "Active", "email": "ananya@novixacrm.com" },
            { "id": "EMP-003", "name": "Kittisak Vong", "department": "Sales & Business", "position": "Account Director", "type": "Full-Time", "salary": "฿105,000", "status": "On Leave", "email": "kittisak@novixacrm.com" },
        ]

    active_count = sum(1 for e in employees if e["status"] == "Active")
    on_leave_count = sum(1 for e in employees if e["status"] == "On Leave")

    return {
        "stats": {
            "totalEmployees": len(employees),
            "activeStaff": active_count,
            "onLeave": on_leave_count,
            "openRequisitions": 5
        },
        "employees": employees
    }

@router.post("/employees", status_code=status.HTTP_201_CREATED)
async def create_employee(
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    names = payload.get("name", "New Employee").split(" ", 1)
    firstname = names[0]
    lastname = names[1] if len(names) > 1 else "Staff"
    db_staff = Staff(
        firstname=firstname,
        lastname=lastname,
        email=payload.get("email", f"{firstname.lower()}@novixacrm.com"),
        phonenumber=payload.get("phone", ""),
        active=1
    )
    db.add(db_staff)
    await db.commit()
    await db.refresh(db_staff)
    return db_staff

@router.put("/employees/{staff_id}")
async def update_employee(
    staff_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Staff).where(Staff.staffid == staff_id))
    staff = result.scalar_one_or_none()
    if not staff:
        raise HTTPException(status_code=404, detail="Staff not found")
    for k, v in payload.items():
        if hasattr(staff, k):
            setattr(staff, k, v)
    await db.commit()
    await db.refresh(staff)
    return staff

@router.delete("/employees/{staff_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_employee(
    staff_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Staff).where(Staff.staffid == staff_id))
    staff = result.scalar_one_or_none()
    if not staff:
        raise HTTPException(status_code=404, detail="Staff not found")
    await db.delete(staff)
    await db.commit()
    return None
