# Seller Verification System - Visual Workflow

## 📊 Complete System Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    AGRI-CONNECT VERIFICATION SYSTEM                  │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                         USER REGISTRATION                            │
└─────────────────────────────────────────────────────────────────────┘

    User visits homepage (index.php)
            ↓
    Clicks "Get Started" button
            ↓
    ┌───────────────────────────────┐
    │   Signup Modal Opens          │
    │   - Full Name                 │
    │   - Email                     │
    │   - Password                  │
    │   - Role Selection            │
    └───────────────────────────────┘
            ↓
    ┌─────────────────┬─────────────────┐
    │                 │                 │
    ▼                 ▼                 ▼
┌─────────┐    ┌──────────┐    ┌──────────┐
│  BUYER  │    │  SELLER  │    │  ADMIN   │
└─────────┘    └──────────┘    └──────────┘
    │               │               │
    │               │               │
    │          ┌────▼────┐          │
    │          │ ID Upload│          │
    │          │ Required │          │
    │          └────┬────┘          │
    │               │               │
    ▼               ▼               ▼
┌─────────┐    ┌──────────┐    ┌──────────┐
│Approved │    │ Pending  │    │ Approved │
│Status   │    │ Status   │    │ Status   │
└────┬────┘    └────┬─────┘    └────┬─────┘
     │              │               │
     │              │               │
     ▼              ▼               ▼
┌─────────┐    ┌──────────┐    ┌──────────┐
│Can Login│    │Can't Login│   │Can Login │
│Immediate│    │Wait Admin│    │Immediate │
└─────────┘    └──────────┘    └──────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                      SELLER VERIFICATION FLOW                        │
└─────────────────────────────────────────────────────────────────────┘

    Seller registers with ID document
            ↓
    ┌───────────────────────────────┐
    │  signup.php processes:        │
    │  1. Validates file type       │
    │  2. Checks file size          │
    │  3. Generates unique filename │
    │  4. Saves to uploads/         │
    │  5. Sets status = 'pending'   │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Database Record Created:     │
    │  - full_name: "John Farmer"   │
    │  - email: "john@farm.com"     │
    │  - role: "seller"             │
    │  - verification_status:       │
    │    "pending"                  │
    │  - id_document:               │
    │    "id_abc123.jpg"            │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  User sees message:           │
    │  "Account created! Your       │
    │   seller account is pending   │
    │   admin approval."            │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Seller tries to login        │
    │  ❌ BLOCKED                   │
    │  "Your account is pending     │
    │   verification"               │
    └───────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                      ADMIN APPROVAL FLOW                             │
└─────────────────────────────────────────────────────────────────────┘

    Admin logs in
            ↓
    ┌───────────────────────────────┐
    │  login.php detects admin role │
    │  Redirects to:                │
    │  admin_dashboard.php          │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Admin Dashboard Shows:       │
    │  ┌─────────────────────────┐  │
    │  │ Statistics Cards        │  │
    │  │ - Pending: 5            │  │
    │  │ - Approved: 120         │  │
    │  │ - Rejected: 3           │  │
    │  └─────────────────────────┘  │
    │  ┌─────────────────────────┐  │
    │  │ Seller List Table       │  │
    │  │ Name | Email | Status   │  │
    │  │ [View] [Approve][Reject]│  │
    │  └─────────────────────────┘  │
    └───────────────────────────────┘
            ↓
    Admin clicks "View" button
            ↓
    ┌───────────────────────────────┐
    │  ID Document opens in new tab │
    │  Admin reviews:               │
    │  - Name matches?              │
    │  - Signature present?         │
    │  - Document valid?            │
    └───────────────────────────────┘
            ↓
    ┌─────────────────┬─────────────────┐
    │                 │                 │
    ▼                 ▼                 ▼
┌─────────┐    ┌──────────┐    ┌──────────┐
│ APPROVE │    │  REJECT  │    │  RESET   │
└────┬────┘    └────┬─────┘    └────┬─────┘
     │              │               │
     │              │               │
     ▼              ▼               ▼
┌─────────┐    ┌──────────┐    ┌──────────┐
│SweetAlert│   │SweetAlert│    │SweetAlert│
│Confirm  │    │Confirm   │    │Confirm   │
└────┬────┘    └────┬─────┘    └────┬─────┘
     │              │               │
     │              │               │
     ▼              ▼               ▼
┌─────────────────────────────────────────┐
│  AJAX call to admin_verify_seller.php   │
│  POST: {user_id: 123, status: "..."}    │
└─────────────────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Database Updated:            │
    │  UPDATE users                 │
    │  SET verification_status =    │
    │      'approved'               │
    │  WHERE id = 123               │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Email Notification Sent:     │
    │  To: seller@email.com         │
    │  Subject: "Account Verified!" │
    │  Body: HTML template          │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Success Response:            │
    │  {success: true,              │
    │   message: "Seller approved"} │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Page Refreshes               │
    │  Status badge updated         │
    │  Statistics updated           │
    └───────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                      SELLER LOGIN AFTER APPROVAL                     │
└─────────────────────────────────────────────────────────────────────┘

    Seller receives email
            ↓
    ┌───────────────────────────────┐
    │  Email Content:               │
    │  🎉 Congratulations!          │
    │  Your account is verified!    │
    │  [Go to Dashboard] button     │
    └───────────────────────────────┘
            ↓
    Seller clicks link or logs in manually
            ↓
    ┌───────────────────────────────┐
    │  login.php checks:            │
    │  1. Email/password correct?   │
    │  2. Role = 'seller'?          │
    │  3. Status = 'approved'? ✅   │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  ✅ LOGIN SUCCESSFUL          │
    │  Session created              │
    │  Redirect to dashboard.php    │
    └───────────────────────────────┘
            ↓
    ┌───────────────────────────────┐
    │  Seller Dashboard Access:     │
    │  - Add Products               │
    │  - Manage Listings            │
    │  - Chat with Buyers           │
    │  - View Analytics             │
    └───────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                      FILE STRUCTURE OVERVIEW                         │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│  Frontend (User-Facing)                                              │
├─────────────────────────────────────────────────────────────────────┤
│  index.php                                                           │
│  └─ Signup Modal (with ID upload field)                             │
│     └─ JavaScript: Show/hide ID field based on role                 │
│                                                                      │
│  login.php                                                           │
│  └─ Verification status checks                                      │
│     └─ Redirect logic (admin vs user)                               │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│  Backend (Processing)                                                │
├─────────────────────────────────────────────────────────────────────┤
│  signup.php                                                          │
│  └─ File upload handling                                            │
│     └─ Validation (type, size)                                      │
│        └─ Save to uploads/id_documents/                             │
│           └─ Set verification_status                                │
│                                                                      │
│  admin_verify_seller.php                                            │
│  └─ AJAX endpoint                                                   │
│     └─ Update database status                                       │
│        └─ Send email notification                                   │
│           └─ Return JSON response                                   │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│  Admin Panel                                                         │
├─────────────────────────────────────────────────────────────────────┤
│  admin_dashboard.php                                                 │
│  └─ Statistics cards                                                │
│  └─ Seller list table                                               │
│  └─ Action buttons (View/Approve/Reject)                            │
│  └─ JavaScript: AJAX calls + SweetAlert                             │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│  Configuration                                                       │
├─────────────────────────────────────────────────────────────────────┤
│  config.php          → Database connection                           │
│  email_config.php    → SMTP settings + email functions              │
│  .gitignore          → Protect sensitive files                      │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│  Database                                                            │
├─────────────────────────────────────────────────────────────────────┤
│  users table:                                                        │
│  ├─ id (INT)                                                         │
│  ├─ full_name (VARCHAR)                                             │
│  ├─ email (VARCHAR)                                                 │
│  ├─ password_hash (VARCHAR)                                         │
│  ├─ role (ENUM: buyer, seller, admin) ← NEW                        │
│  ├─ verification_status (ENUM: pending, approved, rejected) ← NEW   │
│  ├─ id_document (VARCHAR) ← NEW                                     │
│  └─ created_at (TIMESTAMP)                                          │
└─────────────────────────────────────────────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                      SECURITY LAYERS                                 │
└─────────────────────────────────────────────────────────────────────┘

Layer 1: File Upload Validation
├─ File type check (JPG, PNG, PDF only)
├─ File size limit (5MB max)
└─ Unique filename generation

Layer 2: Database Security
├─ PDO prepared statements
├─ Password hashing (bcrypt)
└─ Role-based access control

Layer 3: Session Security
├─ Session-based authentication
├─ Role verification on each request
└─ Verification status checks

Layer 4: Admin Protection
├─ Admin-only routes
├─ AJAX endpoint validation
└─ Action confirmations

Layer 5: File Storage
├─ Secure directory (uploads/)
├─ .gitignore protection
└─ Unique filenames (no overwrites)


┌─────────────────────────────────────────────────────────────────────┐
│                      STATUS FLOW DIAGRAM                             │
└─────────────────────────────────────────────────────────────────────┘

    BUYER REGISTRATION
         │
         ▼
    ┌─────────┐
    │APPROVED │ ──────► Can Login Immediately
    └─────────┘

    SELLER REGISTRATION
         │
         ▼
    ┌─────────┐
    │ PENDING │ ──────► Cannot Login
    └────┬────┘         (Waiting for Admin)
         │
         │ Admin Reviews
         │
    ┌────┴────┐
    │         │
    ▼         ▼
┌─────────┐ ┌─────────┐
│APPROVED │ │REJECTED │
└────┬────┘ └────┬────┘
     │           │
     │           │
     ▼           ▼
Can Login    Cannot Login
+ Email      (Contact Support)
Sent

    ADMIN REGISTRATION
         │
         ▼
    ┌─────────┐
    │APPROVED │ ──────► Can Login Immediately
    └─────────┘         (Redirects to Admin Panel)


┌─────────────────────────────────────────────────────────────────────┐
│                      EMAIL NOTIFICATION FLOW                         │
└─────────────────────────────────────────────────────────────────────┘

Admin clicks "Approve"
        ↓
admin_verify_seller.php
        ↓
Database updated
        ↓
sendVerificationEmail() called
        ↓
┌───────────────────────────────┐
│  Email Template Generated:    │
│  ┌─────────────────────────┐  │
│  │ Header (Green)          │  │
│  │ 🎉 Congratulations!     │  │
│  ├─────────────────────────┤  │
│  │ Body (Light Green)      │  │
│  │ Hello [Name],           │  │
│  │ Your account approved!  │  │
│  │ You can now:            │  │
│  │ ✅ List products        │  │
│  │ ✅ Connect with buyers  │  │
│  │ [Go to Dashboard]       │  │
│  ├─────────────────────────┤  │
│  │ Footer                  │  │
│  │ © 2024 Agri-Connect     │  │
│  └─────────────────────────┘  │
└───────────────────────────────┘
        ↓
sendSimpleEmail() or PHPMailer
        ↓
Email sent to seller's inbox
        ↓
Seller receives notification
        ↓
Seller clicks "Go to Dashboard"
        ↓
Seller logs in successfully! 🎉


═══════════════════════════════════════════════════════════════════════
                            END OF WORKFLOW
═══════════════════════════════════════════════════════════════════════
```

## 🎯 Key Takeaways

1. **Three User Types:** Buyer (instant), Seller (verified), Admin (manager)
2. **Automatic Detection:** System knows what to show based on role
3. **Secure Process:** Multiple validation layers protect the system
4. **Email Integration:** Professional notifications keep users informed
5. **Admin Control:** Full oversight of seller registrations
6. **Status Management:** Clear workflow from pending to approved
7. **User Experience:** Smooth, intuitive process for all parties

## 📱 User Experience Summary

| User Type | Registration | Verification | Login | Dashboard |
|-----------|-------------|--------------|-------|-----------|
| Buyer     | Simple form | None         | ✅ Immediate | Standard |
| Seller    | + ID upload | Admin review | ⏳ After approval | Full features |
| Admin     | Pre-created | None         | ✅ Immediate | Admin panel |

---

**This visual guide shows the complete flow of the seller verification system from registration to approval!**
