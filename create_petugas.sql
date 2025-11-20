-- Script untuk membuat akun petugas baru
-- Usage: mysql -h yamanote.proxy.rlwy.net -u root -pDmAxrbVXaioQUfuttWoIIRCjlkMPzqJD --port 54511 --protocol=TCP railway < create_petugas.sql

USE railway;

-- Insert petugas baru dengan password: admin123
-- Password hash: $2y$12$qO4.FK8IUlfYXHEONGv5rutfjBb0loqZk6yzs2qisNtX42/m7mTqq (untuk admin123)
-- Atau generate baru dengan: php artisan tinker -> Hash::make('password')

-- Option 1: Insert dengan password hash yang sudah ada (admin123)
INSERT INTO `petugas` (`username`, `email`, `password`, `created_at`, `updated_at`) 
VALUES 
('admin_new', 'admin_new@gmail.com', '$2y$12$qO4.FK8IUlfYXHEONGv5rutfjBb0loqZk6yzs2qisNtX42/m7mTqq', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Option 2: Insert dengan password hash baru (generate dulu dengan PHP)
-- Ganti PASSWORD_HASH dengan hasil dari: php artisan tinker -> Hash::make('password_anda')
-- INSERT INTO `petugas` (`username`, `email`, `password`, `created_at`, `updated_at`) 
-- VALUES 
-- ('username_baru', 'email_baru@gmail.com', 'PASSWORD_HASH', NOW(), NOW());

-- Cek hasil
SELECT id, username, email, created_at FROM `petugas` ORDER BY id DESC LIMIT 5;

