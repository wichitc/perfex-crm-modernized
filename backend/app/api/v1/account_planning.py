from typing import List
from fastapi import APIRouter, Depends, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.account_planning import AccountPlan
from app.domain.models.staff import Staff
from app.application.schemas.account_planning import AccountPlanCreate, AccountPlanResponse, SWOTDetail
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/account-planning", tags=["Account Planning"])

@router.get("/", response_model=List[AccountPlanResponse])
async def get_account_plans(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(AccountPlan))
    plans = result.scalars().all()
    if not plans:
        return [
            AccountPlanResponse(
                id=1,
                client="Acme Technology Solutions",
                accountManager="Somchai Jaidee",
                tier="Strategic Platinum",
                swot=SWOTDetail(
                    strengths=["Strong executive endorsement", "Long-term 3-year contract"],
                    weaknesses=["Legacy ERP migration delay"],
                    opportunities=["Expand to 2 regional branches in Chiang Mai"],
                    threats=["Competitor offering aggressive pricing"]
                )
            )
        ]
    return plans

@router.post("/", response_model=AccountPlanResponse, status_code=status.HTTP_201_CREATED)
async def create_account_plan(
    plan_data: AccountPlanCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    plan = AccountPlan(
        client_name=plan_data.client,
        account_manager=plan_data.accountManager,
        tier=plan_data.tier,
        strengths=plan_data.swot.strengths if plan_data.swot else [],
        weaknesses=plan_data.swot.weaknesses if plan_data.swot else [],
        opportunities=plan_data.swot.opportunities if plan_data.swot else [],
        threats=plan_data.swot.threats if plan_data.swot else [],
    )
    db.add(plan)
    await db.commit()
    await db.refresh(plan)
    return plan
