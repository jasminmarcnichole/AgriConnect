# 🚀 Deployment Checklist - Seller Verification System

## ✅ Pre-Deployment Checklist

### 1. Database Setup
- [ ] Backup existing database (if any)
- [ ] Run new schema.sql file
- [ ] Verify admin account created
- [ ] Test database connection

**Commands:**
```bash
# Backup (if needed)
mysqldump -u root -p agi_connect > backup_$(date +%Y%m%d).sql

# Import new schema
mysql -u root -p
DROP DATABASE IF EXISTS agi_connect;
CREATE DATABASE agi_connect;
USE agi_connect;
SOURCE e:/xampp/htdocs/AgriConnect/database/schema.sql;
QUIT;
```

### 2. File System
- [x] uploads/ directory created
- [x] uploads/id_documents/ directory created
- [ ] Verify write permissions on uploads/
- [ ] Test file upload functionality

**Windows (XAMPP):**
- Right-click uploads folder → Properties → Security
- Ensure "Users" have Write permissions

### 3. Configuration Files
- [ ] config.php - Database credentials correct
- [ ] email_config.php - SMTP settings configured (optional)
- [ ] .gitignore - Protecting sensitive files

### 4. Admin Account
- [ ] Admin login works (admin@agriconnect.com / admin123)
- [ ] Admin redirects to admin_dashboard.php
- [ ] **IMPORTANT:** Change admin password after first login!

### 5. Testing

#### Test Buyer Registration
- [ ] Register as buyer
- [ ] No ID upload required
- [ ] Can login immediately
- [ ] Access dashboard

#### Test Seller Registration
- [ ] Register as seller
- [ ] ID upload field appears
- [ ] Upload test document
- [ ] See "pending approval" message
- [ ] Cannot login (blocked)

#### Test Admin Approval
- [ ] Login as admin
- [ ] See pending seller in list
- [ ] View ID document
- [ ] Approve seller
- [ ] Check email sent (if configured)

#### Test Approved Seller
- [ ] Login as approved seller
- [ ] Access granted
- [ ] Can access dashboard
- [ ] Can add products

## 📧 Email Configuration (Optional but Recommended)

### Option A: Basic PHP mail() - Already Configured ✅
- Works with local mail server
- May not work on all systems
- No additional setup needed

### Option B: Gmail SMTP - Recommended for Production

#### Step 1: Enable 2-Step Verification
- [ ] Go to: https://myaccount.google.com/security
- [ ] Enable 2-Step Verification

#### Step 2: Generate App Password
- [ ] Go to: https://myaccount.google.com/apppasswords
- [ ] Select "Mail" and your device
- [ ] Copy 16-character password

#### Step 3: Update Configuration
- [ ] Edit email_config.php
- [ ] Update SMTP_USERNAME with your Gmail
- [ ] Update SMTP_PASSWORD with app password
- [ ] Save file

#### Step 4: Install PHPMailer (Optional)
```bash
cd e:\xampp\htdocs\AgriConnect
composer require phpmailer/phpmailer
```
- [ ] Uncomment PHPMailer function in email_config.php

#### Step 5: Test Email
- [ ] Approve a test seller
- [ ] Check email inbox
- [ ] Check spam folder
- [ ] Verify email formatting

## 🔒 Security Checklist

### File Security
- [x] .gitignore created
- [x] Uploads folder protected
- [ ] File permissions set correctly
- [ ] No sensitive data in version control

### Database Security
- [x] PDO prepared statements used
- [x] Password hashing implemented
- [ ] Database user has minimal privileges
- [ ] Database password is strong

### Application Security
- [x] Role-based access control
- [x] Verification status checks
- [x] File type validation
- [x] File size limits
- [ ] HTTPS enabled (production)
- [ ] Session security configured

### Admin Security
- [ ] Default admin password changed
- [ ] Admin email changed to real email
- [ ] Admin access logged (future enhancement)
- [ ] Regular security audits scheduled

## 📱 User Acceptance Testing

### Buyer Flow
- [ ] Can register without ID
- [ ] Can login immediately
- [ ] Can browse products
- [ ] Can contact sellers
- [ ] Can view conversations

### Seller Flow
- [ ] Must upload ID to register
- [ ] Cannot login while pending
- [ ] Receives email when approved
- [ ] Can login after approval
- [ ] Can add products
- [ ] Can manage listings

### Admin Flow
- [ ] Can login to admin panel
- [ ] Can view all sellers
- [ ] Can view ID documents
- [ ] Can approve sellers
- [ ] Can reject sellers
- [ ] Can reset status
- [ ] Sees accurate statistics

## 🐛 Troubleshooting Checklist

### Database Issues
- [ ] MySQL service running
- [ ] Database exists
- [ ] Tables created correctly
- [ ] Admin account exists
- [ ] Connection credentials correct

### File Upload Issues
- [ ] uploads/ directory exists
- [ ] Directory has write permissions
- [ ] PHP upload_max_filesize = 5M
- [ ] PHP post_max_size = 6M
- [ ] File types allowed in php.ini

### Email Issues
- [ ] SMTP credentials correct
- [ ] 2-Step verification enabled
- [ ] App password generated
- [ ] Firewall allows SMTP
- [ ] Check spam folder

### Login Issues
- [ ] Session directory writable
- [ ] Cookies enabled in browser
- [ ] Correct email/password
- [ ] Verification status correct
- [ ] No typos in credentials

## 📊 Post-Deployment Monitoring

### Week 1
- [ ] Monitor seller registrations
- [ ] Check ID document quality
- [ ] Verify email delivery
- [ ] Review admin activity
- [ ] Collect user feedback

### Week 2-4
- [ ] Analyze approval rates
- [ ] Review rejection reasons
- [ ] Optimize email templates
- [ ] Improve admin workflow
- [ ] Document common issues

### Ongoing
- [ ] Regular database backups
- [ ] Monitor disk space (uploads/)
- [ ] Review security logs
- [ ] Update documentation
- [ ] Plan enhancements

## 🎯 Success Metrics

### Technical Metrics
- [ ] 100% of sellers upload ID documents
- [ ] 0% file upload errors
- [ ] < 24 hour approval time
- [ ] 100% email delivery rate
- [ ] 0 security incidents

### User Metrics
- [ ] Seller satisfaction with process
- [ ] Admin efficiency in reviews
- [ ] Buyer trust in verified sellers
- [ ] Reduction in fraudulent accounts
- [ ] Positive user feedback

## 📞 Support Resources

### Documentation
- [ ] README.md - Main documentation
- [ ] QUICKSTART.md - Quick setup guide
- [ ] VERIFICATION_SETUP.md - Detailed setup
- [ ] WORKFLOW_DIAGRAM.md - Visual workflow
- [ ] IMPLEMENTATION_SUMMARY.md - Technical details

### Support Contacts
- [ ] Technical support email set up
- [ ] Admin contact information updated
- [ ] User help documentation created
- [ ] FAQ section prepared

## 🚀 Go-Live Checklist

### Final Checks Before Launch
- [ ] All tests passed
- [ ] Database backed up
- [ ] Admin password changed
- [ ] Email configured and tested
- [ ] Documentation reviewed
- [ ] Support team briefed
- [ ] Monitoring tools ready
- [ ] Rollback plan prepared

### Launch Day
- [ ] Deploy to production
- [ ] Verify all features working
- [ ] Monitor error logs
- [ ] Test critical paths
- [ ] Announce to users
- [ ] Monitor user feedback
- [ ] Be ready for support requests

### Post-Launch (First 24 Hours)
- [ ] Monitor system performance
- [ ] Check error logs
- [ ] Verify email delivery
- [ ] Review user registrations
- [ ] Address any issues immediately
- [ ] Collect feedback
- [ ] Document lessons learned

## ✅ Sign-Off

### Development Team
- [ ] Code reviewed
- [ ] Tests completed
- [ ] Documentation updated
- [ ] Deployment guide created

### Admin Team
- [ ] Training completed
- [ ] Admin panel tested
- [ ] Approval process understood
- [ ] Support procedures ready

### Management
- [ ] Requirements met
- [ ] Budget approved
- [ ] Timeline acceptable
- [ ] Go-live authorized

---

## 🎉 Ready to Launch!

Once all items are checked, your seller verification system is ready for production!

**Current Status:** Development Complete ✅
**Next Step:** Follow this checklist for deployment

**Estimated Setup Time:** 15-30 minutes
**Estimated Testing Time:** 30-60 minutes
**Total Time to Production:** 1-2 hours

---

**Good luck with your deployment! 🚀**
