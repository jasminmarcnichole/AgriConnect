# Agi-Connect (PHP + MySQL)

Simple starter implementation for:
- Landing page with section navigation and Login/Signup modals
- RBAC for buyers and sellers
- Product offers restricted to logged-in users
- Seller dashboard (profile, posted products, conversations)
- Buyer dashboard (profile, conversations)
- Product details with seller contact/chat entry

## Requirements
- PHP 8.1+
- MySQL 8+

## Setup
1. Create database and tables:
   - Import `database/schema.sql` into MySQL.
2. Configure DB credentials in `config.php`.
3. Run with PHP built-in server from project root:
   ```bash
   php -S localhost:8000
   ```
4. Open `http://localhost:8000`.

## Default flow
- Sign up as buyer or seller from modal.
- Sellers can add products from dashboard.
- Buyers can open Product Offer page and start chat with seller.
