from typing import Optional, List
from pydantic import BaseModel, EmailStr

class LoginRequest(BaseModel):
    email: EmailStr
    password: str

class TokenResponse(BaseModel):
    access_token: str
    token_type: str = "bearer"

class UserMeResponse(BaseModel):
    staffid: int
    email: str
    firstname: str
    lastname: str
    admin: int
    role: Optional[int] = None
    scopes: List[str] = []

    class Config:
        from_attributes = True
