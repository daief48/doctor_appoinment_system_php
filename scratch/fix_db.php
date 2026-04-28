<?php
require_once 'config/database.php';
try {
    $pdo->exec("ALTER TABLE doctors ADD COLUMN signature_image VARCHAR(255) AFTER profile_image");
    echo "Successfully added signature_image column.\n";
} catch (PDOException $e) {
    if ($e->getCode() == '42S21') {
        echo "Column already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>
