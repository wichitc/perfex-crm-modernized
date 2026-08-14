from typing import Optional
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from app.domain.models.invoice import Invoice, InvoicePayment
from app.domain.models.accounting import Account, JournalEntry, AccountHistory
from datetime import datetime

class PostingEngine:
    @staticmethod
    async def post_invoice_payment(
        db: AsyncSession,
        invoice: Invoice,
        payment: InvoicePayment,
        addedfrom: Optional[int] = None
    ) -> JournalEntry:
        # 1. Fetch matching default ledger accounts
        # Cash/Bank (Asset, Code 1000)
        cash_result = await db.execute(
            select(Account).where(Account.key_name == "cash_bank")
        )
        cash_account = cash_result.scalar_one_or_none()
        
        # Accounts Receivable (Asset, Code 1200)
        ar_result = await db.execute(
            select(Account).where(Account.key_name == "accounts_receivable")
        )
        ar_account = ar_result.scalar_one_or_none()
        
        if not cash_account or not ar_account:
            # Fallback/Auto-creation of default accounts if not seeded
            if not cash_account:
                cash_account = Account(
                    name="Cash/Bank",
                    key_name="cash_bank",
                    number="1000",
                    account_type_id=1, # Asset
                    balance=0.00
                )
                db.add(cash_account)
            if not ar_account:
                ar_account = Account(
                    name="Accounts Receivable",
                    key_name="accounts_receivable",
                    number="1200",
                    account_type_id=1, # Asset
                    balance=0.00
                )
                db.add(ar_account)
            await db.flush() # Populate IDs

        # 2. Create Journal Entry Header
        journal = JournalEntry(
            number=f"JV-PAY-{payment.id}",
            description=f"Automated double-entry posting for payment on Invoice #{invoice.prefix}{invoice.number}",
            journal_date=payment.date,
            amount=payment.amount,
            addedfrom=addedfrom
        )
        db.add(journal)
        await db.flush() # Populate journal ID

        # 3. Create balancing Ledger Rows (tblacc_account_history)
        # Entry A: Debit Cash/Bank (increases Asset)
        debit_row = AccountHistory(
            account=cash_account.id,
            debit=payment.amount,
            credit=0.00,
            description=f"Debit cash for Invoice #{invoice.prefix}{invoice.number} payment",
            rel_id=invoice.id,
            rel_type="invoice",
            addedfrom=addedfrom,
            customer=invoice.clientid
        )
        
        # Entry B: Credit Accounts Receivable (decreases Asset)
        credit_row = AccountHistory(
            account=ar_account.id,
            debit=0.00,
            credit=payment.amount,
            description=f"Credit receivables for Invoice #{invoice.prefix}{invoice.number} payment",
            rel_id=invoice.id,
            rel_type="invoice",
            addedfrom=addedfrom,
            customer=invoice.clientid
        )
        
        db.add_all([debit_row, credit_row])

        # 4. Update balances directly on Account models
        amt = float(payment.amount or 0.0)
        cash_account.balance = float(cash_account.balance or 0.0) + amt
        ar_account.balance = float(ar_account.balance or 0.0) - amt

        return journal
