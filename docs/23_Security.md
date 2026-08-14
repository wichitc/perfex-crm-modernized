# Security

## Purpose
Documents security controls, OWASP protection, encryption configurations, and security configurations.

## Scope
CSRF filters, XSS cleaning, SQLi prevention, and password encryptions.

## Detailed Explanation
### 1. Cross-Site Scripting (XSS) Prevention
- CodeIgniter global XSS cleaning is enabled in `application/config/config.php`.
- All user input through POST/GET requests is sanitized: `$this->input->post('field', true)` (second argument enables sanitization filters).

### 2. SQL Injection (SQLi) Prevention
- CodeIgniter Active Record (Query Builder) is used exclusively for queries.
- Inputs are parameterized and escaped automatically.
- Direct queries (where used) use escaped arguments via `$this->db->escape()`.

### 3. Cross-Site Request Forgery (CSRF) Prevention
- CSRF tokens are injected into all form fields.
- Token validations are enforced by CodeIgniter on all POST requests.

## References
- [Authentication](24_Authentication.md)
- [Authorization](25_Authorization.md)
