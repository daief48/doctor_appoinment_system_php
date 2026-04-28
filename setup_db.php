<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database (Fresh)
    echo "Dropping and recreating database...\n";
    $pdo->exec("DROP DATABASE IF EXISTS health_center_db");
    $pdo->exec("CREATE DATABASE health_center_db");
    $pdo->exec("USE health_center_db");

    // Read and execute schema.sql
    $sql = file_get_contents(__DIR__ . '/database/schema.sql');
    
    // The schema.sql already contains CREATE DATABASE and USE, but we'll execute it anyway
    // Splitting by semicolon to execute multiple queries (basic way)
    // Note: This simple split might fail if there are semicolons in strings/comments, 
    // but for this schema it should work.
    
    echo "Executing schema...\n";
    $pdo->exec($sql);

    echo "Database setup successful!\n";
    echo "Admin Login: admin@healthcenter.com / admin123\n";

} catch (PDOException $e) {
    die("Error setting up database: " . $e->getMessage() . "\n");
}
?>
