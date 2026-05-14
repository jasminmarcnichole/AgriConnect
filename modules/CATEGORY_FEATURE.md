# Category Feature Migration Guide

## Overview
This update adds product categories to the buyer dashboard with search and filter functionality.

## Database Migration Required

Run the following SQL file to add the category column:
```bash
mysql -u root -p agi_connect < database/add_category_column.sql
```

Or manually execute:
```sql
USE agi_connect;
ALTER TABLE products ADD COLUMN category VARCHAR(50) DEFAULT 'Other' AFTER description;
UPDATE products SET category = 'Other' WHERE category IS NULL;
```

## Features Added

### Buyer Dashboard
- ✅ Displays all available products (stock > 0)
- ✅ Search bar for filtering by product name
- ✅ Category dropdown filter (8 categories)
- ✅ Product cards with images, prices, stock, and seller info
- ✅ Real-time filtering without page reload

### Seller Product Management
- ✅ Category selection when adding new products
- ✅ Category selection when editing products
- ✅ 8 predefined categories: Vegetables, Fruits, Grains, Seeds, Fertilizers, Tools, Livestock, Other

## Categories Available
1. Vegetables
2. Fruits
3. Grains
4. Seeds
5. Fertilizers
6. Tools
7. Livestock
8. Other

## Files Modified
- `dashboard.php` - Added products section for buyers with search and category filter
- `seller_products.php` - Added category dropdown to product form
- `edit_product.php` - Added category dropdown to edit form
- `database/add_category_column.sql` - Migration file for category column

## Testing
1. Run the database migration
2. Login as a buyer to see the new dashboard with products
3. Test search functionality
4. Test category filtering
5. Login as a seller and add/edit products with categories
