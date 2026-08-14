from fastapi import APIRouter, Depends
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.settings import SystemSetting
from app.domain.models.staff import Staff
from app.application.schemas.settings import SystemSettingUpdate, SystemSettingResponse
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/settings", tags=["Settings"])

@router.get("/", response_model=SystemSettingResponse)
async def get_settings(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(SystemSetting))
    rows = result.scalars().all()
    settings_dict = {r.name: r.value for r in rows if r.value}
    
    return SystemSettingResponse(
        company_name=settings_dict.get("company_name", "NOVIXA CRM Modernized"),
        company_domain=settings_dict.get("company_domain", "https://crm.company.com"),
        timezone=settings_dict.get("timezone", "Asia/Bangkok"),
        date_format=settings_dict.get("date_format", "Y-m-d"),
        currency=settings_dict.get("currency", "THB")
    )

@router.post("/", response_model=SystemSettingResponse)
async def update_settings(
    payload: SystemSettingUpdate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    for name, value in payload.settings.items():
        res = await db.execute(select(SystemSetting).where(SystemSetting.name == name))
        existing = res.scalar_one_or_none()
        if existing:
            existing.value = str(value)
        else:
            db.add(SystemSetting(name=name, value=str(value)))
            
    await db.commit()
    return await get_settings(db, current_user)
