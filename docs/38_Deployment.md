# Deployment

## Purpose
Details production deployment parameters, rewrite rules, and security folder privileges.

## Scope
Web server configurations (.htaccess, web.config), PHP versions, and directory access.

## Detailed Explanation
### Apache Config (.htaccess)
Ensures routing requests are routed to `index.php` and secures the application:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?/$1 [L]
```

### Directory Permissions
- `uploads/`: Write permission (755 or 777).
- `application/config/`: Read-only in production (644).
- `temp/` and `application/cache/`: Write permission.

## References
- [Docker](39_Docker.md)
- [Configuration](27_Configuration.md)
