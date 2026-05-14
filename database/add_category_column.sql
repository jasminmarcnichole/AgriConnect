-- Migration: Add category column to products table
-- Run this SQL to add product categories feature

USE agi_connect;

-- Add category column to products table
ALTER TABLE products 
ADD COLUMN category VARCHAR(50) DEFAULT 'Other' AFTER description;

-- Update existing products with default category
UPDATE products SET category = 'Other' WHERE category IS NULL;
