# 🆘 COMPLETE TROUBLESHOOTING GUIDE

## Your Issue: "User not found with this email"

This means the email you're entering doesn't exist in the database, OR you're checking the wrong database.

## 🔍 Step-by-Step Diagnosis

### Step 1: View ALL Users in Your Database
**Go to this page:**
```
http://localhost/AgriConnect/list_all_users.php
```

This will show you:
- ✅ Every single user in your database
- 📧 Their exact email addresses
- 👤 Their roles (buyer/seller/admin)
- ✔️ Their verification status
- 🔘 Copy email buttons
- 🧪 Test login buttons

**What to look for:**
- Is your seller listed there?
- What is the EXACT email shown? (copy it exactly)
- What is their verification status?

### Step 2: Test Login with Exact Email
**Go to this page:**
```
http://localhost/AgriConnect/test_login.php
```

1. Copy the email EXACTLY from list_all_users.php
2. Paste it into test_login.php
3. Enter the password (or leave blank to just check user)
4. Click "Test Login"

This will show you:
- ✅ If user exists
- ✅ If password is correct
- ✅ If verification status allows login
- ✅ Exact reason if login fails

### Step 3: Check Which Database You're Using

**Possible Issue:** You might have multiple databases or the wrong database name.

**Check your config.php:**
```php
// Open: e:\xampp\htdocs\AgriConnect\config.php
// Look for this line:
$db = 'agi_connect';  // <-- This is your database name
```

**Verify in phpMyAdmin:**
1. Go to: http://localhost/phpmyadmin
2. Look at the left sidebar
3. Do you see `agi_connect` database?
4. Click on it
5. Click on `users` table
6. Click "Browse"
7. Do you see your seller there?

## 🎯 Common Scenarios & Solutions

### Scenario 1: Email Has Extra Spaces
**Problem:** Email is "seller@test.com " (with space at end)
**Solution:** 
```sql
-- Find emails with spaces
SELECT id, email, CONCAT('[', email, ']') as email_with_brackets FROM users;

-- Fix it
UPDATE users SET email = TRIM(email);
```

### Scenario 2: Email Has Different Case
**Problem:** Registered as "Seller@Test.com" but logging in with "seller@test.com"
**Solution:** MySQL is case-insensitive by default, but check:
```sql
SELECT * FROM users WHERE LOWER(email) = LOWER('seller@test.com');
```

### Scenario 3: User is in Different Database
**Problem:** You have multiple databases (agi_connect, agriconnect, agri_connect)
**Solution:**
```sql
-- Check all databases
SHOW DATABASES;

-- Check each one
USE agi_connect;
SELECT COUNT(*) FROM users;

USE agriconnect;
SELECT COUNT(*) FROM users;
```

### Scenario 4: User Was Deleted
**Problem:** User was registered but got deleted somehow
**Solution:** Register again or restore from backup

### Scenario 5: Wrong config.php
**Problem:** Multiple config.php files or wrong database credentials
**Solution:** 
```php
// Add this to top of list_all_users.php to see which DB you're connected to
echo "Connected to: " . $pdo->query("SELECT DATABASE()")->fetchColumn();
```

## 🔧 Quick Fixes

### Fix 1: Create a Test Seller Manually
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

Then try logging in with:
- Email: test@seller.com
- Password: test123

### Fix 2: List All Emails
```sql
-- See all emails in database
SELECT id, full_name, email, role, verification_status FROM users ORDER BY id;
```

### Fix 3: Search for Partial Email
```sql
-- If you remember part of the email
SELECT * FROM users WHERE email LIKE '%seller%';
SELECT * FROM users WHERE email LIKE '%test%';
```

## 📋 Diagnostic Checklist

Run through this checklist:

- [ ] Opened list_all_users.php
- [ ] Can see users in the list
- [ ] Found my seller in the list
- [ ] Copied the EXACT email (with copy button)
- [ ] Tested login with that exact email
- [ ] Checked verification status is 'approved'
- [ ] Verified I'm using correct password
- [ ] Checked config.php has correct database name
- [ ] Verified in phpMyAdmin the user exists
- [ ] No extra spaces in email
- [ ] Email case matches

## 🎬 Video-Style Step-by-Step

**Do this RIGHT NOW:**

1. **Open:** http://localhost/AgriConnect/list_all_users.php
   
2. **Look at the table** - Do you see ANY users?
   - If NO users: Database is empty, register a new seller
   - If YES: Continue to step 3

3. **Find your seller** in the list
   - Look for their name or email
   - Check the "Verification Status" column
   - Is it "APPROVED"? If not, click "Approve" button

4. **Click "Copy Email"** button next to your seller
   - This copies the EXACT email

5. **Click "Test Login"** button next to your seller
   - This opens a test page
   - Paste the email (Ctrl+V)
   - Enter password
   - Click "Test Login"
   - Read the results

6. **If test says "LOGIN SHOULD WORK":**
   - Go to: http://localhost/AgriConnect/
   - Click "Login"
   - Paste the EXACT email
   - Enter password
   - Should work now!

7. **If test says "LOGIN WILL FAIL":**
   - Read the reasons listed
   - Follow the fix suggestions
   - Try again

## 🆘 Still Not Working?

### Last Resort: Show Me Your Data

Run this and send me the output:

```sql
-- Show all users
SELECT id, full_name, email, role, verification_status, created_at 
FROM users 
ORDER BY id;

-- Show database name
SELECT DATABASE();

-- Show table structure
DESCRIBE users;
```

### Nuclear Option: Fresh Start

If nothing works, start fresh:

```sql
-- Backup first!
-- Then drop and recreate
DROP DATABASE IF EXISTS agi_connect;
CREATE DATABASE agi_connect;
USE agi_connect;
SOURCE e:/xampp/htdocs/AgriConnect/database/schema.sql;
```

Then register a new seller.

## 📞 What to Tell Me

If you need more help, tell me:

1. **Output from list_all_users.php:**
   - How many users do you see?
   - What are their emails?
   - What are their roles?
   - What are their verification statuses?

2. **Output from test_login.php:**
   - What does it say when you test?
   - Does it find the user?
   - What's the final verdict?

3. **Your config.php database name:**
   - What is the value of `$db`?

4. **Screenshot of phpMyAdmin:**
   - Show the users table with data

---

**Start with list_all_users.php - that's the key to solving this!**
