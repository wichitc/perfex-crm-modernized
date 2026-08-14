-- PostgreSQL Schema Migration: 001_extensions.sql
-- Description: Required PostgreSQL Extensions for Perfex CRM

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "unaccent";
