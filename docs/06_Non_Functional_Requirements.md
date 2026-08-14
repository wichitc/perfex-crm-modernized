# Non-Functional Requirements

## Purpose
Specifies quality standards, security constraints, performance metrics, and scalability criteria.

## Scope
Response times, data isolation, GDPR compliance, and backup operations.

## Detailed Explanation
### 1. Security & Compliance
- **GDPR Compliance**: Support consent management, right to be forgotten (gdpr delete requests), and exporting personal data.
- **CSRF & XSS Protection**: Enforced by CodeIgniter security filters on all post requests.
- **Data Encryption**: Encrypt API keys and external credentials in the database using AES-256 equivalent wrappers.

### 2. Performance & Tuning
- **Page Load Time**: Average admin dashboard load under 1.5 seconds.
- **InnoDB Transactions**: Ensure all transactional queries (such as invoicing and double-entry book balancing) run inside ACID database transactions.
- **Indexing**: Core database foreign keys and filter columns must be indexed (e.g. `tblinvoices.clientid`, `tbltasks.rel_id`).

### 3. Localization & Multi-currency
- **Multi-language**: Load language files dynamically based on staff or customer preferences.
- **Currency conversions**: Dynamic multi-currency definitions for invoicing.

## References
- [Security](23_Security.md)
- [Database Analysis](15_Database_Analysis.md)
