# Enterprise i18n Architecture Specification

## Overview
This document specifies the Multi-Language / Internationalization (i18n) and Localization (L10n) Architecture for the modernized **Perfex CRM Portal**. The system is designed to provide full parity between **Thai (TH - Default)** and **English (EN)**, while guaranteeing future zero-code expansion for **Chinese (ZH)**, **Japanese (JA)**, **Korean (KO)**, and **Vietnamese (VI)**.

```
                         ┌─────────────────────────────────┐
                         │   User Preference / Language    │
                         │       (localStorage / Cookie)   │
                         └────────────────┬────────────────┘
                                          │
                                          ▼
                         ┌─────────────────────────────────┐
                         │     React LanguageProvider      │
                         │    (frontend/src/providers)     │
                         └────────────────┬────────────────┘
                                          │
                  ┌───────────────────────┼───────────────────────┐
                  ▼                       ▼                       ▼
      ┌───────────────────────┐ ┌───────────────────┐ ┌───────────────────────┐
      │   Domain Dictionaries │ │  Intl Formatters  │ │  RESTful API Headers  │
      │  (frontend/src/i18n)  │ │ (Date/Number/Cur) │ │  Accept-Language: th  │
      └───────────────────────┘ └───────────────────┘ └───────────────────────┘
```

---

## 1. Core Principles & Zero Hard-code Policy
1. **Presentation Concern Only**: Language choice affects display strings and locale formatting, never core business rules or database primary keys.
2. **Stable Enums & Action Codes**: Statuses, Roles, and API Error Codes MUST remain stable, uppercase English strings (`APPROVED`, `SUBMITTED`, `CUSTOMER_NOT_FOUND`).
3. **No Hard-coded UI Text**: All text presented in buttons, titles, tables, dialogs, form labels, toasts, tooltips, placeholders, and empty states MUST be rendered through `t("domain.key")`.
4. **Graceful Fallback Chain**: Requested Locale (`th`) ➔ Default Locale (`en`) ➔ Key Path (`domain.key`).

---

## 2. Directory & Dictionary Architecture

The translation dictionaries are structured by business domain:

```text
frontend/src/i18n/
├── th/
│   ├── common.json
│   ├── auth.json
│   ├── navigation.json
│   ├── dashboard.json
│   ├── customer.json
│   ├── lead.json
│   ├── invoice.json
│   ├── estimate.json
│   ├── accounting.json
│   ├── inventory.json
│   ├── purchase.json
│   ├── woocommerce.json
│   ├── recruitment.json
│   ├── okrs.json
│   ├── task.json
│   ├── ticket.json
│   ├── outsourcing.json
│   ├── account_planning.json
│   ├── settings.json
│   ├── validation.json
│   ├── notification.json
│   ├── error.json
│   └── report.json
└── en/
    └── (matching files)
```

---

## 3. Provider & Hook Design (`useTranslation`)

```typescript
export interface LanguageContextType {
  language: "th" | "en" | string;
  setLanguage: (lang: "th" | "en" | string) => void;
  t: (keyPath: string, params?: Record<string, any>) => string;
  formatDate: (date: Date | string, options?: Intl.DateTimeFormatOptions) => string;
  formatNumber: (val: number, options?: Intl.NumberFormatOptions) => string;
  formatCurrency: (amount: number, currency?: string) => string;
}
```

---

## 4. RESTful API & Backend Localization Contract

All HTTP requests from the frontend automatically include:
```http
Accept-Language: th
```

Backend responses return structured error objects with stable codes and localization keys:
```json
{
  "success": false,
  "error": {
    "code": "CUSTOMER_NOT_FOUND",
    "message": "Customer ID not found",
    "localizationKey": "error.customerNotFound",
    "params": { "id": "CUST-1002" }
  }
}
```

---

## 5. Expansion Protocol for New Languages

To add a new language (e.g. Japanese `ja`):
1. Create directory `frontend/src/i18n/ja/` with corresponding domain JSON files.
2. Register `"ja"` in `SUPPORTED_LANGUAGES` inside `frontend/src/providers/language-provider.tsx`.
3. Add flag indicator in `Header` language switcher.
4. **No core business logic edit required**.
