# Class Diagram

## Purpose
Exposes core classes, controllers, and models hierarchy inside the CodeIgniter 3 runtime.

## Scope
Core class structures, module extensions, and model interfaces.

## Detailed Explanation
In CodeIgniter, Controllers inherit from `MX_Controller` (for modular architecture) or `AdminController`/`ClientsController` (for authentication mappings). Models inherit from `App_Model`.

### Inheritance Class Structures
- **Core Controller**: `AdminController` extends `App_Controller` extends `CI_Controller`.
- **Core Model**: `App_Model` extends `CI_Model`.
- **Accounting Controller**: `Accounting` extends `AdminController`.
- **Recruitment Controller**: `Recruitment` extends `AdminController`.
- **Warehouse Controller**: `Warehouse` extends `AdminController`.

## Mermaid Diagrams
```mermaid
classDiagram
    class CI_Controller {
        +load
        +input
        +db
    }
    class App_Controller {
        +init_controller()
    }
    class AdminController {
        +__construct()
        +check_permission()
    }
    class Accounting {
        +chart_of_accounts()
        +journal_entries()
        +reconcile()
    }
    class Warehouse {
        +commodity_list()
        +goods_receipt()
        +goods_delivery()
    }
    class API_Controller {
        +__construct()
        +response()
    }

    CI_Controller <|-- App_Controller
    App_Controller <|-- AdminController
    AdminController <|-- Accounting
    AdminController <|-- Warehouse
    App_Controller <|-- API_Controller
```

## References
- [Project Structure](03_Project_Structure.md)
- [System Architecture](01_System_Architecture.md)
