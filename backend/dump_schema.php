<?php
$host = '127.0.0.1';
$port = '3306';
$db   = 'rental';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    $stmt = $pdo->query("
        SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, IS_NULLABLE, COLUMN_DEFAULT
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = 'rental'
        ORDER BY TABLE_NAME, ORDINAL_POSITION
    ");
    
    $tables = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tables[$row['TABLE_NAME']][] = $row;
    }
    
    $output = "";
    foreach ($tables as $tableName => $columns) {
        $output .= "Table: $tableName\n";
        foreach ($columns as $col) {
            $len = $col['CHARACTER_MAXIMUM_LENGTH'] ? "({$col['CHARACTER_MAXIMUM_LENGTH']})" : "";
            $nullable = $col['IS_NULLABLE'] === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $col['COLUMN_DEFAULT'] !== null ? "DEFAULT '{$col['COLUMN_DEFAULT']}'" : "";
            $output .= "  - {$col['COLUMN_NAME']} {$col['DATA_TYPE']}{$len} {$nullable} {$default}\n";
        }
        $output .= "\n";
    }
    
    file_put_contents('schema_dump.txt', $output);
    echo "Saved to schema_dump.txt\n";
} catch (PDOException $e) {
    echo "PDO Error: " . $e->getMessage() . "\n";
}
