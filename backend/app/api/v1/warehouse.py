from typing import List
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.warehouse import Warehouse
from app.domain.models.staff import Staff
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/warehouse", tags=["Warehouse"])

@router.get("/items")
async def get_warehouse_items(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    return [
        {"id": "SKU-001", "name": "Barcode Scanner Handheld 2D", "location": "Bangkok Central Hub", "category": "Hardware", "stock": 145, "minStock": 20, "unitPrice": 3500},
        {"id": "SKU-002", "name": "Thermal Receipt Printer 80mm", "location": "Nonthaburi Depot", "category": "Hardware", "stock": 82, "minStock": 15, "unitPrice": 4200},
        {"id": "SKU-003", "name": "Smart RFID Asset Tags (Pack of 100)", "location": "Chonburi Warehouse", "category": "Consumables", "stock": 12, "minStock": 25, "unitPrice": 1800},
        {"id": "SKU-004", "name": "Wireless Logistics Tablet 10-inch", "location": "Bangkok Central Hub", "category": "Devices", "stock": 34, "minStock": 10, "unitPrice": 12500},
    ]

@router.post("/items", status_code=status.HTTP_201_CREATED)
async def create_warehouse_item(
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    return {
        "id": f"SKU-{payload.get('sku', '999')}",
        "name": payload.get("name", "New Warehouse Item"),
        "location": payload.get("location", "Bangkok Central Hub"),
        "category": payload.get("category", "General"),
        "stock": payload.get("stock", 50),
        "minStock": payload.get("minStock", 10),
        "unitPrice": payload.get("unitPrice", 1000)
    }

@router.put("/items/{item_id}")
async def update_warehouse_item(
    item_id: str,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    return {"id": item_id, "status": "updated", **payload}

@router.delete("/items/{item_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_warehouse_item(
    item_id: str,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    return None

@router.get("/")
async def get_warehouses(
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Warehouse))
    return result.scalars().all()
