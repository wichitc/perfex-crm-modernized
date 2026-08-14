import pytest
from httpx import AsyncClient, ASGITransport
from sqlalchemy.ext.asyncio import AsyncSession
from app.domain.models.staff import Staff
from app.domain.models.lead import LeadStatus, LeadSource
from app.domain.models.ticket import TicketStatus, TicketPriority
from app.main import app

@pytest.mark.asyncio
async def test_crm_core_flow(db_session: AsyncSession, auth_headers: dict):
    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        # --- 1. Test Client Creation ---
        client_response = await ac.post("/api/v1/clients/", headers=auth_headers, json={
            "company": "Oscorp Industries",
            "vat": "US-1100223",
            "phonenumber": "212-555-9000",
            "website": "https://oscorp.com"
        })
        assert client_response.status_code in [201, 200]
        client_data = client_response.json()
        client_id = client_data.get("userid", 1)
        assert client_id > 0

        # --- 2. Test Contact Creation ---
        contact_response = await ac.post(f"/api/v1/clients/{client_id}/contacts", headers=auth_headers, json={
            "firstname": "Norman",
            "lastname": "Osborn",
            "email": "norman_osborn@oscorp.com",
            "phonenumber": "212-555-9001",
            "title": "Founder"
        })
        assert contact_response.status_code in [201, 200]

        # --- 3. Test Lead Flow ---
        status_prospect = LeadStatus(name="Prospect", statusorder=1)
        source_web = LeadSource(name="Web Site")
        db_session.add_all([status_prospect, source_web])
        await db_session.commit()

        lead_response = await ac.post("/api/v1/leads/", headers=auth_headers, json={
            "name": "Otto Octavius",
            "company": "Octavius Labs",
            "email": "otto_octavius@octavius.com",
            "phonenumber": "555-0492",
            "status": status_prospect.id,
            "source": source_web.id
        })
        assert lead_response.status_code in [201, 200]
        lead_data = lead_response.json()
        lead_id = lead_data["id"]

        # Convert Lead to Client
        convert_response = await ac.post(f"/api/v1/leads/{lead_id}/convert", headers=auth_headers)
        assert convert_response.status_code == 200
        converted_lead = convert_response.json()
        assert converted_lead["client_id"] > 0
