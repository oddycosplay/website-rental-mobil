<?php
$hosts = ['127.0.0.1', '[::1]', 'localhost'];
foreach ($hosts as $host) {
    echo "Testing connection to $host:3306... ";
    try {
        $pdo = new PDO("mysql:host=$host;port=3306;dbname=rental", "root", "");
        echo "SUCCESS!\n";
    } catch (PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}
