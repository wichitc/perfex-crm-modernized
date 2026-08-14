# Authentication & Security Architecture

This document details the authentication protocols, token lifecycles, HTTP-only cookie security, and password hashing algorithms used in the modernized FastAPI backend.

---

## 1. Authentication Overview

The system uses a hybrid **JWT (JSON Web Token) + HttpOnly Cookie** authentication protocol:
- **Access Tokens**: Short-lived JWT Bearer tokens passed via standard `Authorization: Bearer <token>` HTTP header.
- **Refresh Tokens**: Long-lived refresh tokens stored securely in **HttpOnly, SameSite=Strict** cookies to prevent XSS token theft.

```text
Browser Client                     FastAPI Backend
      │                                   │
      ├─────── POST /auth/login ─────────►│
      │   (email, password)               │ Verify BCrypt Hash & Active Status
      │                                   │
      │◄────── 200 OK + JWT ──────────────┤
      │   (Set-Cookie: refreshtoken=...)  │
      │                                   │
      ├─────── GET /auth/me ─────────────►│
      │   (Header: Bearer JWT)            │ Verify JWT Signature & Claims
      │                                   │
      │◄────── 200 OK (User Profile) ─────┤
      │                                   │
```

---

## 2. Password Hashing Specification
- **Algorithm**: BCrypt (`passlib[bcrypt]`)
- **Cost Factor**: 12 Rounds
- **Salt Generation**: Cryptographically secure random salt generated per user.

---

## 3. JWT Token Claims & Payloads

### 3.1 Access Token Payload
```json
{
  "sub": "1",
  "email": "admin@crm.com",
  "role": "Admin",
  "scopes": [
    "admin",
    "core:read",
    "core:write",
    "accounting:all",
    "hrm:all",
    "recruitment:all"
  ],
  "type": "access",
  "exp": 1786636800
}
```

### 3.2 Refresh Token Payload
```json
{
  "sub": "1",
  "type": "refresh",
  "exp": 1787241600
}
```

---

## 4. Security Enforcement & Boundary Rules

1. **HttpOnly Cookie Configuration**:
   - `httponly`: `True` (Inaccessible to client JS)
   - `samesite`: `"strict"` (Prevents CSRF)
   - `max_age`: `604800` (7 Days)
   - `secure`: `False` in dev, `True` in HTTPS production.

2. **Unauthenticated Error Handling**:
   - Missing or invalid tokens return HTTP 401 Unauthorized (`{"detail": "Could not validate credentials"}`).
   - Inactive user accounts (`active != 1`) return HTTP 403 Forbidden (`{"detail": "Inactive user account"}`).
