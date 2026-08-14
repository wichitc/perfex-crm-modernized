import pytest
from httpx import AsyncClient, ASGITransport
from app.main import app

@pytest.mark.asyncio
async def test_api_root_contract():
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        response = await ac.get("/")
        assert response.status_code == 200
        data = response.json()
        assert data["status"] == "online"
        assert "message" in data
        assert "docs_url" in data

@pytest.mark.asyncio
async def test_dashboard_stats_contract(auth_headers):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        response = await ac.get("/api/v1/dashboard/stats", headers=auth_headers)
        assert response.status_code == 200
        data = response.json()
        assert "totalRevenue" in data
        assert "activeClients" in data
        assert "pendingInvoices" in data
        assert "openLeads" in data
        assert "revenueChart" in data
        assert isinstance(data["revenueChart"], list)
        assert "activeTasks" in data
        assert isinstance(data["activeTasks"], list)

@pytest.mark.asyncio
async def test_accounting_summary_contract(auth_headers):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        response = await ac.get("/api/v1/accounting/summary", headers=auth_headers)
        assert response.status_code == 200
        data = response.json()
        assert "summary" in data
        assert "accounts" in data
        assert "assets" in data["summary"]
        assert "liabilities" in data["summary"]
        assert "equity" in data["summary"]
        assert "netIncome" in data["summary"]
