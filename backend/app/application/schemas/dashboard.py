from typing import List, Optional
from pydantic import BaseModel

class RevenueChartItem(BaseModel):
    month: str
    revenue: float
    expenses: float

class ActiveTaskItem(BaseModel):
    id: int
    title: str
    priority: str
    status: str
    dueDate: str
    assignee: str

class DashboardStatsResponse(BaseModel):
    totalRevenue: float
    revenueChange: str
    activeClients: int
    clientsChange: str
    pendingInvoices: int
    invoicesAmount: float
    openLeads: int
    leadsConverted: str
    revenueChart: List[RevenueChartItem] = []
    activeTasks: List[ActiveTaskItem] = []
