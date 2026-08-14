# i18n Testing & QA Plan

## Automated Verification Workflow

1. **Missing Key Detection**: Run translation key scanner against `frontend/src/i18n/th/` and `frontend/src/i18n/en/` to assert exact 1:1 key parity.
2. **Zero Hard-Code Linting**: Scan `.tsx` components for untranslated text strings.
3. **E2E Language Switch Test**:
   - `E2E-TH-001`: Switch to Thai, navigate all 16 pages, verify zero English UI string leaks.
   - `E2E-EN-001`: Switch to English, navigate all 16 pages, verify zero Thai UI string leaks.
4. **Mock Language Test (`JA`)**: Verify architecture dynamically switches to Japanese mock dictionary without code changes.
