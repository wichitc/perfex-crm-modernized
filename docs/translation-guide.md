# Translation & Naming Guide

## Key Structure Guidelines

1. **Semantic Dot-Notation**:
   ```json
   {
     "customer": {
       "title": "Customer Management",
       "create": "Create Customer",
       "edit": "Edit Customer",
       "delete": "Delete Customer",
       "search": "Search customers...",
       "empty": "No customers found.",
       "createdSuccessfully": "Customer {{name}} created successfully."
     }
   }
   ```

2. **Prohibited Key Names**:
   * Never use `text1`, `label2`, `message3`.
   * Never use raw Thai or English sentences as keys.

3. **Parameter Interpolation**:
   ```typescript
   t("customer.createdSuccessfully", { name: "Acme Corp" })
   // Thai: "สร้างลูกค้า Acme Corp สำเร็จแล้ว"
   // English: "Customer Acme Corp created successfully."
   ```

4. **Pluralization**:
   ```json
   {
     "item_zero": "ไม่มีรายการ",
     "item_one": "1 รายการ",
     "item_other": "{{count}} รายการ"
   }
   ```
