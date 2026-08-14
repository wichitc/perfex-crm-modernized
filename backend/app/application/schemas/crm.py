from datetime import datetime, date
from typing import Optional, List
from pydantic import BaseModel, EmailStr, Field

# --- CONTACT SCHEMAS ---
class ContactBase(BaseModel):
    firstname: str
    lastname: str
    email: EmailStr
    phonenumber: str
    title: Optional[str] = None
    is_primary: int = 1

class ContactCreate(ContactBase):
    password: Optional[str] = None

class ContactUpdate(BaseModel):
    firstname: Optional[str] = None
    lastname: Optional[str] = None
    email: Optional[EmailStr] = None
    phonenumber: Optional[str] = None
    title: Optional[str] = None
    is_primary: Optional[int] = None
    password: Optional[str] = None

class ContactResponse(ContactBase):
    id: int
    userid: int
    datecreated: datetime

    class Config:
        from_attributes = True


# --- CLIENT SCHEMAS ---
class ClientBase(BaseModel):
    company: Optional[str] = None
    vat: Optional[str] = None
    phonenumber: Optional[str] = None
    country: int = 0
    city: Optional[str] = None
    zip: Optional[str] = None
    state: Optional[str] = None
    address: Optional[str] = None
    website: Optional[str] = None
    active: int = 1

class ClientCreate(ClientBase):
    pass

class ClientUpdate(BaseModel):
    company: Optional[str] = None
    vat: Optional[str] = None
    phonenumber: Optional[str] = None
    country: Optional[int] = None
    city: Optional[str] = None
    zip: Optional[str] = None
    state: Optional[str] = None
    address: Optional[str] = None
    website: Optional[str] = None
    active: Optional[int] = None

class ClientResponse(ClientBase):
    userid: int
    datecreated: datetime
    contacts: List[ContactResponse] = []

    class Config:
        from_attributes = True


# --- LEAD SCHEMAS ---
class LeadBase(BaseModel):
    name: str
    title: Optional[str] = None
    company: Optional[str] = None
    description: Optional[str] = None
    country: int = 0
    zip: Optional[str] = None
    city: Optional[str] = None
    state: Optional[str] = None
    address: Optional[str] = None
    assigned: int = 0
    status: int
    source: int
    email: Optional[EmailStr] = None
    website: Optional[str] = None
    phonenumber: Optional[str] = None
    lead_value: Optional[float] = None

class LeadCreate(LeadBase):
    pass

class LeadUpdate(BaseModel):
    name: Optional[str] = None
    title: Optional[str] = None
    company: Optional[str] = None
    description: Optional[str] = None
    country: Optional[int] = None
    zip: Optional[str] = None
    city: Optional[str] = None
    state: Optional[str] = None
    address: Optional[str] = None
    assigned: Optional[int] = None
    status: Optional[int] = None
    source: Optional[int] = None
    email: Optional[EmailStr] = None
    website: Optional[str] = None
    phonenumber: Optional[str] = None
    lead_value: Optional[float] = None
    lost: Optional[int] = None
    junk: Optional[int] = None

class LeadResponse(LeadBase):
    id: int
    dateadded: datetime
    lost: int
    junk: int
    client_id: int

    class Config:
        from_attributes = True


# --- TASK SCHEMAS ---
class TaskBase(BaseModel):
    name: str
    description: Optional[str] = None
    priority: Optional[int] = 2 # 1 = low, 2 = medium, 3 = high, 4 = urgent
    startdate: date
    duedate: Optional[date] = None
    status: int = 1 # 1 = not started, 2 = in progress, 3 = testing, 4 = awaiting feedback, 5 = complete
    billable: int = 0
    hourly_rate: float = 0.00
    visible_to_client: int = 0

class TaskCreate(TaskBase):
    rel_id: Optional[int] = None
    rel_type: Optional[str] = "project"

class TaskUpdate(BaseModel):
    name: Optional[str] = None
    description: Optional[str] = None
    priority: Optional[int] = None
    startdate: Optional[date] = None
    duedate: Optional[date] = None
    status: Optional[int] = None
    billable: Optional[int] = None
    hourly_rate: Optional[float] = None
    visible_to_client: Optional[int] = None

class TaskResponse(TaskBase):
    id: int
    dateadded: datetime
    datefinished: Optional[datetime] = None
    addedfrom: int
    rel_id: Optional[int] = None
    rel_type: Optional[str] = None

    class Config:
        from_attributes = True


# --- PROJECT SCHEMAS ---
class ProjectBase(BaseModel):
    name: str
    description: Optional[str] = None
    status: int = 0
    billing_type: int = 1
    start_date: date
    deadline: Optional[date] = None
    project_cost: Optional[float] = None
    project_rate_per_hour: Optional[float] = None
    estimated_hours: Optional[float] = None

class ProjectCreate(ProjectBase):
    clientid: int

class ProjectUpdate(BaseModel):
    name: Optional[str] = None
    description: Optional[str] = None
    status: Optional[int] = None
    billing_type: Optional[int] = None
    start_date: Optional[date] = None
    deadline: Optional[date] = None
    project_cost: Optional[float] = None
    project_rate_per_hour: Optional[float] = None
    estimated_hours: Optional[float] = None

class ProjectResponse(ProjectBase):
    id: int
    clientid: int
    project_created: date
    date_finished: Optional[datetime] = None
    progress: int
    addedfrom: int
    tasks: List[TaskResponse] = []

    class Config:
        from_attributes = True


# --- TICKET SCHEMAS ---
class TicketBase(BaseModel):
    subject: str
    message: Optional[str] = None
    department: int = 1
    priority: int = 1
    status: int = 1
    assigned: int = 0

class TicketCreate(TicketBase):
    userid: int
    contactid: int = 0
    email: Optional[EmailStr] = None
    name: Optional[str] = None
    project_id: int = 0

class TicketUpdate(BaseModel):
    subject: Optional[str] = None
    message: Optional[str] = None
    department: Optional[int] = None
    priority: Optional[int] = None
    status: Optional[int] = None
    assigned: Optional[int] = None
    lastreply: Optional[datetime] = None

class TicketResponse(TicketBase):
    ticketid: int
    userid: int
    contactid: int
    email: Optional[str] = None
    name: Optional[str] = None
    project_id: int
    ticketkey: str
    date: datetime
    lastreply: Optional[datetime] = None

    class Config:
        from_attributes = True
