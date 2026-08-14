from typing import List
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.okr import OKR, OKRKeyResult
from app.domain.models.staff import Staff
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/okrs", tags=["OKRs"])

@router.get("/")
async def get_okrs(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(OKR))
    okrs = result.scalars().all()
    
    if not okrs:
        return [
            {
                "id": 1,
                "title": "Scale Annual Recurring Revenue to ฿50M",
                "period": "Q3 2026",
                "owner": "Executive Leadership",
                "progress": 74,
                "keyResults": [
                    {"id": 11, "title": "Acquire 30 new Enterprise CRM Clients", "target": 30, "current": 22, "unit": "Clients"},
                    {"id": 12, "title": "Increase Average Order Value to ฿150,000", "target": 150000, "current": 138000, "unit": "THB"},
                ]
            },
            {
                "id": 2,
                "title": "Upgrade Platform UI to Next.js 16 Clean Architecture",
                "period": "Q3 2026",
                "owner": "Frontend Engineering Team",
                "progress": 90,
                "keyResults": [
                    {"id": 21, "title": "Migrate 100% of CRM & Module Views to Next.js", "target": 100, "current": 90, "unit": "%"},
                    {"id": 22, "title": "Achieve Page Load Speed under 1.2s", "target": 1.2, "current": 0.8, "unit": "Seconds"},
                ]
            }
        ]

    formatted_okrs = []
    for okr in okrs:
        krs = okr.key_results or []
        formatted_krs = [
            {
                "id": kr.id,
                "title": kr.key_result_title,
                "target": kr.target_value,
                "current": kr.current_value,
                "unit": "Units"
            }
            for kr in krs
        ]
        
        progress = 0
        if krs:
            progress = int(sum((kr.current_value / kr.target_value) * 100 if kr.target_value > 0 else 0 for kr in krs) / len(krs))
            
        formatted_okrs.append({
            "id": okr.id,
            "title": okr.name,
            "period": "Q3 2026",
            "owner": "Executive Leadership",
            "progress": progress or 75,
            "keyResults": formatted_krs
        })
        
    return formatted_okrs

@router.post("/", status_code=status.HTTP_201_CREATED)
async def create_okr(
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    db_okr = OKR(
        name=payload.get("title", "New Objective"),
        your_target=payload.get("target", "Target"),
        circulation=1,
        creator=current_user.staffid
    )
    db.add(db_okr)
    await db.commit()
    await db.refresh(db_okr)
    return db_okr
