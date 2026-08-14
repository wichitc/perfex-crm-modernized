# Entity-Relationship (ER) Diagram

## Purpose
Exposes data entities and foreign key linkages visually using Mermaid.

## Scope
Outlines structural links between leads, clients, contacts, invoices, payments, projects, tasks, staff, and module extensions.

## Detailed Explanation
This diagram highlights how CRM clients, projects, support tickets, and invoicing modules are mapped to contacts, staff, and module-specific tables.

## Mermaid Diagrams
```mermaid
erDiagram
    tblclients ||--o{ tblcontacts : "has contacts"
    tblclients ||--o{ tblinvoices : "billed to"
    tblclients ||--o{ tblprojects : "has projects"
    tblclients ||--o{ tblestimates : "requested by"
    tblclients ||--o{ tblproposals : "pitched to"
    
    tblcontacts ||--o{ tbltickets : "submits"
    tblinvoices ||--o{ tblinvoicepaymentrecords : "has payments"
    
    tblprojects ||--o{ tbltasks : "contains tasks"
    tblprojects ||--o{ tblproject_members : "assigned to"
    
    tblstaff ||--o{ tblproject_members : "is member"
    tblstaff ||--o{ tbltask_assigned : "is assigned task"
    tblstaff ||--o{ tblticket_replies : "answers ticket"
    
    tbltasks ||--o{ tbltask_assigned : "assigned staff"
    
    %% Accounting Relationships
    tblacc_accounts ||--o{ tblacc_account_history : "has balance entries"
    tblacc_journal_entries ||--o{ tblacc_account_history : "documents journal records"
    
    %% HRM & Recruitment Relationships
    tblstaff ||--o{ tblstaff_contract : "has contract"
    tblrec_campaign ||--o{ tblrec_candidate : "attracts applicants"
    tblrec_candidate ||--o{ tblrec_interview : "is scheduled interview"
    
    %% Warehouse & Purchase Relationships
    tblwarehouse ||--o{ tblwh_inventory_serial_numbers : "holds inventory"
    tblpur_orders ||--o{ tblpur_order_detail : "lists ordered items"
    tblpur_vendor ||--o{ tblpur_orders : "receives order"
    
    %% WooCommerce Relationships
    tblclients ||--o{ tblwoocommerce_customers : "linked WooCommerce client"
    tblwoocommerce_orders ||--o{ tblinvoices : "syncs invoice"
```

## References
- [Database Analysis](15_Database_Analysis.md)
- [Table Dictionary](16_Table_Dictionary.md)
