# 🔐 Troubleshooting: Seller Cannot Login (Even After Approval)

## Quick Diagnosis Tool

**Use this tool first to see exactly what's wrong:**
```
http://localhost/AgriConnect/debug_seller_login.php
```

Enter the seller's email and it will show you:
- ✅ What's working
- ❌ What's blocking the login
- 🔧 How to fix it

## Common Causes & Solutions

### Cause 1: Verification Status is Still "Pending"

**Symptoms:**
- Seller was "approved" in admin dashboard
- But still can't login
- Error: "Your seller account is pending admin verification"

**Why this happens:**
- The database update didn't work
- JavaScript error prevented the update
- Page was refreshed before update completed

**Solution A: Check actual status in database**
```sql
SELECT id, full_name, email, verification_status 
FROM users 
WHERE email = 'seller@email.com';
```

If it shows 'pending', manually update:
```sql
UPDATE users 
SET verification_status = 'approved' 
WHERE email = 'seller@email.com';
```

**Solution B: Use the debug tool**
1. Go to: http://localhost/AgriConnect/debug_seller_login.php
2. Enter seller email
3. Click "Approve This Seller Now" button

### Cause 2: verification_status Column Doesn't Exist

**Symptoms:**
- Database hasn't been updated
- Login fails with no clear error
- Admin dashboard doesn't work

**Solution:**
Run this SQL in phpMyAdmin:
```sql
ALTER TABLE users 
ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' 
AFTER role;
```

Or use the quick fix:
```
http://localhost/AgriConnect/check_database.php
```

### Cause 3: Wrong Password

**Symptoms:**
- Error: "Invalid email or password"
- Credentials are correct but still fails

**Solution:**
Reset the password:
```sql
-- New password will be: test123
UPDATE users 
SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'seller@email.com';
```

### Cause 4: User Role is Not "seller"

**Symptoms:**
- Login works but redirects to wrong page
- Can't access seller features

**Solution:**
Check and fix role:
```sql
SELECT id, email, role FROM users WHERE email = 'seller@email.com';

-- If role is wrong, fix it:
UPDATE users SET role = 'seller' WHERE email = 'seller@email.com';
```

### Cause 5: Session Issues

**Symptoms:**
- Login seems to work but immediately logs out
- Redirects back to login page

**Solution:**
1. Clear browser cookies (Ctrl + Shift + Delete)
2. Close all browser tabs
3. Restart browser
4. Try logging in again

### Cause 6: Browser Cache

**Symptoms:**
- Old error messages keep appearing
- Changes don't take effect

**Solution:**
1. Hard refresh: Ctrl + F5
2. Clear cache: Ctrl + Shift + Delete
3. Try incognito/private mode

## Step-by-Step Verification

### Step 1: Verify Database Structure
```
http://localhost/AgriConnect/test_database.php
```
Should show: `"success": true`

### Step 2: Check Seller Status
```
http://localhost/AgriConnect/debug_seller_login.php
```
Enter seller email, should show: "✅ This seller CAN login!"

### Step 3: Test Login
1. Go to: http://localhost/AgriConnect/
2. Click "Login"
3. Enter seller credentials
4. Should redirect to dashboard

## Manual Database Check

Run these queries to verify everything:

```sql
-- 1. Check if seller exists
SELECT * FROM users WHERE email = 'seller@email.com';

-- 2. Check verification status
SELECT id, full_name, email, role, verification_status 
FROM users 
WHERE email = 'seller@email.com';

-- Expected result:
-- role: seller
-- verification_status: approved

-- 3. If status is wrong, fix it:
UPDATE users 
SET verification_status = 'approved' 
WHERE email = 'seller@email.com' AND role = 'seller';

-- 4. Verify the fix:
SELECT verification_status FROM users WHERE email = 'seller@email.com';
-- Should show: approved
```

## Quick Fix Commands

### Fix 1: Approve All Pending Sellers
```sql
UPDATE users 
SET verification_status = 'approved' 
WHERE role = 'seller' AND verification_status = 'pending';
```

### Fix 2: Approve Specific Seller
```sql
UPDATE users 
SET verification_status = 'approved' 
WHERE email = 'seller@email.com';
```

### Fix 3: Reset Seller Password (to: test123)
```sql
UPDATE users 
SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'seller@email.com';
```

### Fix 4: Add Missing Column
```sql
ALTER TABLE users 
ADD COLUMN verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' 
AFTER role;
```

## Testing Checklist

- [ ] Database has verification_status column
- [ ] Seller exists in database
- [ ] Seller role is 'seller'
- [ ] Verification status is 'approved'
- [ ] Password is correct
- [ ] Browser cache cleared
- [ ] Cookies enabled
- [ ] No JavaScript errors (F12 console)
- [ ] Session working properly

## Create Test Seller

If you need a fresh test seller:

```sql
-- Create test seller (email: test@seller.com, password: test123)
INSERT INTO users (full_name, email, password_hash, role, verification_status, id_document) 
VALUES (
    'Test Seller', 
    'test@seller.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'seller', 
    'approved',
    'test_id.jpg'
);
```

Then login with:
- Email: test@seller.com
- Password: test123

## Still Can't Login?

### Enable Debug Mode

Edit `login.php` and add at the top:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

This will show any PHP errors.

### Check What Error You're Getting

Look at the error message carefully:
- "Invalid email or password" = Wrong credentials or user doesn't exist
- "Pending admin verification" = Status is still 'pending'
- "Account has been rejected" = Status is 'rejected'
- No error but redirects back = Session issue

### Use Debug Tool

The debug tool will tell you EXACTLY what's wrong:
```
http://localhost/AgriConnect/debug_seller_login.php
```

### Contact Support

If nothing works, provide:
1. Screenshot from debug_seller_login.php
2. Result from test_database.php
3. The exact error message you see
4. Browser console errors (F12)

---

**Most Common Fix:** The seller's verification_status is still 'pending' in the database even though you clicked approve. Use the debug tool to fix it instantly!
