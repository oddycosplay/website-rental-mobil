<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE', 'rental');
$keyName = "Tables_in_" . $dbName;

$output = "";

foreach ($tables as $t) {
    $tableName = $t->$keyName;
    if (in_array($tableName, ['migrations', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'personal_access_tokens'])) {
        continue;
    }
    $output .= "### Table: {$tableName}\n";
    $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");
    foreach ($columns as $col) {
        $output .= "  - {$col->Field} ({$col->Type})" . ($col->Null === 'YES' ? ' nullable' : '') . ($col->Key ? " [{$col->Key}]" : "") . "\n";
    }
    
    // foreign keys
    $fks = DB::select("
        SELECT 
            COLUMN_NAME, 
            REFERENCED_TABLE_NAME, 
            REFERENCED_COLUMN_NAME 
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            TABLE_SCHEMA = ? AND 
            TABLE_NAME = ? AND 
            REFERENCED_TABLE_NAME IS NOT NULL
    ", [$dbName, $tableName]);
    if (!empty($fks)) {
        $output .= "  Foreign Keys:\n";
        foreach ($fks as $fk) {
            $output .= "    - {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }
    }
    $output .= "\n";
}

file_put_contents(__DIR__ . '/schema_output.txt', $output);
echo "Schema dumped successfully to schema_output.txt\n";
