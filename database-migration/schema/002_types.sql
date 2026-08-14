-- PostgreSQL Schema Migration: 002_types.sql
-- Description: Custom Enums and Domain Definitions

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'crm_status_enum') THEN
        CREATE TYPE crm_status_enum AS ENUM ('draft', 'published', 'archived', 'pending', 'active', 'inactive');
    END IF;
END$$;
