#!/bin/bash

echo "=========================================="
echo "  Setup Railway - Import Data"
echo "=========================================="
echo ""

# Step 1: Run migrations
echo "📦 Step 1: Running migrations..."
railway run php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi

echo "✅ Migrations completed!"
echo ""

# Step 2: Import SQL data
echo "📥 Step 2: Importing SQL data..."
railway run php artisan db:import-sql "galeri_edu (3).sql" --force

if [ $? -ne 0 ]; then
    echo "⚠️  Import completed with some errors (check above)"
else
    echo "✅ Import completed successfully!"
fi

echo ""

# Step 3: Clear cache
echo "🧹 Step 3: Clearing cache..."
railway run php artisan view:clear
railway run php artisan config:clear
railway run php artisan cache:clear

echo "✅ Cache cleared!"
echo ""

# Step 4: Check data
echo "📊 Step 4: Checking imported data..."
railway run php artisan tinker --execute="
echo 'Data Summary:' . PHP_EOL;
echo '=============' . PHP_EOL;
echo 'Galery: ' . \App\Models\galery::count() . PHP_EOL;
echo 'Foto: ' . \App\Models\Foto::count() . PHP_EOL;
echo 'Posts: ' . \App\Models\Post::count() . PHP_EOL;
echo 'Agenda: ' . \App\Models\Agenda::count() . PHP_EOL;
echo 'Informasi: ' . \App\Models\Informasi::count() . PHP_EOL;
echo 'Kategori: ' . \App\Models\Kategori::count() . PHP_EOL;
echo 'Users: ' . \App\Models\User::count() . PHP_EOL;
echo 'Petugas: ' . \App\Models\Petugas::count() . PHP_EOL;
"

echo ""
echo "=========================================="
echo "  Setup Complete!"
echo "=========================================="
echo ""
echo "Test your application:"
echo "  Homepage: https://ujikom-siti-production.up.railway.app/"
echo "  Gallery: https://ujikom-siti-production.up.railway.app/user/gallery"
echo ""



