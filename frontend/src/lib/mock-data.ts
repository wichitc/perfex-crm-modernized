// Comprehensive Mock Dataset for Perfex CRM Next.js Frontend

export const MOCK_USER = {
  staffid: 1,
  firstname: "System",
  lastname: "Administrator",
  email: "admin@perfexcrm.com",
  admin: 1,
  role: 1,
  scopes: ["admin:all"],
};

export const MOCK_DASHBOARD_STATS = {
  totalRevenue: 148920.00,
  revenueChange: "+14.2%",
  activeClients: 48,
  clientsChange: "+4",
  pendingInvoices: 12,
  invoicesAmount: 34500.00,
  openLeads: 27,
  leadsConverted: "68%",
};

export const MOCK_REVENUE_CHART = [
  { month: "Jan", revenue: 12400, expenses: 8200 },
  { month: "Feb", revenue: 14500, expenses: 9100 },
  { month: "Mar", revenue: 18200, expenses: 10400 },
  { month: "Apr", revenue: 16800, expenses: 9800 },
  { month: "May", revenue: 21500, expenses: 11200 },
  { month: "Jun", revenue: 24900, expenses: 12800 },
  { month: "Jul", revenue: 28400, expenses: 13500 },
];

export const MOCK_CLIENTS = [
  {
    userid: 101,
    company: "Acme Technology Solutions",
    vat: "TH0994827101",
    phonenumber: "+66 2 123 4567",
    city: "Bangkok",
    state: "Bangkok",
    website: "https://acmetechnology.co.th",
    datecreated: "2026-01-15T08:30:00Z",
    active: 1,
    contacts: [
      { id: 1, firstname: "Somchai", lastname: "Jaidee", email: "somchai@acme.co.th", phonenumber: "+66 81 234 5678", title: "IT Director", is_primary: 1 },
      { id: 2, firstname: "Ananya", lastname: "Srisuk", email: "ananya@acme.co.th", phonenumber: "+66 89 876 5432", title: "Procurement Manager", is_primary: 0 },
    ],
  },
  {
    userid: 102,
    company: "Siam Digital Innovations",
    vat: "TH0105549023",
    phonenumber: "+66 2 987 6543",
    city: "Nonthaburi",
    state: "Nonthaburi",
    website: "https://siamdigital.com",
    datecreated: "2026-02-01T10:15:00Z",
    active: 1,
    contacts: [
      { id: 3, firstname: "Kittisak", lastname: "Vong", email: "kittisak@siamdigital.com", phonenumber: "+66 86 555 1234", title: "CEO", is_primary: 1 },
    ],
  },
  {
    userid: 103,
    company: "Global Logistics Thailand",
    vat: "TH0105531099",
    phonenumber: "+66 38 777 888",
    city: "Chonburi",
    state: "Chonburi",
    website: "https://globallogistics.co.th",
    datecreated: "2026-03-10T14:20:00Z",
    active: 1,
    contacts: [
      { id: 4, firstname: "Nipon", lastname: "Prasert", email: "nipon@globallogistics.co.th", phonenumber: "+66 83 444 9999", title: "Operations Head", is_primary: 1 },
    ],
  },
];

export const MOCK_LEADS = [
  { id: 1, name: "Supatra Enterprise", email: "contact@supatra.com", status: "New", value: 45000, source: "Web Form", assignedTo: "Somchai" },
  { id: 2, name: "Nexus Cloud Systems", email: "info@nexuscloud.io", status: "Contacted", value: 120000, source: "LinkedIn", assignedTo: "Ananya" },
  { id: 3, name: "Bangkok Retail Corp", email: "sales@bangkokretail.th", status: "Proposal Sent", value: 85000, source: "Referral", assignedTo: "Admin" },
  { id: 4, name: "Eastern Manufacturing", email: "procurement@easternmfg.com", status: "Qualified", value: 210000, source: "Exhibition", assignedTo: "Somchai" },
  { id: 5, name: "Thai Biotech Labs", email: "lab@thaibiotech.org", status: "Won", value: 95000, source: "Direct", assignedTo: "Kittisak" },
];

export const MOCK_INVOICES = [
  {
    id: 1001,
    clientid: 101,
    clientName: "Acme Technology Solutions",
    number: 1001,
    prefix: "INV-2026-",
    date: "2026-07-01",
    duedate: "2026-07-31",
    subtotal: 50000.00,
    total: 53500.00,
    status: 2, // Paid
    items: [
      { id: 1, description: "Enterprise CRM Cloud License (Annual)", long_description: "12 Months Subscription for 50 users", qty: 1, rate: 50000.00 }
    ],
    payments: [
      { id: 1, amount: 53500.00, paymentmethod: "Bank Transfer", date: "2026-07-05", transactionid: "TXN-8849201" }
    ]
  },
  {
    id: 1002,
    clientid: 102,
    clientName: "Siam Digital Innovations",
    number: 1002,
    prefix: "INV-2026-",
    date: "2026-07-15",
    duedate: "2026-08-15",
    subtotal: 28000.00,
    total: 29960.00,
    status: 1, // Unpaid
    items: [
      { id: 2, description: "Custom Next.js Theme Implementation", long_description: "Design and deployment of bespoke frontend", qty: 1, rate: 28000.00 }
    ],
    payments: []
  },
  {
    id: 1003,
    clientid: 103,
    clientName: "Global Logistics Thailand",
    number: 1003,
    prefix: "INV-2026-",
    date: "2026-06-10",
    duedate: "2026-07-10",
    subtotal: 42000.00,
    total: 44940.00,
    status: 3, // Overdue
    items: [
      { id: 3, description: "Warehouse Management API Connector", long_description: "Integration module for automated stock sync", qty: 1, rate: 42000.00 }
    ],
    payments: []
  }
];

export const MOCK_ACCOUNTING = {
  summary: {
    assets: 1850000.00,
    liabilities: 420000.00,
    equity: 1430000.00,
    netIncome: 345000.00
  },
  accounts: [
    { code: "1010", name: "Cash on Hand & Bank", type: "Asset", balance: 650000.00 },
    { code: "1020", name: "Accounts Receivable", type: "Asset", balance: 320000.00 },
    { code: "1050", name: "Inventory Stock Account", type: "Asset", balance: 880000.00 },
    { code: "2010", name: "Accounts Payable", type: "Liability", balance: 290000.00 },
    { code: "2030", name: "VAT Payable", type: "Liability", balance: 130000.00 },
    { code: "4010", name: "Sales Revenue", type: "Income", balance: 1250000.00 },
    { code: "5010", name: "Cost of Goods Sold", type: "Expense", balance: 680000.00 },
  ]
};

export const MOCK_WAREHOUSE = [
  { id: "SKU-001", name: "Barcode Scanner Handheld 2D", location: "Bangkok Central Hub", category: "Hardware", stock: 145, minStock: 20, unitPrice: 3500 },
  { id: "SKU-002", name: "Thermal Receipt Printer 80mm", location: "Nonthaburi Depot", category: "Hardware", stock: 82, minStock: 15, unitPrice: 4200 },
  { id: "SKU-003", name: "Smart RFID Asset Tags (Pack of 100)", location: "Chonburi Warehouse", category: "Consumables", stock: 12, minStock: 25, unitPrice: 1800 },
  { id: "SKU-004", name: "Wireless Logistics Tablet 10-inch", location: "Bangkok Central Hub", category: "Devices", stock: 34, minStock: 10, unitPrice: 12500 },
];

export const MOCK_PURCHASE_ORDERS = [
  { poNumber: "PO-2026-089", vendor: "Zebra Technologies Asia", date: "2026-07-20", totalAmount: 185000, status: "Approved", expectedDelivery: "2026-08-05" },
  { poNumber: "PO-2026-090", vendor: "Honeywell Thailand", date: "2026-07-25", totalAmount: 94000, status: "Pending Approval", expectedDelivery: "2026-08-10" },
  { poNumber: "PO-2026-091", vendor: "Epson Electronics Ltd", date: "2026-07-28", totalAmount: 42000, status: "Received", expectedDelivery: "2026-07-29" },
];

export const MOCK_WOOCOMMERCE = {
  connected: true,
  storeUrl: "https://shop.perfexcrm-demo.com",
  lastSync: "2026-07-29 19:45:00",
  syncedProducts: 142,
  syncedOrders: 859,
  recentSyncs: [
    { id: 1, type: "Orders Sync", count: 14, status: "Success", timestamp: "2026-07-29 19:45:00" },
    { id: 2, type: "Inventory Stock Update", count: 142, status: "Success", timestamp: "2026-07-29 18:00:00" },
    { id: 3, type: "Customer Contacts Sync", count: 3, status: "Success", timestamp: "2026-07-29 15:30:00" },
  ]
};

export const MOCK_RECRUITMENT = {
  jobOpenings: [
    { id: 1, title: "Senior Fullstack Next.js Developer", department: "Engineering", applicants: 18, status: "Active" },
    { id: 2, title: "Enterprise Account Executive", department: "Sales", applicants: 12, status: "Active" },
    { id: 3, title: "DevOps & Infrastructure Lead", department: "IT", applicants: 7, status: "Draft" },
  ],
  candidates: [
    { id: 101, name: "Chaiwat Saelim", position: "Senior Fullstack Next.js Developer", stage: "Interview", rating: 4.8 },
    { id: 102, name: "Pornpimol Wong", position: "Enterprise Account Executive", stage: "Offered", rating: 4.9 },
    { id: 103, name: "Tawatchai Tech", position: "DevOps & Infrastructure Lead", stage: "Applied", rating: 4.2 },
  ]
};

export const MOCK_OKRS = [
  {
    id: 1,
    title: "Scale Annual Recurring Revenue to ฿50M",
    period: "Q3 2026",
    owner: "Executive Leadership",
    progress: 74,
    keyResults: [
      { id: 11, title: "Acquire 30 new Enterprise CRM Clients", target: 30, current: 22, unit: "Clients" },
      { id: 12, title: "Increase Average Order Value to ฿150,000", target: 150000, current: 138000, unit: "THB" },
    ]
  },
  {
    id: 2,
    title: "Upgrade Platform UI to Next.js 16 Clean Architecture",
    period: "Q3 2026",
    owner: "Frontend Engineering Team",
    progress: 90,
    keyResults: [
      { id: 21, title: "Migrate 100% of CRM & Module Views to Next.js", target: 100, current: 90, unit: "%" },
      { id: 22, title: "Achieve Page Load Speed under 1.2s", target: 1.2, current: 0.8, unit: "Seconds" },
    ]
  }
];

export const MOCK_TASKS = [
  { id: 1, title: "Deploy Next.js 16 Multi-Theme Switcher", priority: "High", status: "In Progress", dueDate: "2026-07-30", assignee: "Frontend Agent" },
  { id: 2, title: "Verify Client & Lead Management Views", priority: "Medium", status: "Done", dueDate: "2026-07-28", assignee: "QA Lead" },
  { id: 3, title: "Refactor Accounting & Warehouse Pages", priority: "High", status: "In Progress", dueDate: "2026-07-31", assignee: "UI Architect" },
  { id: 4, title: "Finalize WooCommerce Connector Flow", priority: "Low", status: "To Do", dueDate: "2026-08-05", assignee: "Integration Dev" },
];

export const MOCK_TICKETS = [
  { id: "T-8091", subject: "Request for API token generation help", client: "Acme Technology Solutions", priority: "Medium", status: "Open", date: "2026-07-29 16:20" },
  { id: "T-8092", subject: "Invoice PDF download formatting inquiry", client: "Siam Digital Innovations", priority: "Low", status: "Answered", date: "2026-07-29 14:10" },
  { id: "T-8093", subject: "Warehouse Stock Sync Alert Notification", client: "Global Logistics Thailand", priority: "High", status: "In Progress", date: "2026-07-29 11:45" },
];

export const MOCK_STAFF_OUTSOURCING = [
  { id: 1, name: "Phukhao Tech Consulting", role: "React / Next.js Specialist", rate: "฿1,800/hr", allocation: "100%", status: "Assigned", project: "Perfex CRM Upgrade" },
  { id: 2, name: "Siam Cloud Solutions", role: "DevOps Architect", rate: "฿2,200/hr", allocation: "50%", status: "Assigned", project: "AWS Infrastructure Migration" },
  { id: 3, name: "Innovate Design Studio", role: "UI/UX Designer", rate: "฿1,500/hr", allocation: "0%", status: "Available", project: "-" },
];

export const MOCK_ACCOUNT_PLANNING = [
  {
    client: "Acme Technology Solutions",
    accountManager: "Somchai Jaidee",
    tier: "Strategic Platinum",
    swot: {
      strengths: ["Strong executive endorsement", "Long-term 3-year contract"],
      weaknesses: ["Legacy ERP migration delay"],
      opportunities: ["Expand to 2 regional branches in Chiang Mai"],
      threats: ["Competitor offering aggressive pricing"]
    }
  }
];
