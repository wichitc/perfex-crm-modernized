import pytest
from httpx import AsyncClient, ASGITransport
from sqlalchemy.ext.asyncio import AsyncSession
from app.domain.models.warehouse import Warehouse
from app.main import app

@pytest.mark.asyncio
async def test_logistics_and_woocommerce_flow(db_session: AsyncSession, auth_headers: dict):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        # 1. Test Warehouse items
        items_res = await ac.get("/api/v1/warehouse/items", headers=auth_headers)
        assert items_res.status_code == 200
        assert len(items_res.json()) >= 1

        # 2. Test Purchase orders
        po_res = await ac.get("/api/v1/purchase/orders", headers=auth_headers)
        assert po_res.status_code == 200
        assert len(po_res.json()) >= 1

        # 3. Test WooCommerce status
        wc_res = await ac.get("/api/v1/woocommerce/status", headers=auth_headers)
        assert wc_res.status_code == 200
        assert wc_res.json()["connected"] is True
