<?php
/**
 * Helper script untuk generate password hash untuk petugas baru
 * Usage: php create_petugas_helper.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;

echo "==========================================\n";
echo "Petugas Password Hash Generator\n";
echo "==========================================\n\n";

// Password yang ingin di-hash
$password = 'admin123'; // Ganti dengan password yang diinginkan

$hash = Hash::make($password);

echo "Password: {$password}\n";
echo "Hash: {$hash}\n\n";

echo "SQL Query:\n";
echo "INSERT INTO `petugas` (`username`, `email`, `password`, `created_at`, `updated_at`) \n";
echo "VALUES \n";
echo "('username_baru', 'email_baru@gmail.com', '{$hash}', NOW(), NOW());\n\n";

echo "Atau gunakan command MySQL:\n";
echo "mysql -h yamanote.proxy.rlwy.net -u root -pDmAxrbVXaioQUfuttWoIIRCjlkMPzqJD --port 54511 --protocol=TCP railway -e \"INSERT INTO petugas (username, email, password, created_at, updated_at) VALUES ('username_baru', 'email_baru@gmail.com', '{$hash}', NOW(), NOW());\"\n";

