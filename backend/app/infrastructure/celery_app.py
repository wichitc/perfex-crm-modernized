import os
import sys
from celery import Celery

# Add current dir to path to import app modules inside worker environment
sys.path.append(os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))))

from app.core.config import settings

# Initialize Celery app
celery_app = Celery(
    "perfex_tasks",
    broker=settings.get_redis_url,
    backend=settings.get_redis_url
)

# Optional configurations
celery_app.conf.update(
    task_serializer="json",
    accept_content=["json"],
    result_serializer="json",
    timezone="UTC",
    enable_utc=True,
)

@celery_app.task(name="tasks.sync_woocommerce_orders")
def sync_woocommerce_orders(store_id: int):
    """
    Simulated WooCommerce background synchronization task.
    Pulls sales orders from WooCommerce store and imports them as CRM Invoices.
    """
    print(f"Starting async WooCommerce synchronization for Store #{store_id}...")
    import asyncio
    from app.infrastructure.database import AsyncSessionLocal
    from app.domain.models.woocommerce import WooCommerceOrder
    from app.domain.models.invoice import Invoice, InvoiceItem
    from app.domain.models.client import Client
    from sqlalchemy import select
    import uuid
    from datetime import date

    async def _async_sync():
        async with AsyncSessionLocal() as session:
            # Check if Stark Industries client exists (seeded client)
            client_result = await session.execute(
                select(Client).where(Client.company == "Stark Industries")
            )
            client = client_result.scalar_one_or_none()
            if not client:
                print("Client Stark Industries not found. Skipping WooCommerce sync.")
                return False
                
            # Create a mock WooCommerce order
            wc_order_id = 9000 + store_id
            order_number = f"WC-ORDER-{wc_order_id}"
            
            # Check if this order was already synced
            dup_check = await session.execute(
                select(WooCommerceOrder).where(WooCommerceOrder.order_id == wc_order_id)
            )
            if dup_check.scalar_one_or_none():
                print(f"Order #{order_number} already synced. Skipping.")
                return True

            # 1. Create matching CRM Invoice
            invoice = Invoice(
                clientid=client.userid,
                number=wc_order_id,
                prefix="WC-INV-",
                date=date.today(),
                duedate=date.today(),
                subtotal=12000.00,
                total=12000.00,
                status=1, # Unpaid
                hash=str(uuid.uuid4().hex),
                clientnote="Synced from WooCommerce e-commerce order."
            )
            session.add(invoice)
            await session.flush() # Populate invoice ID
            
            # Create invoice line item
            item = InvoiceItem(
                rel_id=invoice.id,
                rel_type="invoice",
                description="WooCommerce Sync: Stark repulsors components",
                qty=1.00,
                rate=12000.00
            )
            session.add(item)
            
            # 2. Log WooCommerce Order record mapping
            wc_order = WooCommerceOrder(
                order_id=wc_order_id,
                order_number=order_number,
                customer_id=client.userid,
                status= "completed",
                total="12000.00",
                invoice_id=invoice.id,
                store_id=store_id
            )
            session.add(wc_order)
            
            await session.commit()
            print(f"WooCommerce Order #{order_number} synced successfully! Created CRM Invoice #{invoice.id}.")
            return True

    # Run the async operations inside standard Celery thread loop
    loop = asyncio.get_event_loop()
    return loop.run_until_complete(_async_sync())
