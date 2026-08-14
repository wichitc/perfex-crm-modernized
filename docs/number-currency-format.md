# Number & Currency Formatting Specification

## Native `Intl` Standardization

Do NOT use custom string concatenation (`"฿" + amount`). Use standard `Intl.NumberFormat`:

```typescript
// Number Format
new Intl.NumberFormat(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(1234567.89);
// Thai & English: 1,234,567.89

// Currency Format
new Intl.NumberFormat(locale, { style: "currency", currency: "THB" }).format(1250);
// Thai: ฿1,250.00

new Intl.NumberFormat(locale, { style: "currency", currency: "USD" }).format(1250);
// English: $1,250.00
```
