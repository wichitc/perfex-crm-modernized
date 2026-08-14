-- PostgreSQL Schema Migration: 008_procedures.sql
-- Description: Maintenance and batch migration procedures

CREATE OR REPLACE PROCEDURE refresh_table_statistics()
LANGUAGE plpgsql
AS $$
BEGIN
    ANALYZE;
END;
$$;
