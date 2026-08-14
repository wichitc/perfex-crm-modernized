from typing import List
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.recruitment import RecruitmentCampaign, RecruitmentCandidate
from app.domain.models.staff import Staff
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/recruitment", tags=["Recruitment"])

@router.get("/overview")
async def get_recruitment_overview(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    campaign_res = await db.execute(select(RecruitmentCampaign))
    campaigns = campaign_res.scalars().all()
    
    cand_res = await db.execute(select(RecruitmentCandidate))
    candidates = cand_res.scalars().all()

    formatted_jobs = [
        {"id": c.id, "title": c.campaign_name, "department": "Engineering", "applicants": 18, "status": "Active" if c.status == 1 else "Draft"}
        for c in campaigns
    ] if campaigns else [
        {"id": 1, "title": "Senior Fullstack Next.js Developer", "department": "Engineering", "applicants": 18, "status": "Active"},
        {"id": 2, "title": "Enterprise Account Executive", "department": "Sales", "applicants": 12, "status": "Active"},
    ]

    formatted_candidates = [
        {"id": cd.id, "name": cd.candidate_name, "position": "Senior Developer", "stage": "Interview" if cd.status == 2 else "Applied", "rating": 4.8}
        for cd in candidates
    ] if candidates else [
        {"id": 101, "name": "Chaiwat Saelim", "position": "Senior Fullstack Next.js Developer", "stage": "Interview", "rating": 4.8},
        {"id": 102, "name": "Pornpimol Wong", "position": "Enterprise Account Executive", "stage": "Offered", "rating": 4.9},
    ]

    return {
        "jobOpenings": formatted_jobs,
        "candidates": formatted_candidates
    }

@router.post("/campaigns", status_code=status.HTTP_201_CREATED)
async def create_campaign(
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    campaign = RecruitmentCampaign(
        campaign_name=payload.get("title", "New Job Position"),
        status=1
    )
    db.add(campaign)
    await db.commit()
    await db.refresh(campaign)
    return campaign

@router.put("/campaigns/{campaign_id}")
async def update_campaign(
    campaign_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(RecruitmentCampaign).where(RecruitmentCampaign.id == campaign_id))
    campaign = result.scalar_one_or_none()
    if not campaign:
        raise HTTPException(status_code=404, detail="Campaign not found")
    for k, v in payload.items():
        if hasattr(campaign, k):
            setattr(campaign, k, v)
    await db.commit()
    await db.refresh(campaign)
    return campaign

@router.delete("/campaigns/{campaign_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_campaign(
    campaign_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(RecruitmentCampaign).where(RecruitmentCampaign.id == campaign_id))
    campaign = result.scalar_one_or_none()
    if not campaign:
        raise HTTPException(status_code=404, detail="Campaign not found")
    await db.delete(campaign)
    await db.commit()
    return None
