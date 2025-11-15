<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportSqlData extends Command
{
    protected $signature = 'db:import-sql {file} {--force : Force import even if tables have data}';
    protected $description = 'Import data from SQL dump file';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        // Check database connection - force reload from env
        $connection = env('DB_CONNECTION', config('database.default'));
        $this->info("Using database connection: {$connection}");
        $this->info("DB_CONNECTION from env: " . env('DB_CONNECTION', 'not set'));
        $this->info("DB_HOST from env: " . env('DB_HOST', 'not set'));
        
        if ($connection === 'sqlite' || empty($connection)) {
            $this->error("Error: Database masih menggunakan SQLite atau tidak terdeteksi!");
            $this->warn("Pastikan DB_CONNECTION=mysql di Railway environment variables");
            $this->warn("Coba clear config cache: railway run php artisan config:clear");
            return 1;
        }

        $this->info("Reading SQL file: {$file}");
        $sql = file_get_contents($file);
        
        // Remove comments and empty lines
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Extract INSERT statements more carefully - handle multi-line VALUES
        preg_match_all('/INSERT INTO\s+`?(\w+)`?\s*\([^)]+\)\s*VALUES\s*((?:\([^)]+\),?\s*)+);/is', $sql, $matches, PREG_SET_ORDER);
        
        $statements = [];
        foreach ($matches as $match) {
            $statements[] = [
                'table' => $match[1],
                'sql' => trim($match[0])
            ];
        }

        $this->info("Found " . count($statements) . " INSERT statements");
        
        // Define import order (tables without foreign keys first)
        $importOrder = [
            'kategori',
            'petugas',
            'users',
            'posts',
            'galery',
            'foto',
            'agenda',
            'informasi',
            'site_settings',
            'cache',
            'sessions',
            'gallery_like_logs',
            'user_likes',
            'user_dislikes',
            'migrations',
        ];
        
        // Group by table
        $byTable = [];
        foreach ($statements as $stmt) {
            if (!isset($byTable[$stmt['table']])) {
                $byTable[$stmt['table']] = [];
            }
            $byTable[$stmt['table']][] = $stmt['sql'];
        }
        
        // Reorder statements according to import order
        $orderedStatements = [];
        foreach ($importOrder as $table) {
            if (isset($byTable[$table])) {
                foreach ($byTable[$table] as $sql) {
                    $orderedStatements[] = [
                        'table' => $table,
                        'sql' => $sql
                    ];
                }
            }
        }
        
        // Add any remaining tables not in import order
        foreach ($byTable as $table => $sqls) {
            if (!in_array($table, $importOrder)) {
                foreach ($sqls as $sql) {
                    $orderedStatements[] = [
                        'table' => $table,
                        'sql' => $sql
                    ];
                }
            }
        }
        
        $statements = $orderedStatements;

        $bar = $this->output->createProgressBar(count($statements));
        $bar->start();

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        // Truncate tables if --force (in reverse order to respect foreign keys)
        if ($this->option('force')) {
            $this->info("\nTruncating tables (--force enabled)...");
            $tablesToTruncate = array_reverse(array_keys($byTable));
            try {
                DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                foreach ($tablesToTruncate as $table) {
                    if (Schema::hasTable($table)) {
                        try {
                            DB::table($table)->truncate();
                        } catch (\Exception $e) {
                            // Ignore truncate errors for specific tables
                        }
                    }
                }
                DB::statement("SET FOREIGN_KEY_CHECKS=1;");
            } catch (\Exception $e) {
                DB::statement("SET FOREIGN_KEY_CHECKS=1;");
            }
        }

        foreach ($statements as $stmt) {
            $table = $stmt['table'];
            $statement = $stmt['sql'];

            try {
                // Check if table exists
                if (!Schema::hasTable($table)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }
                
                // Check if table has data (unless --force)
                if (!$this->option('force')) {
                    $count = DB::table($table)->count();
                    if ($count > 0) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }
                }
                
                // Use DB::unprepared for raw SQL with complex strings
                // Disable foreign key checks temporarily
                DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                
                // Try to execute statement
                try {
                    DB::unprepared($statement);
                    $imported++;
                } catch (\Exception $insertError) {
                    $errorMsg = $insertError->getMessage();
                    
                    // Check if it's a column not found error - try to fix it
                    if (strpos($errorMsg, 'Column not found') !== false || strpos($errorMsg, 'Unknown column') !== false) {
                        // Try to modify SQL to remove missing columns (recursive fix for multiple missing columns)
                        $modifiedStatement = $this->fixMissingColumns($statement, $table, $errorMsg);
                        if ($modifiedStatement && $modifiedStatement !== $statement) {
                            try {
                                DB::unprepared($modifiedStatement);
                                $imported++;
                                $this->info("\n✅ Table {$table}: Fixed and imported (removed missing columns)");
                            } catch (\Exception $retryError) {
                                // If still error, try fixing again (recursive)
                                $retryErrorMsg = $retryError->getMessage();
                                if (strpos($retryErrorMsg, 'Column not found') !== false || strpos($retryErrorMsg, 'Unknown column') !== false) {
                                    $secondFix = $this->fixMissingColumns($modifiedStatement, $table, $retryErrorMsg);
                                    if ($secondFix && $secondFix !== $modifiedStatement) {
                                        try {
                                            DB::unprepared($secondFix);
                                            $imported++;
                                            $this->info("\n✅ Table {$table}: Fixed and imported (removed multiple missing columns)");
                                        } catch (\Exception $finalError) {
                                            throw $insertError; // Re-throw original error
                                        }
                                    } else {
                                        throw $insertError; // Re-throw original error
                                    }
                                } else {
                                    throw $insertError; // Re-throw original error
                                }
                            }
                        } else {
                            throw $insertError; // Re-throw if can't fix
                        }
                    } else {
                        throw $insertError; // Re-throw other errors
                    }
                }
                
                DB::statement("SET FOREIGN_KEY_CHECKS=1;");
            } catch (\Exception $e) {
                DB::statement("SET FOREIGN_KEY_CHECKS=1;"); // Re-enable in case of error
                $errors++;
                // Only show first few errors to avoid spam
                if ($errors <= 10) {
                    $errorMsg = $e->getMessage();
                    // Check if it's a column not found error
                    if (strpos($errorMsg, 'Column not found') !== false || strpos($errorMsg, 'Unknown column') !== false) {
                        $this->warn("\n⚠️  Table {$table}: Column missing (migration mungkin belum jalan) - Jalankan: railway run php artisan migrate --force");
                    } else {
                        $this->error("\n❌ Error in table {$table}: " . substr($errorMsg, 0, 150));
                    }
                }
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("Import completed!");
        $this->info("Imported: {$imported} statements");
        $this->info("Skipped: {$skipped} statements");
        if ($errors > 0) {
            $this->warn("Errors: {$errors} statements");
            if ($errors > 5) {
                $this->warn("(Only showing first 5 errors)");
            }
        }

        return 0;
    }
    
    /**
     * Fix SQL statement by removing missing columns
     */
    private function fixMissingColumns($statement, $table, $errorMsg)
    {
        // Extract missing column name from error
        preg_match("/Unknown column '([^']+)'/", $errorMsg, $matches);
        if (empty($matches[1])) {
            return null;
        }
        
        $missingColumn = $matches[1];
        
        // Remove column from INSERT statement
        // Pattern: INSERT INTO `table` (`col1`, `col2`, ...) VALUES (...)
        // Handle multi-line VALUES
        if (preg_match('/INSERT INTO\s+`?(\w+)`?\s*\(([^)]+)\)\s*VALUES\s*((?:\([^)]+\),?\s*)+);/is', $statement, $parts)) {
            $tableName = $parts[1];
            $columnsStr = $parts[2];
            $valuesStr = $parts[3];
            
            // Parse columns
            $columns = array_map(function($col) {
                return trim(str_replace(['`', "'", '"'], '', $col));
            }, explode(',', $columnsStr));
            
            // Find and remove missing column index
            $columnIndex = array_search($missingColumn, $columns);
            if ($columnIndex === false) {
                // Try with backticks
                $columnIndex = array_search("`{$missingColumn}`", array_map('trim', explode(',', $columnsStr)));
            }
            
            if ($columnIndex !== false) {
                // Remove column from list
                unset($columns[$columnIndex]);
                $columns = array_values($columns);
                
                // Parse and fix all value rows
                // Extract all value rows: (val1, val2, ...), (val1, val2, ...)
                preg_match_all('/\(([^)]+)\)/', $valuesStr, $valueRows);
                $fixedValueRows = [];
                
                foreach ($valueRows[1] as $row) {
                    // Split values carefully (handle quoted strings)
                    $values = [];
                    $current = '';
                    $inQuotes = false;
                    $quoteChar = null;
                    
                    for ($i = 0; $i < strlen($row); $i++) {
                        $char = $row[$i];
                        
                        if (($char === '"' || $char === "'") && ($i === 0 || $row[$i-1] !== '\\')) {
                            if (!$inQuotes) {
                                $inQuotes = true;
                                $quoteChar = $char;
                            } elseif ($char === $quoteChar) {
                                $inQuotes = false;
                                $quoteChar = null;
                            }
                        }
                        
                        if ($char === ',' && !$inQuotes) {
                            $values[] = trim($current);
                            $current = '';
                        } else {
                            $current .= $char;
                        }
                    }
                    if ($current !== '') {
                        $values[] = trim($current);
                    }
                    
                    // Remove value at missing column index
                    if (isset($values[$columnIndex])) {
                        unset($values[$columnIndex]);
                        $values = array_values($values);
                    }
                    
                    $fixedValueRows[] = '(' . implode(', ', $values) . ')';
                }
                
                // Rebuild statement
                $newColumns = array_map(function($col) {
                    return "`{$col}`";
                }, $columns);
                
                $newStatement = "INSERT INTO `{$tableName}` (" . implode(', ', $newColumns) . ") VALUES " . implode(', ', $fixedValueRows) . ";";
                return $newStatement;
            }
        }
        
        return null;
    }
}

