import pytest
from fastapi.testclient import TestClient
from app.main import app

client = TestClient(app)

def test_unauthenticated_request_rejected():
    # Endpoints requiring auth must reject unauthenticated requests (HTTP 401)
    protected_endpoints = [
        "/api/v1/dashboard/stats",
        "/api/v1/clients/",
        "/api/v1/leads/",
        "/api/v1/invoices/",
        "/api/v1/accounting/summary",
        "/api/v1/settings/",
    ]
    for ep in protected_endpoints:
        res = client.get(ep)
        assert res.status_code == 401

def test_invalid_bearer_token_rejected():
    invalid_headers = {"Authorization": "Bearer invalid_garbage_token"}
    res = client.get("/api/v1/clients/", headers=invalid_headers)
    assert res.status_code == 401

def test_refresh_token_missing_cookie():
    res = client.post("/api/v1/auth/refresh")
    assert res.status_code == 401
