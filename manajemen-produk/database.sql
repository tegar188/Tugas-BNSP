-- =====================================================
-- Database Script untuk Aplikasi Manajemen Produk
-- =====================================================

-- Membuat database baru
CREATE DATABASE IF NOT EXISTS db_manajemen_produk;

-- Menggunakan database
USE db_manajemen_produk;

-- Membuat tabel produk
CREATE TABLE IF NOT EXISTS produk (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL,
    harga DECIMAL(15,2) NOT NULL DEFAULT 0,
    stok INT(11) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data sample untuk testing
INSERT INTO produk (nama_produk, harga, stok) VALUES
('Laptop ASUS ROG', 15000000, 10),
('Mouse Gaming Logitech', 350000, 50),
('Keyboard Mechanical', 750000, 30),
('Monitor LED 24 inch', 2500000, 15),
('Webcam HD 1080p', 450000, 25),
('Headset Gaming', 600000, 20),
('SSD 500GB', 850000, 40),
('RAM DDR4 16GB', 1200000, 35),
('Power Supply 650W', 950000, 18),
('Cooling Fan RGB', 200000, 60);

-- Query untuk melihat data
SELECT * FROM produk;

-- Query untuk menghitung total produk
SELECT COUNT(*) as total_produk FROM produk;

-- Query untuk menghitung total nilai stok
SELECT SUM(harga * stok) as total_nilai_stok FROM produk;

-- =====================================================
-- Catatan:
-- 1. Jalankan script ini di phpMyAdmin atau MySQL Client
-- 2. Pastikan MySQL sudah berjalan (XAMPP/WAMP/MAMP)
-- 3. Sesuaikan kredensial database di config/database.php
-- =====================================================