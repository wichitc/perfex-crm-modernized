from typing import List
from fastapi import APIRouter, Depends, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.purchase import PurchaseOrder
from app.domain.models.staff import Staff
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/purchase", tags=["Purchase Management"])

@router.get("/orders")
async def get_purchase_orders_list(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(PurchaseOrder))
    pos = result.scalars().all()
    if not pos:
        return [
            {"poNumber": "PO-2026-089", "vendor": "Zebra Technologies Asia", "date": "2026-07-20", "totalAmount": 185000, "status": "Approved", "expectedDelivery": "2026-08-05"},
            {"poNumber": "PO-2026-090", "vendor": "Honeywell Thailand", "date": "2026-07-25", "totalAmount": 94000, "status": "Pending Approval", "expectedDelivery": "2026-08-10"},
            {"poNumber": "PO-2026-091", "vendor": "Epson Electronics Ltd", "date": "2026-07-28", "totalAmount": 42000, "status": "Received", "expectedDelivery": "2026-07-29"},
        ]
    return [
        {
            "poNumber": po.pur_order_number,
            "vendor": f"Vendor #{po.vendor}",
            "date": po.order_date.strftime("%Y-%m-%d") if po.order_date else "2026-07-20",
            "totalAmount": po.total,
            "status": "Approved" if po.status == 2 else "Pending Approval",
            "expectedDelivery": "2026-08-05"
        }
        for po in pos
    ]

@router.get("/")
async def get_purchase_orders(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(PurchaseOrder))
    return result.scalars().all()
