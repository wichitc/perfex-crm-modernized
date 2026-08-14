from typing import Optional, List, Dict
from pydantic import BaseModel

class SWOTDetail(BaseModel):
    strengths: List[str] = []
    weaknesses: List[str] = []
    opportunities: List[str] = []
    threats: List[str] = []

class AccountPlanCreate(BaseModel):
    client: str
    accountManager: str
    tier: str = "Strategic Platinum"
    swot: Optional[SWOTDetail] = None

class AccountPlanResponse(BaseModel):
    id: Optional[int] = None
    client: str
    accountManager: str
    tier: str
    swot: Optional[SWOTDetail] = None

    class Config:
        from_attributes = True
