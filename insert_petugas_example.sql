-- Contoh SQL untuk insert petugas baru
-- Ganti username, email, dan password_hash sesuai kebutuhan

USE railway;

-- Insert petugas baru
-- Password: admin123
-- Hash: Generate dengan: php artisan tinker --execute="echo Hash::make('admin123');"
INSERT INTO `petugas` (`username`, `email`, `password`, `created_at`, `updated_at`) 
VALUES 
('admin_test', 'admin_test@gmail.com', '$2y$12$k9MXDGUVFTK9lmzhCNgRF.ZzxxyJ2JrHNVgu3/.INRfXz5OYOnkoa', NOW(), NOW());

-- Cek hasil
SELECT id, username, email, created_at FROM `petugas` ORDER BY id DESC LIMIT 5;
