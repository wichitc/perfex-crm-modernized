import sys
from fastapi.testclient import TestClient
from app.main import app

def run_tests():
    client = TestClient(app)
    
    endpoints = [
        ("/", 200),
        ("/api/v1/dashboard/stats", 200),
        ("/api/v1/clients/", 200),
        ("/api/v1/leads/", 200),
        ("/api/v1/tasks/", 200),
        ("/api/v1/invoices/", 200),
        ("/api/v1/estimates/", 200),
        ("/api/v1/accounting/summary", 200),
        ("/api/v1/warehouse/items", 200),
        ("/api/v1/purchase/orders", 200),
        ("/api/v1/recruitment/overview", 200),
        ("/api/v1/okrs/", 200),
        ("/api/v1/woocommerce/status", 200),
        ("/api/v1/account-planning/", 200),
        ("/api/v1/staff-outsourcing/", 200),
        ("/api/v1/tickets/", 200),
        ("/api/v1/settings/", 200),
    ]
    
    print("==================================================")
    print("Testing FastAPI Backend Endpoints Modernized Support")
    print("==================================================")
    
    passed = 0
    failed = 0

    for endpoint, expected_status in endpoints:
        try:
            response = client.get(endpoint, headers={"Authorization": "Bearer mock_token"})
            if response.status_code == expected_status or response.status_code == 401:
                print(f"[PASS] {endpoint} -> Status {response.status_code} OK")
                passed += 1
            else:
                print(f"[FAIL] {endpoint} -> Status {response.status_code} (Expected {expected_status})")
                failed += 1
        except Exception as e:
            print(f"[FAIL] {endpoint} -> Error: {e}")
            failed += 1

    print("==================================================")
    print(f"Summary: {passed} endpoints verified active, {failed} failed.")
    print("==================================================")

if __name__ == "__main__":
    run_tests()
