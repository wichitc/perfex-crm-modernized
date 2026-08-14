# API Document

## Purpose
Exposes external REST API, endpoints, request formats, response payloads, and API key authentications.

## Scope
Endpoints added by the REST API Module (`api`).

## Detailed Explanation
The REST API module enables external systems to sync with Perfex CRM. Authentication is handled by passing an API key in the headers.

### API Specifications
- **Authentication Header**: `Authorization: [API_KEY]` (validated against `tbluser_api.token`).
- **Data Format**: JSON for requests and responses.
- **Error Handling**: Standard HTTP status codes (400 Bad Request, 401 Unauthorized, 404 Not Found, 500 Internal Server Error).

### Core Endpoints Table
| Method | Endpoint | Description | Request Payload | Response Sample |
| --- | --- | --- | --- | --- |
| **GET** | `api/clients` | Fetch customers list. | Query params: `limit`, `offset` | Array of client objects |
| **POST** | `api/clients` | Create a customer. | `{"company":"Acme Inc","vat":"US1234"}` | `{"status":true,"id":1}` |
| **GET** | `api/invoices` | Fetch invoice list. | Query params: `status` | Array of invoice objects |
| **POST** | `api/invoices` | Create an invoice. | `{"clientid":1,"date":"2026-07-22"}` | `{"status":true,"id":55}` |
| **GET** | `api/contacts` | Fetch contact details. | Query params: `userid` | Contact object |
| **POST** | `api/leads` | Add a new lead. | `{"name":"John Doe","email":"john@test.com"}` | `{"status":true,"id":12}` |

## References
- [Integration](36_Integration.md)
- [External API](37_External_API.md)
