import uuid
from typing import List, Optional
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.infrastructure.database import get_db
from app.domain.models.invoice import Invoice, InvoiceItem, InvoicePayment
from app.domain.models.client import Client
from app.domain.models.staff import Staff
from app.application.schemas.billing import InvoiceCreate, InvoiceResponse, InvoicePaymentCreate, InvoicePaymentResponse
from app.application.services.posting_engine import PostingEngine
from app.api.dependencies import get_current_user

router = APIRouter(prefix="/invoices", tags=["Billing"])

@router.get("/", response_model=List[InvoiceResponse])
async def get_invoices(
    offset: int = 0,
    limit: int = 100,
    client_id: Optional[int] = None,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    query = select(Invoice)
    if client_id is not None:
        query = query.where(Invoice.clientid == client_id)
        
    result = await db.execute(query.offset(offset).limit(limit))
    return result.scalars().all()

@router.post("/", response_model=InvoiceResponse, status_code=status.HTTP_201_CREATED)
async def create_invoice(
    invoice_data: InvoiceCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    client_check = await db.execute(select(Client).where(Client.userid == invoice_data.clientid))
    if not client_check.scalar_one_or_none():
        raise HTTPException(status_code=404, detail="Client not found")

    async with db.begin_nested():
        invoice = Invoice(
            clientid=invoice_data.clientid,
            number=invoice_data.number,
            prefix=invoice_data.prefix,
            date=invoice_data.date,
            duedate=invoice_data.duedate,
            subtotal=invoice_data.subtotal,
            total_tax=invoice_data.total_tax,
            total=invoice_data.total,
            adjustment=invoice_data.adjustment,
            status=invoice_data.status,
            clientnote=invoice_data.clientnote,
            adminnote=invoice_data.adminnote,
            hash=str(uuid.uuid4().hex),
            addedfrom=current_user.staffid
        )
        db.add(invoice)
        await db.flush()

        for item_data in invoice_data.items:
            item = InvoiceItem(
                rel_id=invoice.id,
                rel_type="invoice",
                description=item_data.description,
                long_description=item_data.long_description,
                qty=item_data.qty,
                rate=item_data.rate,
                unit=item_data.unit
            )
            db.add(item)

    await db.commit()
    await db.refresh(invoice)
    return invoice

@router.get("/{invoice_id}", response_model=InvoiceResponse)
async def get_invoice(
    invoice_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Invoice).where(Invoice.id == invoice_id))
    invoice = result.scalar_one_or_none()
    if not invoice:
        raise HTTPException(status_code=404, detail="Invoice not found")
    return invoice

@router.put("/{invoice_id}", response_model=InvoiceResponse)
async def update_invoice(
    invoice_id: int,
    payload: dict,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Invoice).where(Invoice.id == invoice_id))
    invoice = result.scalar_one_or_none()
    if not invoice:
        raise HTTPException(status_code=404, detail="Invoice not found")
    for k, v in payload.items():
        if hasattr(invoice, k):
            setattr(invoice, k, v)
    await db.commit()
    await db.refresh(invoice)
    return invoice

@router.delete("/{invoice_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_invoice(
    invoice_id: int,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Invoice).where(Invoice.id == invoice_id))
    invoice = result.scalar_one_or_none()
    if not invoice:
        raise HTTPException(status_code=404, detail="Invoice not found")
    await db.delete(invoice)
    await db.commit()
    return None

@router.post("/{invoice_id}/payments", response_model=InvoicePaymentResponse, status_code=status.HTTP_201_CREATED)
async def create_payment(
    invoice_id: int,
    payment_data: InvoicePaymentCreate,
    db: AsyncSession = Depends(get_db),
    current_user: Staff = Depends(get_current_user)
):
    result = await db.execute(select(Invoice).where(Invoice.id == invoice_id))
    invoice = result.scalar_one_or_none()
    if not invoice:
        raise HTTPException(status_code=404, detail="Invoice not found")
        
    if invoice.status == 2:
        raise HTTPException(status_code=400, detail="Invoice already fully paid")

    async with db.begin_nested():
        payment = InvoicePayment(
            invoiceid=invoice_id,
            amount=payment_data.amount,
            paymentmode=payment_data.paymentmode,
            paymentmethod=payment_data.paymentmethod,
            date=payment_data.date,
            note=payment_data.note,
            transactionid=payment_data.transactionid or str(uuid.uuid4().hex[:16]).upper()
        )
        db.add(payment)
        await db.flush()

        total_payments_result = await db.execute(
            select(InvoicePayment).where(InvoicePayment.invoiceid == invoice_id)
        )
        all_payments = total_payments_result.scalars().all()
        total_paid = sum(p.amount for p in all_payments)

        if total_paid >= invoice.total:
            invoice.status = 2
        else:
            invoice.status = 3

        await PostingEngine.post_invoice_payment(
            db=db,
            invoice=invoice,
            payment=payment,
            addedfrom=current_user.staffid
        )

    await db.commit()
    await db.refresh(payment)
    return payment
