# Localization (L10n) Technical Guide

## Standard Formatters & Helper Utilities

All UI components MUST import formatting utilities from `@/lib/formatters` or `useTranslation()`:

```typescript
import { useTranslation } from "@/providers/language-provider";

export function TransactionAmount({ amount, date }: { amount: number; date: string }) {
  const { formatCurrency, formatDate } = useTranslation();

  return (
    <div>
      <span>{formatCurrency(amount, "THB")}</span>
      <span>{formatDate(date)}</span>
    </div>
  );
}
```

### Date Formatting Options
* Thai (`th`): `14 สิงหาคม 2569` or `14/08/2569` (Buddhist Era).
* English (`en`): `August 14, 2026` or `08/14/2026` (Gregorian).

### Currency Formatting Options
* Thai (`th`): `฿1,250.00`
* English (`en`): `$1,250.00`
