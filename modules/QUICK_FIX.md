# 🔧 QUICK FIX - "Failed to Update Status" Error

## The Problem
Your database is missing the new columns needed for seller verification.

## The Solution (3 Minutes)

### Option 1: Using MySQL Command Line (Recommended)

1. **Open Command Prompt** (Windows Key + R, type `cmd`)

2. **Navigate to MySQL:**
   ```bash
   cd C:\xampp\mysql\bin
   ```

3. **Login to MySQL:**
   ```bash
   mysql -u root -p
   ```
   (Press Enter if no password, or type your password)

4. **Run these commands one by one:**
   ```sql
   USE agi_connect;
   
   ALTER TABLE users ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;
   
   ALTER TABLE users ADD COLUMN id_document VARCHAR(255) NULL AFTER verification_status;
   
   ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;
   
   INSERT INTO users (full_name, email, password_hash, role, verification_status) VALUES ('Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');
   
   SELECT 'Done!' as Status;
   
   QUIT;
   ```

5. **Test it:**
   - Go to: http://localhost/AgriConnect/
   - Login as admin (admin@agriconnect.com / admin123)
   - Try approving a seller again

### Option 2: Using phpMyAdmin (Easier)

1. **Open phpMyAdmin:**
   - Go to: http://localhost/phpmyadmin

2. **Select Database:**
   - Click on `agi_connect` in the left sidebar

3. **Open SQL Tab:**
   - Click the "SQL" tab at the top

4. **Copy and Paste this:**
   ```sql
   ALTER TABLE users ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;
   ALTER TABLE users ADD COLUMN id_document VARCHAR(255) NULL AFTER verification_status;
   ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;
   INSERT INTO users (full_name, email, password_hash, role, verification_status) VALUES ('Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');
   ```

5. **Click "Go" button**

6. **You should see:** "4 rows affected" or similar success message

7. **Test it:**
   - Go to: http://localhost/AgriConnect/
   - Login as admin (admin@agriconnect.com / admin123)
   - Try approving a seller again

### Option 3: Run the Quick Fix Script

1. **Open Command Prompt**

2. **Navigate to MySQL:**
   ```bash
   cd C:\xampp\mysql\bin
   ```

3. **Run:**
   ```bash
   mysql -u root -p < e:\xampp\htdocs\AgriConnect\database\quick_fix.sql
   ```

4. **Done!**

## Verify It Worked

Visit this page to check:
```
http://localhost/AgriConnect/check_database.php
```

You should see all green checkmarks ✅

## Still Not Working?

### Check if columns were added:
```sql
mysql -u root -p
USE agi_connect;
DESCRIBE users;
```

You should see:
- verification_status
- id_document
- role (with 'admin' option)

### Check if admin exists:
```sql
SELECT * FROM users WHERE role = 'admin';
```

You should see one row with email: admin@agriconnect.com

## Common Errors

### "Column already exists"
**This is OK!** It means the column was already added. Continue with the other commands.

### "Duplicate entry for key 'email'"
**This is OK!** It means the admin account already exists.

### "Unknown database 'agi_connect'"
**Fix:** Create the database first:
```sql
CREATE DATABASE agi_connect;
```

## After the Fix

1. **Logout** from admin dashboard
2. **Clear browser cache** (Ctrl + Shift + Delete)
3. **Login again** as admin
4. **Try approving** a seller
5. **Should work now!** ✅

---

**Need Help?** Check TROUBLESHOOTING.md for detailed solutions.
