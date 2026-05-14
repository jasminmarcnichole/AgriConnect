-- Quick Fix SQL Script for Seller Verification System
-- Run this if you have an existing database and don't want to drop it

USE agi_connect;

-- Add verification_status column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER role;

-- Add id_document column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS id_document VARCHAR(255) NULL AFTER verification_status;

-- Update role enum to include 'admin'
ALTER TABLE users 
MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL;

-- Create admin account if it doesn't exist
-- Password is: admin123
INSERT IGNORE INTO users (full_name, email, password_hash, role, verification_status) 
VALUES ('Admin', 'admin@agriconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'approved');

-- Update existing buyers to have 'approved' status
UPDATE users SET verification_status = 'approved' WHERE role = 'buyer' AND verification_status IS NULL;

-- Update existing sellers to have 'approved' status (so they can continue working)
-- If you want them to go through verification, change 'approved' to 'pending'
UPDATE users SET verification_status = 'approved' WHERE role = 'seller' AND verification_status IS NULL;

-- Verify the changes
SELECT 'Database updated successfully!' as Status;
SELECT COUNT(*) as AdminCount FROM users WHERE role = 'admin';
SELECT COUNT(*) as SellerCount FROM users WHERE role = 'seller';
SELECT COUNT(*) as BuyerCount FROM users WHERE role = 'buyer';
