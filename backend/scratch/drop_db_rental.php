<?php
try {
    // Connect to MySQL server (without specifying database, since we want to drop db_rental)
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if db_rental exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'db_rental'");
    $exists = $stmt->fetch();
    
    if ($exists) {
        $pdo->exec("DROP DATABASE db_rental");
        echo "SUCCESS: Database 'db_rental' has been successfully dropped.\n";
    } else {
        echo "INFO: Database 'db_rental' does not exist.\n";
    }
} catch (PDOException $e) {
    echo "ERROR: Could not complete operation. Details: " . $e->getMessage() . "\n";
}
