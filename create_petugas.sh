#!/bin/bash

# Script untuk membuat akun petugas baru di database production
# Usage: ./create_petugas.sh [username] [email] [password]

DB_HOST="yamanote.proxy.rlwy.net"
DB_USER="root"
DB_PASS="DmAxrbVXaioQUfuttWoIIRCjlkMPzqJD"
DB_PORT="54511"
DB_NAME="railway"

if [ -z "$1" ] || [ -z "$2" ] || [ -z "$3" ]; then
    echo "Usage: $0 [username] [email] [password]"
    echo ""
    echo "Example:"
    echo "  $0 admin_new admin_new@gmail.com admin123"
    exit 1
fi

USERNAME=$1
EMAIL=$2
PASSWORD=$3

echo "=========================================="
echo "Creating Petugas Account"
echo "=========================================="
echo "Username: $USERNAME"
echo "Email: $EMAIL"
echo "Password: [hidden]"
echo ""

# Generate password hash menggunakan PHP
echo "Generating password hash..."
PASSWORD_HASH=$(php artisan tinker --execute="echo Hash::make('$PASSWORD');" 2>/dev/null | tail -1)

if [ -z "$PASSWORD_HASH" ]; then
    echo "Error: Failed to generate password hash"
    echo "Trying alternative method..."
    PASSWORD_HASH=$(php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo Hash::make('$PASSWORD');")
fi

if [ -z "$PASSWORD_HASH" ]; then
    echo "Error: Still failed to generate password hash"
    echo "Please run manually: php create_petugas_helper.php"
    exit 1
fi

echo "Password hash generated: ${PASSWORD_HASH:0:30}..."
echo ""

# Insert ke database
echo "Inserting into database..."
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" --port "$DB_PORT" --protocol=TCP "$DB_NAME" <<EOF
INSERT INTO \`petugas\` (\`username\`, \`email\`, \`password\`, \`created_at\`, \`updated_at\`) 
VALUES ('$USERNAME', '$EMAIL', '$PASSWORD_HASH', NOW(), NOW())
ON DUPLICATE KEY UPDATE \`updated_at\` = NOW();
EOF

if [ $? -eq 0 ]; then
    echo "✅ Petugas account created successfully!"
    echo ""
    echo "Credentials:"
    echo "  Username: $USERNAME"
    echo "  Email: $EMAIL"
    echo "  Password: $PASSWORD"
    echo ""
    echo "You can now login with:"
    echo "  Email: $EMAIL"
    echo "  OR"
    echo "  Username: $USERNAME"
    echo "  Password: $PASSWORD"
else
    echo "❌ Failed to create petugas account"
    exit 1
fi

