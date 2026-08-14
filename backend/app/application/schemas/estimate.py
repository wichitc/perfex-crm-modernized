from typing import Optional, List
from datetime import date
from pydantic import BaseModel

class EstimateItemCreate(BaseModel):
    description: str
    qty: float = 1.0
    rate: float = 0.0

class EstimateItemResponse(EstimateItemCreate):
    id: int

    class Config:
        from_attributes = True

class EstimateCreate(BaseModel):
    clientid: int
    number: int
    prefix: str = "EST-2026-"
    date: date
    expirydate: Optional[date] = None
    subtotal: float = 0.0
    total: float = 0.0
    status: int = 1
    clientnote: Optional[str] = None
    items: List[EstimateItemCreate] = []

class EstimateResponse(BaseModel):
    id: int
    clientid: int
    number: int
    prefix: str
    date: date
    expirydate: Optional[date] = None
    subtotal: float
    total: float
    status: int
    clientnote: Optional[str] = None
    items: List[EstimateItemResponse] = []

    class Config:
        from_attributes = True
