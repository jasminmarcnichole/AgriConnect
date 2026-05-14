# Seller Verification System - Setup Guide

## Overview
The Agri-Connect platform now includes a comprehensive seller verification system with admin approval and email notifications.

## New Features

### 1. Seller ID Document Upload
- Sellers must upload a valid ID document (JPG, PNG, or PDF) during registration
- Maximum file size: 5MB
- Document must show name and signature
- Account status set to "pending" until admin approval

### 2. Admin Dashboard
- Dedicated admin panel for managing seller verifications
- View all seller registrations with their status
- Review uploaded ID documents
- Approve or reject seller accounts
- Statistics dashboard showing pending, approved, and rejected sellers

### 3. Email Notifications
- Automatic email sent to sellers when their account is approved
- Professional HTML email template
- Includes direct link to dashboard

## Setup Instructions

### Step 1: Update Database
Run the updated schema to add new fields and admin account:

```bash
mysql -u root -p
DROP DATABASE IF EXISTS agi_connect;
source database/schema.sql;
```

This will:
- Add `verification_status` field to users table
- Add `id_document` field to users table
- Add 'admin' role option
- Create default admin account

### Step 2: Create Upload Directory
The system needs a directory to store ID documents:

```bash
mkdir uploads
mkdir uploads/id_documents
chmod 755 uploads
chmod 755 uploads/id_documents
```

For Windows (XAMPP):
- Create folder: `AgriConnect/uploads/id_documents/`
- Ensure write permissions are enabled

### Step 3: Configure Email (Optional but Recommended)

#### Option A: Using PHP mail() function (Basic)
- Already configured in `email_config.php`
- Works with local mail server
- May not work on all hosting environments

#### Option B: Using Gmail SMTP (Recommended)
1. Install PHPMailer:
   ```bash
   composer require phpmailer/phpmailer
   ```

2. Enable 2-Step Verification in your Google Account:
   - Go to: https://myaccount.google.com/security

3. Generate App Password:
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and your device
   - Copy the generated password

4. Update `email_config.php`:
   ```php
   define('SMTP_USERNAME', 'your-email@gmail.com');
   define('SMTP_PASSWORD', 'your-16-char-app-password');
   ```

5. Uncomment the PHPMailer function in `email_config.php`

### Step 4: Admin Login Credentials
Default admin account:
- **Email:** admin@agriconnect.com
- **Password:** admin123

**IMPORTANT:** Change the admin password after first login!

## How It Works

### For Sellers (Registration Flow)
1. User selects "Sell My Farm Products" during signup
2. ID document upload field appears automatically
3. User uploads valid ID (JPG/PNG/PDF)
4. Account created with "pending" status
5. User receives message: "Your seller account is pending admin approval"
6. User cannot login until approved

### For Admin (Verification Flow)
1. Admin logs in at: `http://localhost/AgriConnect/`
2. Automatically redirected to admin dashboard
3. Views list of all sellers with their status
4. Clicks "View" to review ID document
5. Clicks "Approve" or "Reject"
6. System updates status and sends email notification
7. Seller can now login and access full features

### For Buyers
- No verification required
- Immediate access after registration
- Can browse and contact verified sellers only

## File Structure

```
AgriConnect/
├── admin_dashboard.php          # Admin panel for seller verification
├── admin_verify_seller.php      # Backend handler for approval/rejection
├── email_config.php             # Email configuration and functions
├── signup.php                   # Updated with ID upload handling
├── login.php                    # Updated with verification checks
├── index.php                    # Updated signup modal with ID field
├── uploads/
│   └── id_documents/           # Stores uploaded ID documents
└── database/
    └── schema.sql              # Updated database schema
```

## Testing the System

### Test Seller Registration
1. Go to homepage
2. Click "Get Started"
3. Fill in details
4. Select "Sell My Farm Products"
5. Upload a test ID document
6. Submit registration
7. Try to login (should be blocked with pending message)

### Test Admin Approval
1. Login as admin (admin@agriconnect.com / admin123)
2. View pending sellers
3. Click "View" to see ID document
4. Click "Approve"
5. Check email (if configured)
6. Logout and login as seller (should work now)

## Security Features

- File type validation (only JPG, PNG, PDF)
- File size limit (5MB maximum)
- Unique filename generation to prevent overwrites
- Secure file storage outside web root (recommended for production)
- Role-based access control for admin functions
- Verification status checks on login

## Production Deployment Checklist

- [ ] Change admin password
- [ ] Move uploads folder outside web root
- [ ] Configure proper SMTP email settings
- [ ] Enable HTTPS
- [ ] Set proper file permissions (644 for files, 755 for directories)
- [ ] Configure backup for ID documents
- [ ] Set up email logging
- [ ] Add rate limiting for verification requests
- [ ] Implement admin activity logging

## Troubleshooting

### Issue: ID upload fails
- Check folder permissions: `chmod 755 uploads/id_documents`
- Verify PHP upload settings in php.ini:
  ```ini
  upload_max_filesize = 5M
  post_max_size = 6M
  ```

### Issue: Email not sending
- Check PHP mail configuration
- Verify SMTP credentials in email_config.php
- Check spam folder
- Enable error logging: `error_log()` in admin_verify_seller.php

### Issue: Admin can't access dashboard
- Verify admin account exists in database
- Check role is set to 'admin'
- Clear browser cache and cookies

### Issue: Seller can't login after approval
- Check verification_status in database is 'approved'
- Clear session: logout and login again
- Verify no typos in email/password

## Support

For issues or questions:
1. Check this documentation
2. Review error logs
3. Contact development team

---

**Note:** This system is designed for development/testing. For production use, consider additional security measures and professional email service providers.
