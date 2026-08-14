"use client";

import React, { createContext, useContext, useEffect, useState } from "react";
import { formatDate, formatNumber, formatCurrency, formatPercent } from "@/lib/formatters";

// Import Thai Dictionaries
import thCommon from "@/i18n/th/common.json";
import thNavigation from "@/i18n/th/navigation.json";
import thDashboard from "@/i18n/th/dashboard.json";
import thCustomer from "@/i18n/th/customer.json";
import thLead from "@/i18n/th/lead.json";
import thInvoice from "@/i18n/th/invoice.json";
import thEstimate from "@/i18n/th/estimate.json";
import thAccounting from "@/i18n/th/accounting.json";
import thInventory from "@/i18n/th/inventory.json";
import thPurchase from "@/i18n/th/purchase.json";
import thWoocommerce from "@/i18n/th/woocommerce.json";
import thRecruitment from "@/i18n/th/recruitment.json";
import thOkrs from "@/i18n/th/okrs.json";
import thTask from "@/i18n/th/task.json";
import thTicket from "@/i18n/th/ticket.json";
import thOutsourcing from "@/i18n/th/outsourcing.json";
import thAccountPlanning from "@/i18n/th/account_planning.json";
import thSettings from "@/i18n/th/settings.json";
import thValidation from "@/i18n/th/validation.json";
import thNotification from "@/i18n/th/notification.json";
import thError from "@/i18n/th/error.json";
import thReport from "@/i18n/th/report.json";

// Import English Dictionaries
import enCommon from "@/i18n/en/common.json";
import enNavigation from "@/i18n/en/navigation.json";
import enDashboard from "@/i18n/en/dashboard.json";
import enCustomer from "@/i18n/en/customer.json";
import enLead from "@/i18n/en/lead.json";
import enInvoice from "@/i18n/en/invoice.json";
import enEstimate from "@/i18n/en/estimate.json";
import enAccounting from "@/i18n/en/accounting.json";
import enInventory from "@/i18n/en/inventory.json";
import enPurchase from "@/i18n/en/purchase.json";
import enWoocommerce from "@/i18n/en/woocommerce.json";
import enRecruitment from "@/i18n/en/recruitment.json";
import enOkrs from "@/i18n/en/okrs.json";
import enTask from "@/i18n/en/task.json";
import enTicket from "@/i18n/en/ticket.json";
import enOutsourcing from "@/i18n/en/outsourcing.json";
import enAccountPlanning from "@/i18n/en/account_planning.json";
import enSettings from "@/i18n/en/settings.json";
import enValidation from "@/i18n/en/validation.json";
import enNotification from "@/i18n/en/notification.json";
import enError from "@/i18n/en/error.json";
import enReport from "@/i18n/en/report.json";

export type LanguageCode = "th" | "en";

const dictionaries: Record<string, Record<string, any>> = {
  th: {
    common: thCommon,
    navigation: thNavigation,
    dashboard: thDashboard,
    customer: thCustomer,
    lead: thLead,
    invoice: thInvoice,
    estimate: thEstimate,
    accounting: thAccounting,
    inventory: thInventory,
    purchase: thPurchase,
    woocommerce: thWoocommerce,
    recruitment: thRecruitment,
    okrs: thOkrs,
    task: thTask,
    ticket: thTicket,
    outsourcing: thOutsourcing,
    account_planning: thAccountPlanning,
    settings: thSettings,
    validation: thValidation,
    notification: thNotification,
    error: thError,
    report: thReport,
  },
  en: {
    common: enCommon,
    navigation: enNavigation,
    dashboard: enDashboard,
    customer: enCustomer,
    lead: enLead,
    invoice: enInvoice,
    estimate: enEstimate,
    accounting: enAccounting,
    inventory: enInventory,
    purchase: enPurchase,
    woocommerce: enWoocommerce,
    recruitment: enRecruitment,
    okrs: enOkrs,
    task: enTask,
    ticket: enTicket,
    outsourcing: enOutsourcing,
    account_planning: enAccountPlanning,
    settings: enSettings,
    validation: enValidation,
    notification: enNotification,
    error: enError,
    report: enReport,
  },
};

interface LanguageContextType {
  language: LanguageCode;
  setLanguage: (lang: LanguageCode) => void;
  t: (keyPath: string, params?: Record<string, any>) => string;
  formatDate: (date: Date | string | number, options?: Intl.DateTimeFormatOptions) => string;
  formatNumber: (val: number, options?: Intl.NumberFormatOptions) => string;
  formatCurrency: (amount: number, currency?: string) => string;
  formatPercent: (val: number) => string;
}

const LanguageContext = createContext<LanguageContextType | undefined>(undefined);

export function LanguageProvider({ children }: { children: React.ReactNode }) {
  const [language, setLanguageState] = useState<LanguageCode>("th");

  useEffect(() => {
    const saved = localStorage.getItem("perfex_language") as LanguageCode;
    if (saved && (saved === "th" || saved === "en")) {
      setLanguageState(saved);
      document.documentElement.lang = saved;
    } else {
      document.documentElement.lang = "th";
    }
  }, []);

  const setLanguage = (newLang: LanguageCode) => {
    setLanguageState(newLang);
    localStorage.setItem("perfex_language", newLang);
    document.cookie = `perfex_lang=${newLang}; path=/; max-age=31536000`;
    document.documentElement.lang = newLang;
  };

  const getNestedValue = (obj: any, path: string[]) => {
    let current = obj;
    for (const key of path) {
      if (current === undefined || current === null) return undefined;
      current = current[key];
    }
    return current;
  };

  const t = (keyPath: string, params?: Record<string, any>): string => {
    const parts = keyPath.split(".");
    if (parts.length < 2) return keyPath;

    const domain = parts[0];
    const restPath = parts.slice(1);

    // 1. Try Primary Language
    let translation = getNestedValue(dictionaries[language]?.[domain], restPath);

    // 2. Fallback to English if missing
    if (translation === undefined && language !== "en") {
      translation = getNestedValue(dictionaries.en?.[domain], restPath);
    }

    // 3. Fallback to keyPath
    if (translation === undefined || typeof translation !== "string") {
      return keyPath;
    }

    // Dynamic Parameter Interpolation: replace {{paramName}}
    if (params) {
      return translation.replace(/\{\{(\w+)\}\}/g, (_, key) => {
        return params[key] !== undefined ? String(params[key]) : `{{${key}}}`;
      });
    }

    return translation;
  };

  return (
    <LanguageContext.Provider
      value={{
        language,
        setLanguage,
        t,
        formatDate: (date, options) => formatDate(date, language, options),
        formatNumber: (val, options) => formatNumber(val, language, options),
        formatCurrency: (amount, currency = language === "th" ? "THB" : "USD") =>
          formatCurrency(amount, currency, language),
        formatPercent: (val) => formatPercent(val, language),
      }}
    >
      {children}
    </LanguageContext.Provider>
  );
}

export function useTranslation() {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error("useTranslation must be used within a LanguageProvider");
  }
  return context;
}
