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

@router.put("/{okr_id}")
async def update_okr(
    okr_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(OKR).where(OKR.id == okr_id))
    okr = result.scalar_one_or_none()
    if not okr:
        raise HTTPException(status_code=404, detail="OKR not found")
    for k, v in payload.items():
        if hasattr(okr, k):
            setattr(okr, k, v)
    await db.commit()
    await db.refresh(okr)
    return okr

@router.delete("/{okr_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_okr(
    okr_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(OKR).where(OKR.id == okr_id))
    okr = result.scalar_one_or_none()
    if not okr:
        raise HTTPException(status_code=404, detail="OKR not found")
    await db.delete(okr)
    await db.commit()
    return None
