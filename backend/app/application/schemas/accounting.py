from datetime import datetime, date
from typing import Optional, List
from pydantic import BaseModel, field_validator

# --- CHART OF ACCOUNTS SCHEMAS ---
class AccountBase(BaseModel):
    name: str
    key_name: Optional[str] = None
    number: Optional[str] = None
    parent_account: Optional[int] = None
    account_type_id: int # 1 = Asset, 2 = Liability, 3 = Equity, 4 = Income, 5 = Expense
    account_detail_type_id: int = 1
    balance: float = 0.00
    description: Optional[str] = None

class AccountCreate(AccountBase):
    pass

class AccountResponse(AccountBase):
    id: int
    balance_as_of: Optional[date] = None

    class Config:
        from_attributes = True


# --- BALANCED JOURNAL ENTRY SCHEMAS ---
class JournalEntryLine(BaseModel):
    account: int # Account ID
    debit: float = 0.00
    credit: float = 0.00
    description: Optional[str] = None
    customer: Optional[int] = None

class JournalEntryCreate(BaseModel):
    description: Optional[str] = None
    journal_date: date
    lines: List[JournalEntryLine]

    # Validate that Debit total equals Credit total
    @field_validator("lines")
    @classmethod
    def validate_balanced_lines(cls, lines: List[JournalEntryLine]) -> List[JournalEntryLine]:
        if len(lines) < 2:
            raise ValueError("A journal entry must contain at least 2 lines")
            
        debit_sum = sum(line.debit for line in lines)
        credit_sum = sum(line.credit for line in lines)
        
        # Using tolerance for floating point calculations comparison
        if abs(debit_sum - credit_sum) > 0.01:
            raise ValueError(f"Ledger unbalanced: Total Debits (${debit_sum:.2f}) must equal Total Credits (${credit_sum:.2f})")
            
        return lines

class JournalEntryResponse(BaseModel):
    id: int
    number: Optional[str] = None
    description: Optional[str] = None
    journal_date: date
    amount: float
    datecreated: datetime
    addedfrom: Optional[int] = None

    class Config:
        from_attributes = True


# --- LEDGER HISTORY SCHEMAS ---
class AccountHistoryResponse(BaseModel):
    id: int
    account: int
    debit: float
    credit: float
    description: Optional[str] = None
    rel_id: Optional[int] = None
    rel_type: Optional[str] = None
    datecreated: datetime
    customer: Optional[int] = None

    class Config:
        from_attributes = True
