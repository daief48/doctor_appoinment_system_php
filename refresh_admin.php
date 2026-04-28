<?php
require_once __DIR__ . '/config/database.php';

try {
    $email = 'admin@healthcenter.com';
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Clear existing admins (optional, but ensures only one)
    $pdo->exec("DELETE FROM admins");

    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['Super Admin', $email, $hashed_password]);

    echo "Admin credentials refreshed successfully!\n";
    echo "Email: $email\n";
    echo "Password: $password\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
