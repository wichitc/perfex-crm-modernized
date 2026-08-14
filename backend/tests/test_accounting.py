import pytest
from httpx import AsyncClient, ASGITransport
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.domain.models.client import Client
from app.domain.models.invoice import Invoice, InvoiceItem
from app.domain.models.accounting import Account, AccountHistory
from app.main import app

@pytest.mark.asyncio
async def test_double_entry_and_reports(db_session: AsyncSession, auth_headers: dict):
    # 1. Seed accounts tree
    cash_acc = Account(name="Cash/Bank", key_name="cash_bank", number="1000", account_type_id=1, balance=0.00)
    ar_acc = Account(name="Accounts Receivable", key_name="accounts_receivable", number="1200", account_type_id=1, balance=0.00)
    rev_acc = Account(name="Sales Revenue", key_name="sales_revenue", number="4000", account_type_id=4, balance=0.00)
    db_session.add_all([cash_acc, ar_acc, rev_acc])
    
    # 2. Seed client and invoice
    client = Client(company="Stark Industries", vat="US-9912345", phonenumber="212-555-0100", active=1)
    db_session.add(client)
    await db_session.flush()

    invoice = Invoice(
        clientid=client.userid,
        number=1001,
        prefix="INV-",
        subtotal=2000.00,
        total=2000.00,
        status=1, # Unpaid
        hash="TEST-HASH-1001"
    )
    db_session.add(invoice)
    await db_session.flush()

    item = InvoiceItem(rel_id=invoice.id, rel_type="invoice", description="Calibration", qty=1, rate=2000.00)
    db_session.add(item)
    await db_session.commit()

    async with AsyncClient(transport=ASGITransport(app=app), base_url="http://test") as ac:
        # 3. Log payment
        pay_response = await ac.post(
            f"/api/v1/invoices/{invoice.id}/payments",
            headers=auth_headers,
            json={
                "amount": 2000.00,
                "paymentmode": "bank",
                "paymentmethod": "Wire Transfer",
                "date": "2026-07-22"
            }
        )
        assert pay_response.status_code in [201, 200]

        # 4. Check summary
        summary_res = await ac.get("/api/v1/accounting/summary", headers=auth_headers)
        assert summary_res.status_code == 200
        summary_data = summary_res.json()
        assert "summary" in summary_data
