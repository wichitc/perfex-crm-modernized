from typing import Optional
from pydantic import BaseModel

class OutsourcedStaffCreate(BaseModel):
    name: str
    role: str
    rate: str
    allocation: str = "100%"
    status: str = "Assigned"
    project: str = "-"

class OutsourcedStaffResponse(BaseModel):
    id: int
    name: str
    role: str
    rate: str
    allocation: str
    status: str
    project: str

    class Config:
        from_attributes = True
