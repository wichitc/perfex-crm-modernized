from datetime import datetime, date
from typing import Optional, List
from pydantic import BaseModel, Field

# --- INVOICE LINE ITEM SCHEMAS ---
class InvoiceItemBase(BaseModel):
    description: str
    long_description: Optional[str] = None
    qty: float = 1.00
    rate: float = 0.00
    unit: Optional[str] = None

class InvoiceItemCreate(InvoiceItemBase):
    pass

class InvoiceItemResponse(InvoiceItemBase):
    id: int
    rel_id: int
    rel_type: str

    class Config:
        from_attributes = True


# --- INVOICE SCHEMAS ---
class InvoiceBase(BaseModel):
    clientid: int
    number: int
    prefix: Optional[str] = "INV-"
    date: date
    duedate: Optional[date] = None
    subtotal: float = 0.00
    total_tax: float = 0.00
    total: float = 0.00
    adjustment: float = 0.00
    clientnote: Optional[str] = None
    adminnote: Optional[str] = None
    status: int = 1 # 1 = unpaid, 2 = paid

class InvoiceCreate(InvoiceBase):
    items: List[InvoiceItemCreate] = []

class InvoiceUpdate(BaseModel):
    clientid: Optional[int] = None
    number: Optional[int] = None
    prefix: Optional[str] = None
    date: Optional[date] = None
    duedate: Optional[date] = None
    subtotal: Optional[float] = None
    total_tax: Optional[float] = None
    total: Optional[float] = None
    adjustment: Optional[float] = None
    clientnote: Optional[str] = None
    adminnote: Optional[str] = None
    status: Optional[int] = None

class InvoicePaymentResponse(BaseModel):
    id: int
    invoiceid: int
    amount: float
    paymentmode: Optional[str] = None
    paymentmethod: Optional[str] = None
    date: date
    daterecorded: datetime
    note: Optional[str] = None
    transactionid: Optional[str] = None

    class Config:
        from_attributes = True

class InvoiceResponse(InvoiceBase):
    id: int
    datecreated: datetime
    hash: str
    items: List[InvoiceItemResponse] = []
    payments: List[InvoicePaymentResponse] = []

    class Config:
        from_attributes = True


# --- INVOICE PAYMENT RECORD SCHEMAS ---
class InvoicePaymentCreate(BaseModel):
    amount: float
    paymentmode: Optional[str] = "bank"
    paymentmethod: Optional[str] = "Direct Deposit"
    date: date
    note: Optional[str] = None
    transactionid: Optional[str] = None
