from datetime import timedelta
from typing import Optional
from fastapi import APIRouter, Depends, HTTPException, status, Response, Cookie
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.staff import Staff, Role
from app.application.schemas.auth import LoginRequest, TokenResponse, UserMeResponse
from app.core.security import verify_password, create_access_token, create_refresh_token, decode_token
from app.api.dependencies import get_current_user
from app.core.config import settings

router = APIRouter(prefix="/auth", tags=["Authentication"])

@router.post("/login", response_model=TokenResponse)
async def login(
    login_data: LoginRequest,
    response: Response,
    db: AsyncSession = Depends(get_db)
):
    # Retrieve user from DB
    result = await db.execute(select(Staff).where(Staff.email == login_data.email))
    staff = result.scalar_one_or_none()
    
    if not staff or not verify_password(login_data.password, staff.password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect email or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
        
    if staff.active != 1:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Inactive user account"
        )
        
    # Get user scopes/roles
    scopes = []
    if staff.admin == 1:
        scopes = ["admin", "core:read", "core:write", "accounting:all", "hrm:all", "recruitment:all"]
    else:
        scopes = ["core:read", "core:write"]
        
    # Generate tokens
    access_token_expires = timedelta(minutes=settings.ACCESS_TOKEN_EXPIRE_MINUTES)
    access_token = create_access_token(
        subject=staff.staffid,
        email=staff.email,
        role="Admin" if staff.admin == 1 else "Staff",
        scopes=scopes,
        expires_delta=access_token_expires
    )
    
    refresh_token = create_refresh_token(subject=staff.staffid)
    
    # Set HTTP-only cookie for refresh token
    response.set_cookie(
        key="refreshtoken",
        value=refresh_token,
        httponly=True,
        max_age=settings.REFRESH_TOKEN_EXPIRE_DAYS * 24 * 3600,
        expires=settings.REFRESH_TOKEN_EXPIRE_DAYS * 24 * 3600,
        samesite="strict",
        secure=False  # Set to True in production (HTTPS only)
    )
    
    return {"access_token": access_token, "token_type": "bearer"}

@router.post("/refresh", response_model=TokenResponse)
async def refresh_token(
    response: Response,
    refreshtoken: Optional[str] = Cookie(None),
    db: AsyncSession = Depends(get_db)
):
    if not refreshtoken:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Refresh token missing"
        )
        
    payload = decode_token(refreshtoken)
    if not payload or payload.get("type") != "refresh":
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid refresh token"
        )
        
    staff_id = payload.get("sub")
    if not staff_id:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid refresh token payload"
        )
        
    # Verify user still active
    result = await db.execute(select(Staff).where(Staff.staffid == int(staff_id)))
    staff = result.scalar_one_or_none()
    
    if not staff or staff.active != 1:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="User not found or inactive"
        )
        
    # Re-issue tokens
    scopes = []
    if staff.admin == 1:
        scopes = ["admin", "core:read", "core:write", "accounting:all", "hrm:all", "recruitment:all"]
    else:
        scopes = ["core:read", "core:write"]
        
    access_token = create_access_token(
        subject=staff.staffid,
        email=staff.email,
        role="Admin" if staff.admin == 1 else "Staff",
        scopes=scopes
    )
    
    new_refresh_token = create_refresh_token(subject=staff.staffid)
    
    response.set_cookie(
        key="refreshtoken",
        value=new_refresh_token,
        httponly=True,
        max_age=settings.REFRESH_TOKEN_EXPIRE_DAYS * 24 * 3600,
        expires=settings.REFRESH_TOKEN_EXPIRE_DAYS * 24 * 3600,
        samesite="strict",
        secure=False
    )
    
    return {"access_token": access_token, "token_type": "bearer"}

@router.post("/logout")
async def logout(response: Response):
    response.delete_cookie(key="refreshtoken")
    return {"detail": "Successfully logged out"}

@router.get("/me", response_model=UserMeResponse)
async def get_me(current_user: Staff = Depends(get_current_user)):
    scopes = []
    if current_user.admin == 1:
        scopes = ["admin", "core:read", "core:write", "accounting:all", "hrm:all", "recruitment:all"]
    else:
        scopes = ["core:read", "core:write"]
        
    return {
        "staffid": current_user.staffid,
        "email": current_user.email,
        "firstname": current_user.firstname,
        "lastname": current_user.lastname,
        "admin": current_user.admin,
        "role": current_user.role,
        "scopes": scopes
    }
