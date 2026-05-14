# Database Migration Instructions

## Adding Stock and Image Support to Products

If you already have an existing database, you need to run this migration to add the new `stock` and `image` columns to the products table.

### Option 1: Using MySQL Command Line

```bash
mysql -u root -p agi_connect < database/add_stock_image.sql
```

### Option 2: Using phpMyAdmin

1. Open phpMyAdmin
2. Select the `agi_connect` database
3. Click on the "SQL" tab
4. Copy and paste the contents of `database/add_stock_image.sql`
5. Click "Go"

### Option 3: Manual SQL

Run this SQL command in your MySQL client:

```sql
USE agi_connect;

ALTER TABLE products 
ADD COLUMN stock INT DEFAULT 0 AFTER price,
ADD COLUMN image VARCHAR(255) NULL AFTER stock;
```

### For New Installations

If you're setting up the database for the first time, simply run:

```bash
mysql -u root -p agi_connect < database/schema.sql
```

The schema.sql file already includes the stock and image columns.

## Verify Migration

After running the migration, verify it worked:

```sql
DESCRIBE products;
```

You should see columns: id, seller_id, title, description, price, stock, image, created_at

## Directory Setup

Make sure the uploads directory exists and has proper permissions:

```bash
mkdir -p uploads/products
chmod 755 uploads/products
```

On Windows (if using XAMPP):
- The directory `uploads/products` should already be created
- Ensure your web server has write permissions to this folder
