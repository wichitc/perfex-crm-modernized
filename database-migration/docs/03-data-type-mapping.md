# Phase 2: Data Type Mapping Matrix

| Legacy MySQL Type | PostgreSQL Type | Notes / Handling |
|---|---|---|
| `int` / `int(11)` | `INTEGER` | Signed 32-bit integer |
| `bigint` | `BIGINT` | Signed 64-bit integer |
| `tinyint(1)` / `tinyint` | `SMALLINT` | Maps exact range (-128 to 127) |
| `smallint` | `SMALLINT` | 16-bit integer |
| `decimal(15,2)` | `NUMERIC(15,2)` | Exact monetary precision, NO FLOAT |
| `double` / `float` | `DOUBLE PRECISION` | Scientific floating point |
| `varchar(N)` | `VARCHAR(N)` | Direct string length mapping |
| `text` / `mediumtext` / `longtext` | `TEXT` | PostgreSQL variable-length character text |
| `datetime` | `TIMESTAMP` | ISO-8601 timestamp without timezone |
| `date` | `DATE` | Calendar date |
| `time` | `TIME` | Time of day |
