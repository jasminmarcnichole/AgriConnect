# 🔧 Fix: "Unexpected token '<'" JSON Error

## What This Error Means
The server is returning HTML (which starts with `<`) instead of JSON. This happens when:
1. Database columns are missing
2. PHP has an error before sending JSON
3. There's output before the JSON response

## Step-by-Step Fix

### Step 1: Test Your Database
Open this URL in your browser:
```
http://localhost/AgriConnect/test_database.php
```

**Expected Result:**
```json
{
  "success": true,
  "checks": {
    "verification_status_column": true,
    "id_document_column": true,
    "admin_role_exists": true,
    "admin_user_exists": true
  },
  "message": "Database is ready!"
}
```

**If you see `"success": false`**, continue to Step 2.

### Step 2: Fix Your Database

#### Option A: Using phpMyAdmin (Easiest)

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click on `agi_connect` database (left sidebar)
3. Click "SQL" tab (top menu)
4. Copy and paste this entire code:

```sql
-- Add missing columns
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS id_document VARCHAR(255) NULL AFTER verification_status;

-- Update role to include admin
ALTER TABLE users 
MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;

-- Create admin account (password: admin123)
INSERT IGNORE INTO users (full_name, email, password_hash, role, verification_status) 
VALUES ('Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');
```

5. Click "Go" button
6. You should see success messages

#### Option B: Using MySQL Command Line

1. Open Command Prompt (Windows Key + R, type `cmd`)
2. Navigate to MySQL:
   ```bash
   cd C:\xampp\mysql\bin
   ```
3. Login:
   ```bash
   mysql -u root -p
   ```
4. Run these commands:
   ```sql
   USE agi_connect;
   
   ALTER TABLE users ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;
   
   ALTER TABLE users ADD COLUMN id_document VARCHAR(255) NULL AFTER verification_status;
   
   ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;
   
   INSERT INTO users (full_name, email, password_hash, role, verification_status) VALUES ('Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');
   
   QUIT;
   ```

### Step 3: Verify the Fix

1. Go back to: http://localhost/AgriConnect/test_database.php
2. You should now see `"success": true`
3. All checks should be `true`

### Step 4: Test Admin Approval

1. **Clear your browser cache** (Ctrl + Shift + Delete)
2. Go to: http://localhost/AgriConnect/
3. **Logout** if you're logged in
4. **Login as admin:**
   - Email: admin@agriconnect.com
   - Password: admin123
5. You should see the admin dashboard
6. Try approving a seller
7. **Open browser console** (Press F12, go to Console tab)
8. Click "Approve" button
9. Check the console for any error messages

### Step 5: Check Console Output

When you click "Approve", you should see in the console:
```
Response status: 200
Response text: {"success":true,"message":"Seller approved successfully!"}
```

**If you see HTML instead**, there's a PHP error. Look for error messages in the HTML.

## Common Issues & Solutions

### Issue 1: "Column already exists"
**This is OK!** It means the column was already added. The error is harmless.

### Issue 2: "Duplicate entry for key 'email'"
**This is OK!** It means the admin account already exists.

### Issue 3: Still getting JSON error after database fix

**Solution A: Check for PHP errors**
1. Open: e:\xampp\htdocs\AgriConnect\admin_verify_seller.php
2. Add this at the very top (line 2):
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 0); // Don't display, just log
   ini_set('log_errors', 1);
   ```
3. Check error log: e:\xampp\apache\logs\error.log

**Solution B: Test the endpoint directly**
1. Open Postman or use curl
2. Send POST request to: http://localhost/AgriConnect/admin_verify_seller.php
3. Body (JSON):
   ```json
   {
     "user_id": 1,
     "status": "approved"
   }
   ```
4. Check the response

### Issue 4: "Unauthorized access"
**Solution:** You're not logged in as admin
1. Logout completely
2. Clear browser cookies
3. Login with: admin@agriconnect.com / admin123

### Issue 5: "User not found" or "User is not a seller"
**Solution:** The user ID doesn't exist or isn't a seller
1. Register a new seller account
2. Make sure to upload an ID document
3. Check they appear in the admin dashboard

## Manual Verification

Run these SQL queries to verify everything:

```sql
-- Check columns exist
DESCRIBE users;

-- Should show: verification_status, id_document, and role with 'admin'

-- Check admin exists
SELECT * FROM users WHERE role = 'admin';

-- Should show one row with email: admin@agriconnect.com

-- Check sellers
SELECT id, full_name, email, role, verification_status FROM users WHERE role = 'seller';

-- Should show your test sellers
```

## Still Not Working?

### Enable Full Error Logging

Edit `admin_verify_seller.php` and add at the top:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

Then try approving again and you'll see the actual PHP error.

### Check Apache Error Log

Location: `e:\xampp\apache\logs\error.log`

Look for recent errors related to AgriConnect.

### Test with Simple Script

Create `test_approve.php`:
```php
<?php
session_start();
require 'config.php';

// Fake admin session for testing
$_SESSION['user'] = ['role' => 'admin'];

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("UPDATE users SET verification_status = 'approved' WHERE id = 1");
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Test successful']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
```

Visit: http://localhost/AgriConnect/test_approve.php

## Success Checklist

- [ ] test_database.php shows all green (success: true)
- [ ] Can login as admin
- [ ] Admin dashboard loads
- [ ] Can see sellers in the list
- [ ] Browser console shows no errors
- [ ] Clicking approve shows "Processing..." popup
- [ ] Console shows: Response status: 200
- [ ] Console shows: Response text with JSON
- [ ] Success message appears
- [ ] Page reloads with updated status

---

**If all else fails:** Delete and recreate the database using the full schema.sql file.
