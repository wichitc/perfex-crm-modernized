from typing import List
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.estimate import Estimate, EstimateItem
from app.domain.models.staff import Staff
from app.application.schemas.estimate import EstimateCreate, EstimateResponse
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/estimates", tags=["Estimates"])

@router.get("/", response_model=List[EstimateResponse])
async def get_estimates(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Estimate))
    return result.scalars().all()

@router.post("/", response_model=EstimateResponse, status_code=status.HTTP_201_CREATED)
async def create_estimate(
    est_data: EstimateCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    estimate = Estimate(
        clientid=est_data.clientid,
        number=est_data.number,
        prefix=est_data.prefix,
        date=est_data.date,
        expirydate=est_data.expirydate,
        subtotal=est_data.subtotal,
        total=est_data.total,
        status=est_data.status,
        clientnote=est_data.clientnote
    )
    db.add(estimate)
    await db.flush()

    for item_data in est_data.items:
        item = EstimateItem(
            estimate_id=estimate.id,
            description=item_data.description,
            qty=item_data.qty,
            rate=item_data.rate
        )
        db.add(item)

    await db.commit()
    await db.refresh(estimate)
    return estimate

@router.put("/{estimate_id}", response_model=EstimateResponse)
async def update_estimate(
    estimate_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Estimate).where(Estimate.id == estimate_id))
    estimate = result.scalar_one_or_none()
    if not estimate:
        raise HTTPException(status_code=404, detail="Estimate not found")
    for k, v in payload.items():
        if hasattr(estimate, k):
            setattr(estimate, k, v)
    await db.commit()
    await db.refresh(estimate)
    return estimate

@router.delete("/{estimate_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_estimate(
    estimate_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Estimate).where(Estimate.id == estimate_id))
    estimate = result.scalar_one_or_none()
    if not estimate:
        raise HTTPException(status_code=404, detail="Estimate not found")
    await db.delete(estimate)
    await db.commit()
    return None
