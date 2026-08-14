# Gap Analysis

## Purpose
Exposes architectural limits and structural shortcomings of the current system.

## Scope
Legacy constraints, ORM limitations, and modular redundancies.

## Detailed Explanation
### 1. Legacy Framework
- Built on top of **CodeIgniter 3** (released in 2015), which lack modern PHP features like dependency injection, PSR standards, middleware queues, and native migrations.

### 2. Missing ORM (Object-Relational Mapping)
- The system relies on raw query strings and basic Active Record array manipulations. This leads to duplicate query writing, lack of entity schemas in code, and manual index configurations.

### 3. Modular Redundancies
- Modules duplicate third-party packages (e.g. PhpSpreadsheet and PDF libraries are loaded separately by different modules, increasing deployment footprint).

## References
- [Improvement Suggestion](44_Improvement_Suggestion.md)
- [Technology Stack](02_Technology_Stack.md)
