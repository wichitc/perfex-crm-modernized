# Authentication

## Purpose
Documents authentication designs, session lifecycles, and passwords crypts.

## Scope
Administrative dashboard logins, client portal contacts, candidate listings, and REST API key checks.

## Detailed Explanation
### 1. Staff and Customer Authentications
- Enforced via `Authentication_model.php`.
- Passwords are encrypted using bcrypt hashing via the PHPass library wrapper.
- Sessions are stored in the database (`tblsessions`) to prevent session hijacking and fixations.

### 2. Candidate & Vendor Portals
- Custom candidate portals (`recruitment`) and vendor portals (`purchase`) authenticate candidates/suppliers using their specific credentials.

### 3. API Key Authorization
- REST API module (`api`) authenticates clients by matching the `Authorization` header against key records inside `tbluser_api`.

## References
- [Security](23_Security.md)
- [Authorization](25_Authorization.md)
