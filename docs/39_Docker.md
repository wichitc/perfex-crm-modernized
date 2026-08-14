# Docker

## Purpose
Details Docker environments, container orchestrations, and volume configurations.

## Scope
Docker Compose layout, base images, and system linkings.

## Detailed Explanation
A rebuild of the CRM can be containerized using a multi-container Docker Compose setup.

### Proposed Docker Compose Layout
- **app**: PHP 8.1 / 8.2-apache base image.
  - Mounts: Local volume to `/var/www/html`.
  - Configures: URL rewriting (.htaccess).
- **db**: MySQL 8.0 / MariaDB 10.6 base image.
  - Environment: `MYSQL_DATABASE=perfex_crm`, custom root passwords.
  - Mounts: Persistence volume to `/var/lib/mysql`.

## References
- [Deployment](38_Deployment.md)
- [Technology Stack](02_Technology_Stack.md)
