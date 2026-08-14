# Database Localization Architecture

## Entity Translation Models

For master business entities that require multi-lingual fields (e.g. Products, Categories, Terms & Conditions):

### Strategy 1: Column-based Translation (For fixed TH/EN fields)
```sql
CREATE TABLE products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    sku VARCHAR(64) UNIQUE NOT NULL,
    price NUMERIC(15, 2) NOT NULL,
    name_th VARCHAR(255) NOT NULL,
    name_en VARCHAR(255) NOT NULL,
    description_th TEXT,
    description_en TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
```

### Strategy 2: Translation Table (For infinite future language scaling)
```sql
CREATE TABLE products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    sku VARCHAR(64) UNIQUE NOT NULL,
    price NUMERIC(15, 2) NOT NULL
);

CREATE TABLE product_translations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    product_id UUID REFERENCES products(id) ON DELETE CASCADE,
    locale VARCHAR(10) NOT NULL, -- e.g. 'th', 'en', 'zh', 'ja'
    name VARCHAR(255) NOT NULL,
    description TEXT,
    CONSTRAINT idx_product_locale UNIQUE (product_id, locale)
);
```
