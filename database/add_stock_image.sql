-- Migration to add stock and image columns to products table
USE agi_connect;

ALTER TABLE products 
ADD COLUMN stock INT DEFAULT 0 AFTER price,
ADD COLUMN image VARCHAR(255) NULL AFTER stock;
