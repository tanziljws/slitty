<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixMissingColumns extends Command
{
    protected $signature = 'db:fix-missing-columns';
    protected $description = 'Add missing columns directly to database';

    public function handle()
    {
        $this->info("🔧 Fixing missing columns...");
        $this->newLine();
        
        // Fix foto table
        if (Schema::hasTable('foto')) {
            if (!Schema::hasColumn('foto', 'likes')) {
                $this->info("Adding 'likes' column to foto table...");
                try {
                    DB::statement("ALTER TABLE `foto` ADD COLUMN `likes` BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER `file`");
                    $this->info("✅ Added 'likes' column");
                } catch (\Exception $e) {
                    $this->error("❌ Failed: " . $e->getMessage());
                }
            } else {
                $this->info("✅ Column 'likes' already exists in foto table");
            }
            
            if (!Schema::hasColumn('foto', 'dislikes')) {
                $this->info("Adding 'dislikes' column to foto table...");
                try {
                    DB::statement("ALTER TABLE `foto` ADD COLUMN `dislikes` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `likes`");
                    $this->info("✅ Added 'dislikes' column");
                } catch (\Exception $e) {
                    $this->error("❌ Failed: " . $e->getMessage());
                }
            } else {
                $this->info("✅ Column 'dislikes' already exists in foto table");
            }
        }
        
        $this->newLine();
        
        // Fix petugas table
        if (Schema::hasTable('petugas')) {
            if (!Schema::hasColumn('petugas', 'remember_token')) {
                $this->info("Adding 'remember_token' column to petugas table...");
                try {
                    DB::statement("ALTER TABLE `petugas` ADD COLUMN `remember_token` VARCHAR(100) NULL AFTER `password`");
                    $this->info("✅ Added 'remember_token' column");
                } catch (\Exception $e) {
                    $this->error("❌ Failed: " . $e->getMessage());
                }
            } else {
                $this->info("✅ Column 'remember_token' already exists in petugas table");
            }
        }
        
        $this->newLine();
        $this->info("✅ Done! Checking structure...");
        $this->newLine();
        
        // Verify
        $this->call('db:check-structure', ['table' => 'foto']);
        $this->call('db:check-structure', ['table' => 'petugas']);
        
        return 0;
    }
}

