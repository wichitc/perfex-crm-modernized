# Date & Timezone Format Specification

## Database Storage Standard
* All date/time values in PostgreSQL/Database and RESTful APIs MUST be stored and transmitted in **ISO 8601 UTC format**:
  `2026-08-14T06:30:00Z`

## Display Formatting Matrix

| Locale | Timezone | Full Date Display | Short Date Display | Time Display |
| ------ | -------- | ----------------- | ------------------ | ------------ |
| `th` | Asia/Bangkok | 14 สิงหาคม 2569 | 14/08/2569 | 13:30 น. |
| `en` | Asia/Bangkok | August 14, 2026 | 08/14/2026 | 1:30 PM |
| `en` | America/New_York | August 14, 2026 | 08/14/2026 | 2:30 AM |
