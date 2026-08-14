# File Management

## Purpose
Documents upload pipelines, attachment storage architectures, and elFinder integrations.

## Scope
Lead attachments, task uploads, support files, and server security.

## Detailed Explanation
### 1. elFinder Media Manager
- Core admin file manager is built on top of elFinder, allowing staff members with permissions to upload and browse documents.

### 2. Secure Upload Pipeline
- Enforced in controllers using helper functions like `handle_task_attachments_upload()` or `handle_lead_attachments_upload()`.
- Restricts extensions (blocks script files like .php, .exe, .sh).
- Stores attachments in `uploads/` folder with hashed filenames or grouped in subdirectories by entity ID.

## References
- [Security](23_Security.md)
- [Project Structure](03_Project_Structure.md)
