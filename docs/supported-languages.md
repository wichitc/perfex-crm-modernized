# Supported Languages Specification

## Currently Active Languages

| Language Code | Language Name | Local Name | Direction | Default Currency | Date Format | Status |
| ------------- | ------------- | ---------- | --------- | ---------------- | ----------- | ------ |
| `th` | Thai | ไทย | LTR | THB (฿) | DD/MM/YYYY (พ.ศ.) | **Primary / Default** |
| `en` | English | English | LTR | USD ($) | MM/DD/YYYY | **Fully Supported** |

---

## Future Expandable Languages

| Language Code | Language Name | Local Name | Direction | Extensibility Readiness |
| ------------- | ------------- | ---------- | --------- | ----------------------- |
| `zh` | Chinese (Simplified) | 简体中文 | LTR | Ready |
| `ja` | Japanese | 日本語 | LTR | Ready |
| `ko` | Korean | 한국어 | LTR | Ready |
| `vi` | Vietnamese | Tiếng Việt | LTR | Ready |
| `ar` | Arabic | العربية | RTL | CSS Architecture Ready (`dir="rtl"`) |

---

## Priority & Resolution Order
1. **User Profile Preference**: Saved setting in User Profile (`user.preferred_language`).
2. **Local Storage**: Key `perfex_language` in client `localStorage`.
3. **HTTP Cookie**: Cookie `perfex_lang`.
4. **Browser `navigator.language`**: Detected user browser language setting.
5. **System Default**: `th` (Thai).
