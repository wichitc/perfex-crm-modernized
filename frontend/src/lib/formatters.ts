/**
 * Shared Internationalization (i18n) & Localization (L10n) Formatters
 * Powered by native browser Intl APIs
 */

export function formatDate(
  dateInput: Date | string | number,
  locale: string = "th",
  options?: Intl.DateTimeFormatOptions
): string {
  if (!dateInput) return "";
  const date = typeof dateInput === "string" || typeof dateInput === "number" ? new Date(dateInput) : dateInput;
  if (isNaN(date.getTime())) return String(dateInput);

  const targetLocale = locale === "th" ? "th-TH" : "en-US";
  const defaultOptions: Intl.DateTimeFormatOptions = {
    year: "numeric",
    month: "short",
    day: "numeric",
    ...options,
  };

  return new Intl.DateTimeFormat(targetLocale, defaultOptions).format(date);
}

export function formatNumber(
  value: number,
  locale: string = "th",
  options?: Intl.NumberFormatOptions
): string {
  if (value === undefined || value === null || isNaN(value)) return "0";
  const targetLocale = locale === "th" ? "th-TH" : "en-US";
  const defaultOptions: Intl.NumberFormatOptions = {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
    ...options,
  };

  return new Intl.NumberFormat(targetLocale, defaultOptions).format(value);
}

export function formatCurrency(
  amount: number,
  currency: string = "THB",
  locale: string = "th"
): string {
  if (amount === undefined || amount === null || isNaN(amount)) return "0";
  const targetLocale = locale === "th" ? "th-TH" : "en-US";

  return new Intl.NumberFormat(targetLocale, {
    style: "currency",
    currency: currency,
    minimumFractionDigits: 2,
  }).format(amount);
}

export function formatPercent(
  value: number,
  locale: string = "th"
): string {
  if (value === undefined || value === null || isNaN(value)) return "0%";
  const targetLocale = locale === "th" ? "th-TH" : "en-US";

  return new Intl.NumberFormat(targetLocale, {
    style: "percent",
    minimumFractionDigits: 0,
    maximumFractionDigits: 1,
  }).format(value / 100);
}
