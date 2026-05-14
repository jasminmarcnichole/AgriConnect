# Troubleshooting: "Failed to Update Status" Error

## Problem
When clicking "Approve" or "Reject" in the admin dashboard, you get an error: "Failed to update status"

## Most Common Cause
Your database hasn't been updated with the new columns needed for the verification system.

## Solution Steps

### Step 1: Check Your Database
Visit this URL in your browser:
```
http://localhost/AgriConnect/check_database.php
```

This will show you exactly what's missing from your database.

### Step 2: Choose Your Fix Method

#### Method A: Quick Fix (Recommended if you have existing data)
Run the quick fix SQL script to add missing columns without losing data:

```bash
# Open MySQL command line
mysql -u root -p

# Run the quick fix
source e:/xampp/htdocs/AgriConnect/database/quick_fix.sql
```

Or using phpMyAdmin:
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Select `agi_connect` database
3. Click "SQL" tab
4. Copy and paste contents of `database/quick_fix.sql`
5. Click "Go"

#### Method B: Fresh Install (If you don't mind losing data)
Drop and recreate the database with the new schema:

```bash
mysql -u root -p
DROP DATABASE IF EXISTS agi_connect;
CREATE DATABASE agi_connect;
USE agi_connect;
SOURCE e:/xampp/htdocs/AgriConnect/database/schema.sql;
QUIT;
```

### Step 3: Verify the Fix
1. Visit: http://localhost/AgriConnect/check_database.php
2. You should see all green checkmarks ✅
3. Try approving a seller again

## Manual SQL Commands (If scripts don't work)

If the automated scripts don't work, run these commands manually:

```sql
USE agi_connect;

-- Add verification_status column
ALTER TABLE users 
ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;

-- Add id_document column
ALTER TABLE users 
ADD COLUMN id_document VARCHAR(255) NULL AFTER verification_status;

-- Update role enum to include admin
ALTER TABLE users 
MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;

-- Create admin account (password: admin123)
INSERT INTO users (full_name, email, password_hash, role, verification_status) 
VALUES ('Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');
```

## Other Possible Issues

### Issue 1: Not Logged in as Admin
**Symptom:** Error says "Unauthorized access"
**Solution:** 
- Logout and login again with: admin@agriconnect.com / admin123
- Make sure you're redirected to admin_dashboard.php

### Issue 2: Seller Doesn't Exist
**Symptom:** Error says "User not found"
**Solution:**
- Register a new seller account
- Make sure to upload an ID document
- Check the admin dashboard to see if they appear

### Issue 3: User is Not a Seller
**Symptom:** Error says "User is not a seller"
**Solution:**
- The user you're trying to approve must have role='seller'
- Check in database: `SELECT id, email, role FROM users WHERE id = [USER_ID];`

### Issue 4: JavaScript Error
**Symptom:** Nothing happens when clicking buttons
**Solution:**
1. Press F12 to open browser console
2. Look for red error messages
3. Common fixes:
   - Clear browser cache (Ctrl+Shift+Delete)
   - Hard refresh (Ctrl+F5)
   - Try a different browser

### Issue 5: Database Connection Error
**Symptom:** Error says "Database error: ..."
**Solution:**
- Check config.php has correct credentials
- Make sure MySQL is running in XAMPP
- Verify database name is 'agi_connect'

## Testing the Complete Flow

After fixing the database, test the complete flow:

### 1. Register a Test Seller
```
1. Go to: http://localhost/AgriConnect/
2. Click "Get Started"
3. Fill in:
   - Name: Test Seller
   - Email: testseller@test.com
   - Password: test123
   - Role: Sell My Farm Products
4. Upload any image as ID
5. Submit
6. Should see: "Account pending admin approval"
```

### 2. Login as Admin
```
1. Go to: http://localhost/AgriConnect/
2. Click "Login"
3. Email: admin@agriconnect.com
4. Password: admin123
5. Should redirect to admin_dashboard.php
```

### 3. Approve the Seller
```
1. You should see "Test Seller" in the pending list
2. Click "View" to see their ID document
3. Click "Approve"
4. Confirm in the popup
5. Should see: "Seller approved successfully!"
6. Page refreshes, status changes to "Approved"
```

### 4. Login as Seller
```
1. Logout from admin
2. Login with: testseller@test.com / test123
3. Should now work and redirect to dashboard
```

## Still Having Issues?

### Enable Error Logging
Add this to the top of admin_verify_seller.php:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Check PHP Error Log
Location: `e:\xampp\apache\logs\error.log`

### Check Browser Console
1. Press F12
2. Go to "Console" tab
3. Look for red errors
4. Copy the error message

### Get Detailed Error
Modify admin_verify_seller.php temporarily to see the exact error:
```php
// Add after the try-catch block
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
```

## Quick Checklist

- [ ] Database has verification_status column
- [ ] Database has id_document column
- [ ] Role enum includes 'admin'
- [ ] Admin account exists
- [ ] Logged in as admin
- [ ] Seller account exists with role='seller'
- [ ] Browser cache cleared
- [ ] JavaScript console shows no errors
- [ ] MySQL is running

## Contact Support

If none of these solutions work:
1. Run check_database.php and save the output
2. Check browser console (F12) and save any errors
3. Check PHP error log
4. Provide all error messages for further assistance

---

**Most Common Fix:** Run the quick_fix.sql script - this solves 90% of issues!
