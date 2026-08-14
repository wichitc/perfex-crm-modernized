from fastapi import APIRouter, Depends
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
            { "id": "EMP-004", "name": "Pornpimol Wong", "department": "Marketing", "position": "Content Strategist", "type": "Full-Time", "salary": "฿65,000", "status": "Active", "email": "pornpimol@novixacrm.com" },
            { "id": "EMP-005", "name": "Chaiwat Saelim", "department": "Engineering", "position": "Senior Fullstack Dev", "type": "Full-Time", "salary": "฿95,000", "status": "Active", "email": "chaiwat@novixacrm.com" },
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
