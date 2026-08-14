import pytest
from fastapi.testclient import TestClient
from app.main import app

client = TestClient(app)

def test_lead_to_client_conversion_workflow(auth_headers):
    # 1. Create a Lead
    lead_payload = {
        "name": "Acme Lead Test",
        "company": "Acme Test Corp",
        "email": "testlead@acmetest.com",
        "phonenumber": "+66 2 999 8888",
        "status": 1,
        "source": 1,
        "lead_value": 50000.0
    }
    create_res = client.post("/api/v1/leads/", json=lead_payload, headers=auth_headers)
    assert create_res.status_code in [201, 200]
    lead_id = create_res.json()["id"]

    # 2. Convert Lead to Client
    convert_res = client.post(f"/api/v1/leads/{lead_id}/convert", headers=auth_headers)
    assert convert_res.status_code == 200
    converted_lead = convert_res.json()
    assert converted_lead["client_id"] > 0

def test_ticket_creation_workflow(auth_headers):
    ticket_payload = {
        "subject": "API Sync Assistance Request",
        "message": "Need help verifying sync status.",
        "client": "Stark Industries",
        "userid": 1
    }
    res = client.post("/api/v1/tickets/", json=ticket_payload, headers=auth_headers)
    assert res.status_code in [201, 200]
    data = res.json()
    assert "ticketkey" in data or "id" in data
