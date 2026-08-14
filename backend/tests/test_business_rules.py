import pytest
from httpx import AsyncClient, ASGITransport
from app.main import app

@pytest.mark.asyncio
async def test_br001_password_verification_fail():
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        response = await ac.post("/api/v1/auth/login", json={"email": "wrong@crm.com", "password": "wrong_password"})
        assert response.status_code == 401
        assert "Incorrect email or password" in response.json()["detail"]

@pytest.mark.asyncio
async def test_br006_invoice_total_calculation(auth_headers):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        invoice_payload = {
            "clientid": 1,
            "number": 9901,
            "prefix": "INV-2026-",
            "date": "2026-08-14",
            "duedate": "2026-09-14",
            "subtotal": 10000.0,
            "total_tax": 700.0,
            "total": 10700.0,
            "adjustment": 0.0,
            "status": 1,
            "items": [
                {
                    "description": "Consulting Services",
                    "qty": 2.0,
                    "rate": 5000.0
                }
            ]
        }
        response = await ac.post("/api/v1/invoices/", json=invoice_payload, headers=auth_headers)
        assert response.status_code in [201, 200]
        data = response.json()
        assert data["total"] == 10700.0

@pytest.mark.asyncio
async def test_br016_okr_progress_formatting(auth_headers):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        response = await ac.get("/api/v1/okrs/", headers=auth_headers)
        assert response.status_code == 200
        data = response.json()
        assert len(data) > 0
        assert "progress" in data[0]
        assert "keyResults" in data[0]
