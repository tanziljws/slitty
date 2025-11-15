<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckTableStructure extends Command
{
    protected $signature = 'db:check-structure {table?}';
    protected $description = 'Check table structure and missing columns';

    public function handle()
    {
        $table = $this->argument('table');
        
        if ($table) {
            $this->checkTable($table);
        } else {
            // Check all important tables
            $tables = ['foto', 'petugas', 'galery', 'posts', 'agenda', 'informasi'];
            foreach ($tables as $t) {
                $this->checkTable($t);
                $this->newLine();
            }
        }
        
        return 0;
    }
    
    private function checkTable($table)
    {
        $this->info("📊 Checking table: {$table}");
        $this->line("─────────────────────────────────────");
        
        if (!Schema::hasTable($table)) {
            $this->error("❌ Table '{$table}' does not exist!");
            $this->warn("   Run: railway run php artisan migrate --force");
            return;
        }
        
        // Get columns from database
        $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
        $columnNames = array_map(function($col) {
            return $col->Field;
        }, $columns);
        
        $this->info("✅ Table exists");
        $this->line("Columns: " . implode(', ', $columnNames));
        
        // Check for expected columns
        $expectedColumns = $this->getExpectedColumns($table);
        if ($expectedColumns) {
            $this->newLine();
            $this->line("Expected columns:");
            foreach ($expectedColumns as $col) {
                if (in_array($col, $columnNames)) {
                    $this->line("  ✅ {$col}");
                } else {
                    $this->error("  ❌ {$col} - MISSING!");
                }
            }
        }
        
        // Count rows
        try {
            $count = DB::table($table)->count();
            $this->newLine();
            $this->line("Rows: {$count}");
        } catch (\Exception $e) {
            $this->warn("Could not count rows: " . $e->getMessage());
        }
    }
    
    private function getExpectedColumns($table)
    {
        $expected = [
            'foto' => ['id', 'galery_id', 'file', 'likes', 'dislikes', 'created_at', 'updated_at'],
            'petugas' => ['id', 'nama', 'email', 'password', 'remember_token'],
            'galery' => ['id', 'post_id', 'position', 'status'],
            'posts' => ['id', 'judul', 'deskripsi', 'kategori_id', 'petugas_id', 'created_at', 'updated_at'],
            'agenda' => ['id', 'judul', 'deskripsi', 'tanggal', 'created_at', 'updated_at'],
            'informasi' => ['id', 'judul', 'deskripsi', 'created_at', 'updated_at'],
        ];
        
        return $expected[$table] ?? null;
    }
}

