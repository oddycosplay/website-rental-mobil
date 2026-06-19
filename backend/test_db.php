<?php
$host = '127.0.0.1';
$port = '3306';
$db   = 'rental';
$user = 'root';
$pass = '';

try {
    echo "Connecting to MySQL at $host:$port...\n";
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 2, // 2 seconds timeout
    ]);
    echo "Successfully connected!\n";
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "Table: " . $row[0] . "\n";
    }
} catch (PDOException $e) {
    echo "PDO Error: " . $e->getMessage() . "\n";
}
