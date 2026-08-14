-- PostgreSQL Schema Migration: 011_permissions.sql
-- Description: Role grants and schema permissions

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres;
