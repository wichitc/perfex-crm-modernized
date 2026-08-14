from datetime import datetime, date
from typing import Optional
from pydantic import BaseModel

# --- WAREHOUSE SCHEMAS ---
class WarehouseResponse(BaseModel):
    warehouse_id: int
    warehouse_code: Optional[str] = None
    warehouse_name: Optional[str] = None
    warehouse_address: Optional[str] = None
    display: Optional[int] = None
    note: Optional[str] = None

    class Config:
        from_attributes = True


# --- PURCHASE SCHEMAS ---
class PurchaseOrderCreate(BaseModel):
    pur_order_name: str
    vendor: int
    pur_order_number: str
    order_date: date
    subtotal: float
    total: float
    vendornote: Optional[str] = None

class PurchaseOrderResponse(BaseModel):
    id: int
    pur_order_name: str
    vendor: int
    pur_order_number: str
    order_date: date
    status: int
    approve_status: int
    datecreated: datetime
    subtotal: float
    total: float
    addedfrom: int
    vendornote: Optional[str] = None

    class Config:
        from_attributes = True


# --- WOOCOMMERCE SCHEMAS ---
class WooCommerceOrderResponse(BaseModel):
    id: int
    order_id: int
    order_number: str
    customer_id: int
    status: Optional[str] = None
    total: Optional[str] = None
    invoice_id: Optional[int] = None
    store_id: Optional[int] = None
    last_synced_at: datetime

    class Config:
        from_attributes = True
