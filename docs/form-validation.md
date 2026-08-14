# Form Validation Specifications - Perfex CRM

## Form Validation Rules (Zod & React Hook Form Schema)

### 1. Client Creation Schema
```typescript
import * as zod from "zod";

export const clientSchema = zod.object({
  company: zod.string().min(2, "Company name is required"),
  vat: zod.string().optional(),
  phonenumber: zod.string().min(9, "Valid phone number required"),
  city: zod.string().min(2, "City is required"),
  state: zod.string().optional(),
  website: zod.string().url("Invalid URL format").optional().or(zod.literal("")),
});
```

### 2. Invoice Creation Schema
```typescript
export const invoiceSchema = zod.object({
  clientid: zod.number({ required_error: "Client selection is required" }),
  date: zod.string().min(1, "Issue date required"),
  duedate: zod.string().min(1, "Due date required"),
  items: zod.array(
    zod.object({
      description: zod.string().min(1, "Line item description required"),
      qty: zod.number().min(1, "Quantity must be at least 1"),
      rate: zod.number().min(0, "Rate must be positive"),
    })
  ).min(1, "At least one line item is required"),
});
```

### 3. Lead Entry Schema
```typescript
export const leadSchema = zod.object({
  name: zod.string().min(2, "Lead name required"),
  email: zod.string().email("Invalid email address"),
  status: zod.enum(["New", "Contacted", "Qualified", "Proposal Sent", "Won", "Lost"]),
  value: zod.number().min(0, "Value must be positive"),
  source: zod.string().min(1, "Lead source required"),
});
```
