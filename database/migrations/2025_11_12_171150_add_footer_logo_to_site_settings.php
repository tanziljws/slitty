<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert footer_logo setting if it doesn't exist
        DB::table('site_settings')->insertOrIgnore([
            'key' => 'footer_logo',
            'label' => 'Logo Footer',
            'type' => 'image',
            'value' => null,
            'group' => 'footer',
            'description' => 'Logo untuk ditampilkan di footer website',
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->where('key', 'footer_logo')->delete();
    }
};
