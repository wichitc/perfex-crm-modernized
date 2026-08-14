-- PostgreSQL Schema Migration: 010_views.sql
-- Description: PostgreSQL analytical and reporting views

CREATE OR REPLACE VIEW v_active_clients AS
SELECT userid, company, vat, phonenumber, city, state, datecreated
FROM tblclients
WHERE active = 1;

CREATE OR REPLACE VIEW v_invoice_summary AS
SELECT id, clientid, number, date, duedate, subtotal, total, status
FROM tblinvoices;
