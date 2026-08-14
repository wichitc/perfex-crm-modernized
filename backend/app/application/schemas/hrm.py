from datetime import datetime, date
from typing import Optional, List
from pydantic import BaseModel

# --- RECRUITMENT SCHEMAS ---
class CampaignResponse(BaseModel):
    id: int
    campaign_name: str
    position: str
    department_id: Optional[int] = None
    start_date: date
    end_date: Optional[date] = None
    status: int
    description: Optional[str] = None

    class Config:
        from_attributes = True

class CandidateCreate(BaseModel):
    candidate_name: str
    email: str
    phonenumber: Optional[str] = None
    campaign_id: int
    status: int = 1
    evaluation: Optional[str] = None

class CandidateResponse(BaseModel):
    id: int
    candidate_name: str
    email: str
    phonenumber: Optional[str] = None
    campaign_id: int
    status: int
    evaluation: Optional[str] = None

    class Config:
        from_attributes = True


# --- OKR SCHEMAS ---
class OKRKeyResultResponse(BaseModel):
    id: int
    okrs_id: int
    key_result_title: str
    target_value: float
    current_value: float
    confidence_level: int

    class Config:
        from_attributes = True

class OKRKeyResultUpdate(BaseModel):
    current_value: float
    confidence_level: Optional[int] = None

class OKRCreate(BaseModel):
    name: str
    your_target: str
    circulation: int = 1

class OKRResponse(BaseModel):
    id: int
    name: str
    circulation: int
    okr_superior: Optional[str] = None
    your_target: str
    okr_cross: Optional[str] = None
    display: Optional[int] = None
    creator: int
    datecreator: datetime
    key_results: List[OKRKeyResultResponse] = []

    class Config:
        from_attributes = True
