<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cek apakah user test@example.com sudah ada
        $existingUser = User::where('email', 'test@example.com')->first();
        if (!$existingUser) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
        
        // Seeder kategori default sesuai requirement UKK
        // Hanya membuat kategori jika belum ada, tidak menghapus yang sudah ada
        $kategoriList = [
            'Informasi Terkini',
            'Galery Sekolah', 
            'Agenda Sekolah',
        ];
        
        foreach ($kategoriList as $judul) {
            // Cek apakah kategori sudah ada
            $existingKategori = \App\Models\Kategori::where('judul', $judul)->first();
            if (!$existingKategori) {
                \App\Models\Kategori::create(['judul' => $judul]);
            }
        }

        // Panggil seeder yang diperlukan
        $this->call([
            PetugasSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}