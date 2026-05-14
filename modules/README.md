# Agri-Connect - Premium Agricultural Marketplace

<div align="center">
  <img src="assets/images/logo.svg" alt="Agri-Connect Logo" width="300">
  <p><strong>Empowering Agriculture Through Technology</strong></p>
</div>

## 🌾 Overview

Agri-Connect is a premium, enterprise-grade agricultural marketplace platform that connects farmers, suppliers, and buyers across the nation. Built with modern web technologies and designed with a focus on security, usability, and scalability.

## ✨ Key Features

### For All Users
- 🔐 **Secure Authentication** - Role-based access control (RBAC) for buyers and sellers
- 💬 **Real-time Messaging** - Built-in chat system for direct buyer-seller communication
- 📱 **Responsive Design** - Fully optimized for desktop, tablet, and mobile devices
- 🎨 **Premium UI/UX** - Modern, agriculture-themed interface with smooth animations
- 🔍 **Product Discovery** - Easy-to-navigate marketplace with detailed product listings

### For Buyers
- Browse verified agricultural products with images
- View real-time stock availability
- Direct communication with sellers
- Secure transaction environment
- Access to seller ratings and reviews
- Conversation history management
- Preview products on landing page

### For Sellers
- Easy product listing management with image uploads
- Stock inventory management
- Dashboard with analytics
- Direct buyer communication
- Product inventory tracking
- Edit and update product information
- Professional seller profile

## 🛠️ Technology Stack

- **Backend:** PHP 8.1+
- **Database:** MySQL 8+
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Design:** Custom CSS with CSS Variables, Gradients, and Animations
- **Icons:** SVG-based custom icons
- **Architecture:** MVC-inspired structure with session-based authentication

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Modern web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation & Setup

### 1. Clone or Download the Project
```bash
git clone <repository-url>
cd AgriConnect
```

### 2. Database Setup
```bash
# Create a new MySQL database
mysql -u root -p
CREATE DATABASE agriconnect;
USE agriconnect;

# Import the schema
source database/schema.sql;
```

**OR** use the web-based migration tool:
1. Access `http://localhost/AgriConnect/migrate.php`
2. Follow the on-screen instructions
3. Delete `migrate.php` after successful migration

### 3. Configure Database Connection
Edit `config.php` with your database credentials:
```php
$host = 'localhost';
$dbname = 'agriconnect';
$username = 'your_username';
$password = 'your_password';
```

### 4. Setup Upload Directories
Ensure the upload directories exist and are writable:
```bash
mkdir -p uploads/products uploads/id_documents
chmod 755 uploads/products uploads/id_documents
```

On Windows (XAMPP): The directories should be created automatically.

### 5. Start the Server

#### Using PHP Built-in Server (Development)
```bash
php -S localhost:8000
```

#### Using XAMPP
1. Copy the project to `htdocs/AgriConnect`
2. Start Apache and MySQL from XAMPP Control Panel
3. Access via `http://localhost/AgriConnect`

### 6. Access the Application
Open your browser and navigate to:
- Development: `http://localhost:8000`
- XAMPP: `http://localhost/AgriConnect`

## 👥 User Roles

### Buyer Account
- Browse and search products
- Contact sellers via chat
- Manage conversations
- View product details
- **No verification required** - Immediate access

### Seller Account
- All buyer features
- Add/manage product listings
- View seller dashboard
- Track product performance
- **Requires verification** - Must upload valid ID and await admin approval

### Admin Account
- Manage seller verifications
- Review ID documents
- Approve/reject seller registrations
- View system statistics
- **Default credentials:** admin@agriconnect.com / admin123

## 📁 Project Structure

```
AgriConnect/
├── assets/
│   ├── images/          # SVG images, icons, and graphics
│   │   ├── logo.svg
│   │   ├── favicon.svg
│   │   ├── hero-bg.svg
│   │   ├── pattern.svg
│   │   ├── product-placeholder.svg
│   │   └── icons.svg
│   └── styles.css       # Main stylesheet with premium design
├── database/
│   ├── schema.sql       # Database schema
│   ├── add_stock_image.sql  # Migration for stock & image columns
│   └── MIGRATION_README.md  # Migration instructions
├── uploads/
│   ├── id_documents/    # Seller ID documents (secure storage)
│   ├── products/        # Product images
│   └── .htaccess        # Security rules for uploads
├── auth.php             # Authentication helpers
├── config.php           # Database configuration
├── email_config.php     # Email/SMTP configuration
├── index.php            # Landing page with featured products
├── login.php            # Login handler
├── signup.php           # Registration handler (with ID upload)
├── logout.php           # Logout handler
├── dashboard.php        # User dashboard
├── admin_dashboard.php  # Admin verification panel
├── admin_verify_seller.php  # Seller approval handler
├── products.php         # Product marketplace
├── product.php          # Product details
├── seller_products.php  # Seller product management
├── edit_product.php     # Product editing interface
├── profile.php          # User profile
├── conversations.php    # Conversation list
├── chat.php             # Chat interface
├── migrate.php          # Database migration tool
├── README.md            # Main documentation
├── QUICKSTART.md        # Quick setup guide
├── VERIFICATION_SETUP.md # Detailed verification system docs
└── PRODUCT_FEATURES_UPDATE.md # Product features documentation
```

## 🎨 Design Features

### Color Palette
- **Primary:** Forest Green (#2d5016)
- **Secondary:** Olive Green (#6b8e23)
- **Accent:** Lime Green (#8fbc4b)
- **Gold:** Premium Gold (#d4af37)
- **Light:** Off-white (#f4f7f0)
- **Dark:** Deep Green (#1a2f0a)

### UI Components
- Gradient backgrounds and buttons
- Smooth animations and transitions
- Card-based layouts
- Modal dialogs
- Badge system
- Responsive navigation
- Loading states
- Empty states

## 🔒 Security Features

- Session-based authentication
- Role-based access control (RBAC)
- SQL injection prevention (PDO prepared statements)
- XSS protection (htmlspecialchars)
- CSRF protection ready
- Secure password handling

## 📱 Responsive Design

The platform is fully responsive and optimized for:
- Desktop (1920px+)
- Laptop (1366px - 1920px)
- Tablet (768px - 1366px)
- Mobile (320px - 768px)

## 🚦 Getting Started Guide

### Quick Setup (New!)
For fastest setup, see **[QUICKSTART.md](QUICKSTART.md)** - Get running in 3 steps!

### For New Users
1. Visit the homepage
2. Click "Signup" button
3. Choose your role (Buyer or Seller)
4. **If Seller:** Upload a valid ID document (JPG/PNG/PDF)
5. Fill in your details
6. **Buyers:** Start exploring immediately!
7. **Sellers:** Wait for admin approval (you'll receive an email)

### For Sellers
1. Register and upload your ID document
2. Wait for admin approval email
3. Login to your account
4. Navigate to "My Products"
5. Click "Add New Product"
6. Upload product image and fill in details (title, description, price, stock)
7. Your product is now live!
8. Edit products anytime by viewing them and clicking "Edit Product Information"

### For Buyers
1. Register (no verification needed)
2. Login to your account
3. Browse products on the landing page or "Product Marketplace"
4. Check stock availability and product images
5. Click on any product for full details
6. Contact seller via chat (if product is in stock)
7. Start your conversation!

### For Admin
1. Login with: admin@agriconnect.com / admin123
2. Review pending seller registrations
3. View uploaded ID documents
4. Approve or reject sellers
5. System sends automatic email notifications

## 🔧 Customization

### Changing Colors
Edit CSS variables in `assets/styles.css`:
```css
:root {
  --primary: #2d5016;
  --secondary: #6b8e23;
  --accent: #8fbc4b;
  --gold: #d4af37;
}
```

### Adding New Features
1. Create new PHP file
2. Include `auth.php` for authentication
3. Include `config.php` for database access
4. Follow existing code patterns

## 📊 Database Schema

The system uses 4 main tables:
- **users** - User accounts, authentication, and verification status
- **products** - Product listings with images and stock
- **conversations** - Chat conversations
- **messages** - Chat messages

### Product Fields
- `id` - Primary key
- `seller_id` - Foreign key to users table
- `title` - Product name
- `description` - Product description
- `price` - Product price (₱)
- `stock` - Available quantity
- `image` - Product image filename
- `created_at` - Timestamp

### User Verification Fields
- `users.role` - 'buyer', 'seller', or 'admin'
- `users.verification_status` - 'pending', 'approved', or 'rejected'
- `users.id_document` - Filename of uploaded ID document

## 🐛 Troubleshooting

### Database Connection Error
- Check `config.php` credentials
- Ensure MySQL is running
- Verify database exists

### Session Issues
- Clear browser cookies
- Check PHP session configuration
- Ensure write permissions on session directory

### Styling Issues
- Clear browser cache
- Check CSS file path
- Verify assets folder permissions

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📄 License

This project is open source and available under the MIT License.

## 📞 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check documentation

## 🎯 Future Enhancements

- [x] **Seller Verification System** - ID document upload and admin approval
- [x] **Admin Dashboard** - Manage and verify seller registrations
- [x] **Email Notifications** - Automatic approval notifications via Gmail
- [x] **Product Images** - Upload and display product photos
- [x] **Stock Management** - Track product inventory
- [x] **Product Editing** - Sellers can update their listings
- [x] **Landing Page Products** - Featured products showcase
- [x] **Login-Required Product Views** - Increase user registrations
- [ ] Payment gateway integration
- [ ] Advanced search and filters
- [ ] Product reviews and ratings
- [ ] Multiple images per product
- [ ] Product categories
- [ ] Analytics and reporting
- [ ] Multi-language support
- [ ] Mobile app
- [ ] API for third-party integrations

## 🌟 Credits

Developed with ❤️ for the agricultural community.

---

<div align="center">
  <p><strong>Agri-Connect - Cultivating Connections, Growing Together</strong></p>
  <p>© 2024 Agri-Connect. All rights reserved.</p>
</div>
