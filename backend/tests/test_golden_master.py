import pytest
from httpx import AsyncClient, ASGITransport
from app.main import app

@pytest.mark.asyncio
async def test_golden_master_all_domains_response(auth_headers):
    golden_routes = [
        ("/api/v1/dashboard/stats", ["totalRevenue", "activeClients", "pendingInvoices", "openLeads"]),
        ("/api/v1/clients/", []),
        ("/api/v1/leads/", []),
        ("/api/v1/tasks/", []),
        ("/api/v1/invoices/", []),
        ("/api/v1/estimates/", []),
        ("/api/v1/accounting/summary", ["summary", "accounts"]),
        ("/api/v1/warehouse/items", []),
        ("/api/v1/purchase/orders", []),
        ("/api/v1/recruitment/overview", ["jobOpenings", "candidates"]),
        ("/api/v1/okrs/", []),
        ("/api/v1/woocommerce/status", ["connected", "syncedProducts"]),
        ("/api/v1/account-planning/", []),
        ("/api/v1/staff-outsourcing/", []),
        ("/api/v1/tickets/", []),
        ("/api/v1/settings/", ["company_name", "timezone"]),
    ]

    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        for route, expected_keys in golden_routes:
            res = await ac.get(route, headers=auth_headers)
            assert res.status_code == 200, f"Failed on route {route}"
            data = res.json()
            for k in expected_keys:
                assert k in data, f"Missing key {k} in route {route}"
