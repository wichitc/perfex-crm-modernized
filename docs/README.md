# Perfex CRM - Software Design Document Repository

Welcome to the complete Software Design Document repository for **Perfex CRM** and its **10 custom modules**. This documentation serves as a comprehensive system specification detailing the business logic, database structure, security workflows, and architecture patterns.

## Directory Index

| File | Description |
| --- | --- |
| [00_Project_Overview.md](00_Project_Overview.md) | High-level summary of the Perfex CRM platform, its 10 custom modules, and objectives. |
| [01_System_Architecture.md](01_System_Architecture.md) | Core system components, MVC/Service patterns, and communication models. |
| [02_Technology_Stack.md](02_Technology_Stack.md) | Programming languages, frameworks, libraries, and runtime environments. |
| [03_Project_Structure.md](03_Project_Structure.md) | Physical and logical layout of directories and core source modules. |
| [04_Business_Requirements.md](04_Business_Requirements.md) | Target market, core business pillars, and high-level business constraints. |
| [05_Functional_Requirements.md](05_Functional_Requirements.md) | Detailed functional specifications for core CRM and all modules. |
| [06_Non_Functional_Requirements.md](06_Non_Functional_Requirements.md) | Performance, security, scalability, and compliance metrics. |
| [07_User_Roles.md](07_User_Roles.md) | System actors, roles, permissions matrix, and access scopes. |
| [08_Use_Cases.md](08_Use_Cases.md) | Core interactions of users with the platform. |
| [09_Business_Workflow.md](09_Business_Workflow.md) | Logical business flows (e.g., Lead conversion, purchase stock, invoicing). |
| [10_System_Workflow.md](10_System_Workflow.md) | Technical system request-response workflows and request lifecycles. |
| [11_UI_Analysis.md](11_UI_Analysis.md) | UI dashboard design patterns, grid layouts, and standard components. |
| [12_Screen_Flow.md](12_Screen_Flow.md) | Navigation maps between core application dashboards and portals. |
| [13_Menu_Structure.md](13_Menu_Structure.md) | Nested sidebar and navigation menu items list, including module injections. |
| [14_API_Document.md](14_API_Document.md) | REST API endpoints, keys authentication, and payload formats. |
| [15_Database_Analysis.md](15_Database_Analysis.md) | General database structures, indexes, naming conventions, and constraints. |
| **Table Dictionary Parts** | **Detailed Schema Field Specifications of Database Tables** |
| ├─ [Part 1 (Core CRM)](16_Table_Dictionary.md) | Schema tables from tblactivity_log to tblweb_to_lead. |
| ├─ [Part 2 (Core CRM)](16_Table_Dictionary_Part2.md) | Schema tables from tblestimates to tbltracked_mails. |
| ├─ [Part 3 (Core CRM)](16_Table_Dictionary_Part3.md) | Schema tables from tblinvoicepaymentrecords to tblviews_tracking. |
| ├─ [Part 4 (Core CRM)](16_Table_Dictionary_Part4.md) | Schema tables from tblknowedge_base_article_feedback to tblticket_attachments. |
| ├─ [Part 5 (Core CRM)](16_Table_Dictionary_Part5.md) | Schema tables from tbltickets_pipe_log to tbluser_meta. |
| ├─ [Part 6 (Modules)](16_Table_Dictionary_Part6.md) | Accounting module tables (tblacc_account_history to tblacc_transfer_details). |
| ├─ [Part 7 (Modules)](16_Table_Dictionary_Part7.md) | Accounting and API module tables (tblacc_transfers to tblasset_depreciation_schedule). |
| ├─ [Part 8 (Modules)](16_Table_Dictionary_Part8.md) | Assets and HRM module tables (tblasset_custom_fields to tblhrm_contract_templates). |
| ├─ [Part 9 (Modules)](16_Table_Dictionary_Part9.md) | HRM and OKRs module tables (tblhrm_deduction_type to tblokr_setting_evaluation_criteria). |
| ├─ [Part 10 (Modules)](16_Table_Dictionary_Part10.md) | OKRs, Purchase, and Recruitment tables (tblokr_setting_question to tblpur_estimates). |
| ├─ [Part 11 (Modules)](16_Table_Dictionary_Part11.md) | Purchase and Recruitment tables (tblpur_faf_requests to tblrec_notifications). |
| └─ [Part 12 (Modules)](16_Table_Dictionary_Part12.md) | Staff Outsourcing, Warehouse, WooCommerce (tblrec_proposal to tblworkplace). |
| [17_ER_Diagram.md](17_ER_Diagram.md) | Entity-Relationship diagrams of core and module tables. |
| [18_Data_Flow.md](18_Data_Flow.md) | Data flow paths of leads, invoices, and support. |
| [19_Class_Diagram.md](19_Class_Diagram.md) | Core classes, controllers, and models hierarchy. |
| [20_Component_Diagram.md](20_Component_Diagram.md) | System components dependency layout. |
| [21_Sequence_Diagram.md](21_Sequence_Diagram.md) | Transactional sequence execution diagrams. |
| [22_State_Diagram.md](22_State_Diagram.md) | Lifecycle state transitions of invoices, tickets, tasks, etc. |
| [23_Security.md](23_Security.md) | OWASP protection, CSRF, XSS filtration, and encryption. |
| [24_Authentication.md](24_Authentication.md) | Multi-auth design, session lifecycle, and password hashing. |
| [25_Authorization.md](25_Authorization.md) | Access control matrix, role scopes, and permission checks. |
| [26_Validation_Rules.md](26_Validation_Rules.md) | Forms and validation specifications for data integrity. |
| [27_Configuration.md](27_Configuration.md) | App config keys and custom application preferences. |
| [28_Background_Jobs.md](28_Background_Jobs.md) | Handlers for asynchronous workflows, queues, and syncs. |
| [29_Scheduler.md](29_Scheduler.md) | Cron-based jobs scheduling structure and pipeline checks. |
| [30_Event_Flow.md](30_Event_Flow.md) | Core hooks system layout (Actions and Filters). |
| [31_Notification.md](31_Notification.md) | System alerts, email template mappings, and SMS layout. |
| [32_Logging.md](32_Logging.md) | Activity auditing and debug logging configuration. |
| [33_Error_Handling.md](33_Error_Handling.md) | Exception interception, database rollbacks, and recovery. |
| [34_Report_System.md](34_Report_System.md) | Analytics, sales graphs, ledger reporting, and TCPDF exports. |
| [35_File_Management.md](35_File_Management.md) | Upload pipelines, attachments structure, and elFinder manager. |
| [36_Integration.md](36_Integration.md) | WooCommerce integration and synchronization workflows. |
| [37_External_API.md](37_External_API.md) | Twilio, clickatell, stripe webhooks, and pusher implementations. |
| [38_Deployment.md](38_Deployment.md) | Web server redirection configs (.htaccess, IIS config) and folder permissions. |
| [39_Docker.md](39_Docker.md) | Docker environment container setup details. |
| [40_Testing.md](40_Testing.md) | Strategy for manual, integration, and CI/CD validation. |
| [41_Reverse_Engineering.md](41_Reverse_Engineering.md) | Structural findings, core design patterns, and assumptions. |
| [42_AI_Rebuild_Guide.md](42_AI_Rebuild_Guide.md) | Target rebuild path and milestones for automated development. |
| [43_Gap_Analysis.md](43_Gap_Analysis.md) | Architectural shortcomings of the legacy CodeIgniter 3 stack. |
| [44_Improvement_Suggestion.md](44_Improvement_Suggestion.md) | Recommendations for modernizing the CRM stack (Laravel, Vue/React). |
