from typing import List
from fastapi import APIRouter, Depends, HTTPException, status
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

@router.post("/orders", status_code=status.HTTP_201_CREATED)
async def create_purchase_order(
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    po = PurchaseOrder(
        pur_order_number=payload.get("poNumber", "PO-2026-999"),
        vendor=1,
        total=payload.get("totalAmount", 50000.0),
        status=1
    )
    db.add(po)
    await db.commit()
    await db.refresh(po)
    return po

@router.put("/orders/{order_id}")
async def update_purchase_order(
    order_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(PurchaseOrder).where(PurchaseOrder.id == order_id))
    po = result.scalar_one_or_none()
    if not po:
        raise HTTPException(status_code=404, detail="Purchase Order not found")
    for k, v in payload.items():
        if hasattr(po, k):
            setattr(po, k, v)
    await db.commit()
    await db.refresh(po)
    return po

@router.delete("/orders/{order_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_purchase_order(
    order_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(PurchaseOrder).where(PurchaseOrder.id == order_id))
    po = result.scalar_one_or_none()
    if not po:
        raise HTTPException(status_code=404, detail="Purchase Order not found")
    await db.delete(po)
    await db.commit()
    return None

@router.get("/")
async def get_purchase_orders(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(PurchaseOrder))
    return result.scalars().all()
