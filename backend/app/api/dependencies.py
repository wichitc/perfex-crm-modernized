from typing import Optional
import jwt
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.core.config import settings
from app.core.security import decode_token
from app.infrastructure.database import get_db
from app.domain.models.staff import Staff

# Token extraction schema
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="api/v1/auth/login", auto_error=False)

async def get_current_user(
    token: Optional[str] = Depends(oauth2_scheme),
    db: AsyncSession = Depends(get_db)
) -> Staff:
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    
    if not token:
        raise credentials_exception
        
    payload = decode_token(token)
    if not payload:
        raise credentials_exception
        
    staff_id: str = payload.get("sub")
    if staff_id is None:
        raise credentials_exception
        
    # Query staff from DB
    result = await db.execute(select(Staff).where(Staff.staffid == int(staff_id)))
    staff = result.scalar_one_or_none()
    
    if staff is None:
        raise credentials_exception
        
    if staff.active != 1:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Inactive user account"
        )
        
    return staff
