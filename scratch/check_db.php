<?php
require_once 'config/database.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM patients LIKE 'image'");
    $column = $stmt->fetch();
    
    if (!$column) {
        $pdo->exec("ALTER TABLE patients ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER email");
        echo "Column 'image' added successfully.\n";
    } else {
        echo "Column 'image' already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
