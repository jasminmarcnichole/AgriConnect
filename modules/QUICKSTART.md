# Quick Start Guide - Seller Verification System

## 🚀 Get Started in 3 Steps

### Step 1: Update Your Database (REQUIRED)
Open your MySQL and run:
```sql
DROP DATABASE IF EXISTS agi_connect;
CREATE DATABASE agi_connect;
USE agi_connect;
SOURCE e:/xampp/htdocs/AgriConnect/database/schema.sql;
```

Or using command line:
```bash
cd e:\xampp\htdocs\AgriConnect
mysql -u root -p < database/schema.sql
```

### Step 2: Test the System

#### A. Test Seller Registration
1. Go to: http://localhost/AgriConnect/
2. Click "Get Started"
3. Fill in details:
   - Name: Test Seller
   - Email: seller@test.com
   - Password: test123
   - Role: **Sell My Farm Products** (ID upload field will appear)
4. Upload any image/PDF as ID document
5. Click "Create My Account"
6. You'll see: "Account created! Your seller account is pending admin approval"

#### B. Login as Admin
1. Go to: http://localhost/AgriConnect/
2. Click "Login"
3. Use credentials:
   - Email: **admin@agriconnect.com**
   - Password: **admin123**
4. You'll be redirected to Admin Dashboard automatically

#### C. Approve the Seller
1. In Admin Dashboard, you'll see the pending seller
2. Click "View" to see their ID document
3. Click "Approve" button
4. Confirmation dialog appears - click "Yes, proceed!"
5. Success message: "Seller approved successfully!"
6. Email notification sent to seller (if email configured)

#### D. Login as Approved Seller
1. Logout from admin
2. Login with seller credentials (seller@test.com / test123)
3. Now you can access the full dashboard!

### Step 3: Configure Email (Optional)

For Gmail notifications:
1. Edit `email_config.php`
2. Update these lines:
   ```php
   define('SMTP_USERNAME', 'your-email@gmail.com');
   define('SMTP_PASSWORD', 'your-app-password');
   ```
3. Get App Password from: https://myaccount.google.com/apppasswords

## 📋 Default Accounts

### Admin Account
- **Email:** admin@agriconnect.com
- **Password:** admin123
- **Access:** Admin Dashboard for seller verification

### Test Buyer (Create manually)
- Register as "Buy Agricultural Products"
- No verification needed
- Immediate access

### Test Seller (Create manually)
- Register as "Sell My Farm Products"
- Upload ID document
- Wait for admin approval

## 🎯 Key Features

### For Sellers
✅ Must upload valid ID during registration
✅ Account pending until admin approves
✅ Email notification when approved
✅ Can list products after approval

### For Admin
✅ View all seller registrations
✅ Review uploaded ID documents
✅ Approve/Reject sellers with one click
✅ Dashboard with statistics
✅ Automatic email notifications

### For Buyers
✅ No verification required
✅ Immediate access
✅ Browse verified sellers only

## 🔧 File Locations

- **Admin Dashboard:** `admin_dashboard.php`
- **Signup Handler:** `signup.php`
- **Login Handler:** `login.php`
- **Email Config:** `email_config.php`
- **ID Documents:** `uploads/id_documents/`

## ⚠️ Important Notes

1. **Change Admin Password:** After first login, change the default password!
2. **File Permissions:** Ensure `uploads/id_documents/` is writable
3. **Email Testing:** Email may go to spam folder initially
4. **Production:** Follow security checklist in VERIFICATION_SETUP.md

## 🐛 Common Issues

**Can't upload ID:**
- Check folder exists: `uploads/id_documents/`
- Check permissions (Windows: right-click > Properties > Security)

**Email not sending:**
- Normal! PHP mail() may not work locally
- Configure Gmail SMTP for real emails
- Check spam folder

**Admin can't login:**
- Verify database was updated with new schema
- Check admin account exists: `SELECT * FROM users WHERE role='admin';`

**Seller can't login after approval:**
- Check status: `SELECT verification_status FROM users WHERE email='seller@test.com';`
- Should be 'approved', not 'pending'

## 📞 Need Help?

1. Check `VERIFICATION_SETUP.md` for detailed documentation
2. Review error messages in browser console
3. Check PHP error logs in XAMPP

---

**Ready to go!** Start with Step 1 above and you'll be up and running in minutes! 🎉
