# Agri-Connect - Quick Setup Guide

## 🚀 5-Minute Setup

### Step 1: Database Setup (2 minutes)
```sql
-- Open MySQL command line or phpMyAdmin
CREATE DATABASE agriconnect;
USE agriconnect;

-- Import the schema file
SOURCE database/schema.sql;
-- OR in phpMyAdmin: Import > Choose database/schema.sql
```

### Step 2: Configure Database (1 minute)
Open `config.php` and update:
```php
$host = 'localhost';        // Usually 'localhost'
$dbname = 'agriconnect';    // Database name
$username = 'root';         // Your MySQL username
$password = '';             // Your MySQL password (empty for XAMPP default)
```

### Step 3: Start Server (1 minute)

#### Option A: PHP Built-in Server
```bash
cd AgriConnect
php -S localhost:8000
```
Then open: http://localhost:8000

#### Option B: XAMPP
1. Copy AgriConnect folder to `C:\xampp\htdocs\`
2. Start Apache and MySQL from XAMPP Control Panel
3. Open: http://localhost/AgriConnect

### Step 4: Test the System (1 minute)
1. Open the application in your browser
2. Click "Signup" button
3. Create a test account (Buyer or Seller)
4. Explore the features!

## ✅ Verification Checklist

- [ ] Database created successfully
- [ ] Schema imported without errors
- [ ] config.php updated with correct credentials
- [ ] Server running (Apache/PHP)
- [ ] Homepage loads correctly
- [ ] Can create new account
- [ ] Can login successfully

## 🐛 Common Issues

### "Connection failed" error
**Solution:** Check config.php credentials and ensure MySQL is running

### "Table doesn't exist" error
**Solution:** Import database/schema.sql again

### CSS not loading
**Solution:** Check that assets/styles.css exists and path is correct

### Session errors
**Solution:** Ensure PHP has write permissions to session directory

## 🎯 First Steps After Setup

### As a Seller:
1. Login to your account
2. Go to "My Products"
3. Add your first product
4. View it in the marketplace

### As a Buyer:
1. Login to your account
2. Browse "Product Marketplace"
3. Click on a product
4. Start a conversation with seller

## 📞 Need Help?

- Check the main README.md for detailed documentation
- Review the troubleshooting section
- Check file permissions
- Verify PHP version (8.1+)
- Verify MySQL version (8.0+)

## 🎨 Customization Quick Tips

### Change Colors:
Edit `assets/styles.css` - Look for `:root` variables

### Change Logo:
Replace `assets/images/logo.svg`

### Add Features:
Follow the existing code patterns in PHP files

---

**You're all set! Enjoy using Agri-Connect! 🌾**
