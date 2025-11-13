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
        // Insert profile_image setting if it doesn't exist
        DB::table('site_settings')->insertOrIgnore([
            'key' => 'profile_image',
            'label' => 'Foto Profil Sekolah',
            'type' => 'image',
            'value' => null,
            'group' => 'profile',
            'description' => 'Foto latar belakang untuk profil sekolah',
            'order' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->where('key', 'profile_image')->delete();
    }
};
