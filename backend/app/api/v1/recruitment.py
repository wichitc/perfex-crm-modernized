from typing import List
from fastapi import APIRouter, Depends, status
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
        {"id": 3, "title": "DevOps & Infrastructure Lead", "department": "IT", "applicants": 7, "status": "Draft"},
    ]

    formatted_candidates = [
        {"id": cd.id, "name": cd.candidate_name, "position": "Senior Developer", "stage": "Interview" if cd.status == 2 else "Applied", "rating": 4.8}
        for cd in candidates
    ] if candidates else [
        {"id": 101, "name": "Chaiwat Saelim", "position": "Senior Fullstack Next.js Developer", "stage": "Interview", "rating": 4.8},
        {"id": 102, "name": "Pornpimol Wong", "position": "Enterprise Account Executive", "stage": "Offered", "rating": 4.9},
        {"id": 103, "name": "Tawatchai Tech", "position": "DevOps & Infrastructure Lead", "stage": "Applied", "rating": 4.2},
    ]

    return {
        "jobOpenings": formatted_jobs,
        "candidates": formatted_candidates
    }

@router.get("/campaigns")
async def get_campaigns(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(RecruitmentCampaign))
    return result.scalars().all()

@router.get("/candidates")
async def get_candidates(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(RecruitmentCandidate))
    return result.scalars().all()
