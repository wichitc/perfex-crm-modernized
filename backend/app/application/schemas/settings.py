from typing import Optional, Dict
from pydantic import BaseModel

class SystemSettingUpdate(BaseModel):
    settings: Dict[str, str]

class SystemSettingResponse(BaseModel):
    company_name: str = "Perfex CRM"
    company_domain: str = "https://crm.company.com"
    timezone: str = "Asia/Bangkok"
    date_format: str = "Y-m-d"
    currency: str = "THB"
