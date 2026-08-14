import asyncio
import sys
import os
from datetime import datetime, date

# Add parent directory to path so we can run script directly
sys.path.append(os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))))

from app.infrastructure.database import async_engine
from app.domain.models.base import Base
from app.domain.models.staff import Staff, Role
from app.domain.models.client import Client, Contact
from app.domain.models.lead import Lead, LeadStatus, LeadSource
from app.domain.models.project import Project, Task, TaskTimer
from app.domain.models.ticket import Ticket, TicketStatus, TicketPriority
from app.domain.models.invoice import Invoice, InvoiceItem, InvoicePayment
from app.domain.models.accounting import Account, JournalEntry, AccountHistory
from app.domain.models.warehouse import Warehouse
from app.domain.models.purchase import PurchaseOrder
from app.domain.models.woocommerce import WooCommerceOrder
from app.domain.models.recruitment import RecruitmentCampaign, RecruitmentCandidate
from app.domain.models.okr import OKR, OKRKeyResult
from app.domain.models.account_planning import AccountPlan
from app.domain.models.staff_outsourcing import OutsourcedStaff
from app.domain.models.estimate import Estimate, EstimateItem
from app.domain.models.settings import SystemSetting
from app.core.security import get_password_hash
from sqlalchemy.ext.asyncio import async_sessionmaker, AsyncSession
from sqlalchemy import select

async def seed_data():
    print("Initializing database tables...")
    async with async_engine.begin() as conn:
        # Create all mapped tables if they do not exist
        await conn.run_sync(Base.metadata.create_all)
    
    AsyncSessionLocal = async_sessionmaker(bind=async_engine, class_=AsyncSession)
    
    async with AsyncSessionLocal() as session:
        # Check if default admin exists
        result = await session.execute(select(Staff).where(Staff.email == "admin@crm.com"))
        admin = result.scalar_one_or_none()
        
        if not admin:
            print("Seeding default Administrator and Staff roles...")
            admin_role = Role(name="Administrator", permissions="all")
            staff_role = Role(name="Staff", permissions="read_write")
            session.add_all([admin_role, staff_role])
            await session.flush()
            
            print("Seeding default Administrator user...")
            hashed_password = get_password_hash("admin_password")
            default_admin = Staff(
                email="admin@crm.com",
                firstname="System",
                lastname="Administrator",
                password=hashed_password,
                admin=1,
                role=admin_role.roleid,
                active=1
            )
            session.add(default_admin)
            await session.flush()
            
            # --- SEED LEAD METADATA ---
            print("Seeding lead statuses and sources...")
            status_prospect = LeadStatus(name="Prospect", statusorder=1, color="#757575")
            status_contacted = LeadStatus(name="Contacted", statusorder=2, color="#03a9f4")
            status_proposal = LeadStatus(name="Proposal Sent", statusorder=3, color="#ff9800")
            status_converted = LeadStatus(name="Converted", statusorder=4, color="#8bc34a")
            session.add_all([status_prospect, status_contacted, status_proposal, status_converted])
            
            source_web = LeadSource(name="Website Form")
            source_google = LeadSource(name="Google Search")
            source_referral = LeadSource(name="Referral")
            session.add_all([source_web, source_google, source_referral])
            await session.flush()
            
            # --- SEED LEADS ---
            print("Seeding leads...")
            lead1 = Lead(
                name="Alice Vance",
                email="alice@leads.com",
                company="Vance Refrigeration",
                phonenumber="555-0192",
                status=status_proposal.id,
                source=source_web.id,
                assigned=default_admin.staffid
            )
            lead2 = Lead(
                name="Bob Vance",
                email="bob@leads.com",
                company="Vance Refrigeration",
                phonenumber="555-0193",
                status=status_prospect.id,
                source=source_referral.id,
                assigned=default_admin.staffid
            )
            session.add_all([lead1, lead2])
            
            # --- SEED CLIENTS & CONTACTS ---
            print("Seeding clients and contacts...")
            client1 = Client(
                company="Stark Industries",
                vat="US-9912345",
                phonenumber="212-555-0100",
                city="New York",
                state="NY",
                address="10880 Malibu Point",
                website="https://starkindustries.com",
                active=1
            )
            session.add(client1)
            await session.flush()
            
            contact1 = Contact(
                userid=client1.userid,
                firstname="Pepper",
                lastname="Potts",
                email="pepper@stark.com",
                phonenumber="212-555-0101",
                title="CEO",
                is_primary=1,
                is_not_staff=1
            )
            session.add(contact1)
            
            # --- SEED PROJECTS & TASKS ---
            print("Seeding projects and tasks...")
            project1 = Project(
                name="Arc Reactor Optimization",
                description="Refining efficiency metrics and power output bounds.",
                status=2, # In progress
                clientid=client1.userid,
                billing_type=1, # Fixed
                start_date=date.today(),
                addedfrom=default_admin.staffid
            )
            session.add(project1)
            await session.flush()
            
            task1 = Task(
                name="Perform core thermal audit",
                description="Analyze heat distribution curves during high load testing.",
                priority=3, # High
                startdate=date.today(),
                status=2, # In progress
                rel_id=project1.id,
                rel_type="project",
                addedfrom=default_admin.staffid
            )
            session.add(task1)
            
            # --- SEED SUPPORT TICKETS ---
            print("Seeding support metadata and tickets...")
            t_status_open = TicketStatus(name="Open", isdefault=1, statusorder=1)
            t_status_closed = TicketStatus(name="Closed", statusorder=4)
            session.add_all([t_status_open, t_status_closed])
            
            t_priority_low = TicketPriority(name="Low")
            t_priority_high = TicketPriority(name="High")
            session.add_all([t_priority_low, t_priority_high])
            await session.flush()
            
            ticket1 = Ticket(
                subject="Thermal vent calibration issue",
                message="The main thermal cooling vents are not responding to automation overrides.",
                userid=client1.userid,
                contactid=contact1.id,
                email="pepper@stark.com",
                name="Pepper Potts",
                priority=t_priority_high.priorityid,
                status=t_status_open.ticketstatusid,
                ticketkey="TK-ARCREACTOR-01",
                project_id=project1.id,
                assigned=default_admin.staffid
            )
            session.add(ticket1)
            
            # --- SEED CHART OF ACCOUNTS ---
            print("Seeding Chart of Accounts...")
            acc_cash = Account(
                name="Cash/Bank",
                key_name="cash_bank",
                number="1000",
                account_type_id=1, # Asset
                balance=0.00,
                description="Primary bank account and cash reserves."
            )
            acc_ar = Account(
                name="Accounts Receivable",
                key_name="accounts_receivable",
                number="1200",
                account_type_id=1, # Asset
                balance=0.00,
                description="Outstanding invoices from corporate clients."
            )
            acc_rev = Account(
                name="Sales Revenue",
                key_name="sales_revenue",
                number="4000",
                account_type_id=4, # Income
                balance=0.00,
                description="Revenues generated from client invoices."
            )
            acc_exp = Account(
                name="Utilities Expense",
                key_name="utilities_expense",
                number="5000",
                account_type_id=5, # Expense
                balance=0.00,
                description="Power, heating, and cooling utility charges."
            )
            session.add_all([acc_cash, acc_ar, acc_rev, acc_exp])
            await session.flush()
            
            # --- SEED INVOICES ---
            print("Seeding default client invoices...")
            invoice1 = Invoice(
                clientid=client1.userid,
                number=1000,
                prefix="INV-",
                date=date.today(),
                duedate=date.today(),
                subtotal=5000.00,
                total_tax=0.00,
                total=5000.00,
                adjustment=0.00,
                status=1, # Unpaid
                hash="SEED-INVOICE-HASH-STARK-01",
                clientnote="Thank you for your business!",
                addedfrom=default_admin.staffid
            )
            session.add(invoice1)
            await session.flush()
            
            item1 = InvoiceItem(
                rel_id=invoice1.id,
                rel_type="invoice",
                description="Arc Reactor Core Calibration Services",
                long_description="Sub-particle thermal output calibration for Mark LXXXV design.",
                qty=1.00,
                rate=5000.00
            )
            session.add(item1)
            
            # --- SEED WAREHOUSE (LOGISTICS) ---
            print("Seeding Warehouse storage depots...")
            warehouse1 = Warehouse(
                warehouse_code="WH-MAIN",
                warehouse_name="Main Logistics Depot",
                warehouse_address="10880 Malibu Point, Malibu, CA",
                order=1,
                display=1,
                note="Core repository for reactor cells and high-grade armor components."
            )
            session.add(warehouse1)
            
            # --- SEED PROCUREMENT PURCHASE ORDERS ---
            print("Seeding Vendor Purchase Orders...")
            po1 = PurchaseOrder(
                pur_order_name="Vibranium Core Castings",
                vendor=1, # Stark Industries (acting as vendor here for seed simulation)
                pur_order_number="PO-2026-001",
                order_date=date.today(),
                status=2, # ordered
                approve_status=2, # approved
                subtotal=8000.00,
                total=8000.00,
                addedfrom=default_admin.staffid,
                vendornote="Specialized vibranium castings for alloy reinforcement."
            )
            session.add(po1)
            
            # --- SEED WOOCOMMERCE ORDERS ---
            print("Seeding WooCommerce e-commerce orders sync log...")
            wc1 = WooCommerceOrder(
                order_id=9001,
                order_number="WC-ORDER-9001",
                customer_id=client1.userid,
                status="completed",
                total="12000.00",
                invoice_id=invoice1.id,
                store_id=1
            )
            session.add(wc1)
            
            # --- SEED RECRUITMENT CAMPAIGNS ---
            print("Seeding recruitment campaigns...")
            campaign1 = RecruitmentCampaign(
                campaign_name="Senior Propulsion Architect",
                position="Principal Engineer",
                department_id=1,
                start_date=date.today(),
                status=1,
                description="Lead developer for ion propulsion designs and core thrust nozzle dynamics."
            )
            session.add(campaign1)
            await session.flush()
            
            candidate1 = RecruitmentCandidate(
                candidate_name="Bruce Banner",
                email="bruce@avengers.com",
                phonenumber="555-0988",
                campaign_id=campaign1.id,
                status=2, # interview scheduled
                evaluation="Exceptional background in gamma radiation and safety limits. Mind the stress levels."
            )
            session.add(candidate1)
            
            # --- SEED OKRs ---
            print("Seeding Objectives & Key Results...")
            okr1 = OKR(
                name="Upgrade Malibu Laboratory Security Protocols",
                circulation=1,
                your_target="Establish zero containment breaches and automated fire protection overrides.",
                creator=default_admin.staffid
            )
            session.add(okr1)
            await session.flush()
            
            kr1 = OKRKeyResult(
                okrs_id=okr1.id,
                key_result_title="Calibrate core heat ventilation containment dampeners",
                target_value=100.00,
                current_value=40.00,
                confidence_level=7
            )
            kr2 = OKRKeyResult(
                okrs_id=okr1.id,
                key_result_title="Deploy automated thermal fire suppression matrix",
                target_value=100.00,
                current_value=0.00,
                confidence_level=5
            )
            session.add_all([kr1, kr2])
            
            await session.commit()
            print("Database seeding completed successfully!")
        else:
            print("Default administrator already exists. Skipping seeding.")

if __name__ == "__main__":
    asyncio.run(seed_data())
