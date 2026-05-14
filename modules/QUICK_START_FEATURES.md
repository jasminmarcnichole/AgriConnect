# Quick Start Guide - New Product Features

## 🚀 Getting Started with Product Images & Stock Management

### Step 1: Run Database Migration

Choose one of these methods:

#### Method A: Web-Based (Easiest)
1. Open your browser
2. Navigate to: `http://localhost/AgriConnect/migrate.php`
3. Click through the migration process
4. Delete `migrate.php` file after completion

#### Method B: Command Line
```bash
mysql -u root -p agi_connect < database/add_stock_image.sql
```

#### Method C: phpMyAdmin
1. Open phpMyAdmin
2. Select `agi_connect` database
3. Go to SQL tab
4. Paste and run:
```sql
ALTER TABLE products 
ADD COLUMN stock INT DEFAULT 0 AFTER price,
ADD COLUMN image VARCHAR(255) NULL AFTER stock;
```

### Step 2: Verify Directory Structure

Make sure these directories exist:
```
AgriConnect/
├── uploads/
│   ├── products/        ← Product images go here
│   └── id_documents/    ← Seller IDs go here
```

If not, create them:
- **Windows (XAMPP):** Already created automatically
- **Linux/Mac:** Run `mkdir -p uploads/products`

### Step 3: Test the Features

#### As a Seller:
1. Login to your seller account
2. Go to "My Products"
3. Add a new product:
   - Upload an image (JPG, PNG, WEBP)
   - Enter title, description
   - Set price in Philippine Peso (₱)
   - Set stock quantity
4. Click "Publish to Marketplace Now"
5. View your product - you'll see "Edit Product Information" button
6. Click edit to update anytime

#### As a Buyer:
1. Visit the landing page (logged out)
2. Scroll to "Featured Products" section
3. Click any product → Login/Signup popup appears
4. Login and browse products
5. Check stock availability badges
6. View product images
7. Contact seller if product is in stock

#### As Admin:
1. Products with images appear on landing page
2. Stock levels are visible everywhere
3. Out of stock products show red badges

## 🎨 New Features Overview

### 1. Product Images
- **Upload:** When creating/editing products
- **Formats:** JPG, JPEG, PNG, WEBP
- **Size Limit:** 5MB
- **Storage:** `uploads/products/`
- **Fallback:** Default image if none uploaded

### 2. Stock Management
- **Set Stock:** When creating products
- **Update Stock:** Edit product page
- **Visual Indicators:**
  - 🔴 Red badge: Out of Stock (0)
  - 🟡 Yellow badge: Low Stock (< 10)
  - 🔵 Blue badge: In Stock (≥ 10)
- **Buyer Protection:** Can't contact seller if out of stock

### 3. Seller Self-Management
- **Own Products:** Show "Edit" instead of "Contact Seller"
- **Edit Anytime:** Update title, description, price, stock, image
- **No Self-Contact:** Prevents sellers from messaging themselves

### 4. Landing Page Products
- **Featured Section:** Shows 6 latest products
- **Login Required:** Non-logged users must login to view details
- **Conversion Tool:** Increases user registrations
- **Real Images:** Displays actual product photos

## 📝 Common Tasks

### Adding a Product with Image
```
1. Go to "My Products"
2. Fill in product form
3. Click "Choose File" for image
4. Select your product photo
5. Enter stock quantity
6. Set price
7. Submit
```

### Editing a Product
```
1. View your product
2. Click "Edit Product Information"
3. Change any field
4. Upload new image (optional)
5. Update stock
6. Save changes
```

### Checking Stock Levels
```
- Landing page: Badge on product card
- Marketplace: Badge on product card
- Product page: Large badge + stock count
- Seller dashboard: Badge on each product
```

## 🔧 Troubleshooting

### Images Not Uploading?
- Check `uploads/products/` exists
- Verify folder permissions (755)
- Ensure file size < 5MB
- Use supported formats (JPG, PNG, WEBP)

### Migration Failed?
- Check database connection in `config.php`
- Ensure MySQL is running
- Verify database name is correct
- Try web-based migration tool

### Products Not Showing on Landing Page?
- Ensure seller is verified (approved status)
- Check if products exist in database
- Clear browser cache
- Verify database query in `index.php`

### Can't Edit Own Product?
- Ensure you're logged in as the seller
- Check product ownership (seller_id matches)
- Verify session is active

## 💡 Tips & Best Practices

### For Sellers:
1. **Use High-Quality Images:** Clear, well-lit photos sell better
2. **Keep Stock Updated:** Update regularly to avoid disappointment
3. **Detailed Descriptions:** More info = more sales
4. **Competitive Pricing:** Research market rates
5. **Monitor Stock:** Set alerts when stock is low

### For Buyers:
1. **Check Stock First:** Avoid contacting for out-of-stock items
2. **View Images:** Zoom in to see product quality
3. **Read Descriptions:** Understand what you're buying
4. **Contact Sellers:** Ask questions before purchasing

### For Admins:
1. **Verify Sellers:** Only approve legitimate farmers
2. **Monitor Products:** Check for inappropriate content
3. **Review Images:** Ensure quality standards
4. **Track Metrics:** Monitor product listings

## 📊 Feature Comparison

| Feature | Before | After |
|---------|--------|-------|
| Product Images | ❌ Placeholder only | ✅ Real uploads |
| Stock Tracking | ❌ Not available | ✅ Full management |
| Edit Products | ❌ Not possible | ✅ Full editing |
| Landing Products | ❌ None | ✅ Featured section |
| Self-Contact | ⚠️ Possible | ✅ Prevented |
| Stock Badges | ❌ None | ✅ Color-coded |

## 🎯 Next Steps

1. ✅ Run migration
2. ✅ Test product creation with images
3. ✅ Update existing products with images
4. ✅ Set stock levels for all products
5. ✅ Test landing page product display
6. ✅ Verify edit functionality
7. ✅ Delete `migrate.php` for security

## 📞 Need Help?

- Check `PRODUCT_FEATURES_UPDATE.md` for detailed documentation
- Review `database/MIGRATION_README.md` for migration help
- See main `README.md` for general setup
- Contact support if issues persist

---

**Happy Selling! 🌾**
