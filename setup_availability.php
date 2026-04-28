<?php
require_once __DIR__ . '/config/database.php';

try {
    echo "Creating doctor_availability table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS doctor_availability (
        id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        day_of_week VARCHAR(15) NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        status ENUM('available', 'break') DEFAULT 'available',
        FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
    )");

    echo "Seeding default availability for existing doctors...\n";
    $doctors = $pdo->query("SELECT id FROM doctors")->fetchAll();
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    
    foreach ($doctors as $dr) {
        foreach ($days as $day) {
            // Check if already exists
            $stmt = $pdo->prepare("SELECT id FROM doctor_availability WHERE doctor_id = ? AND day_of_week = ?");
            $stmt->execute([$dr['id'], $day]);
            if ($stmt->rowCount() == 0) {
                $stmt = $pdo->prepare("INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time) VALUES (?, ?, '09:00:00', '17:00:00')");
                $stmt->execute([$dr['id'], $day]);
            }
        }
    }

    echo "Availability table setup and seeded successfully!\n";

} catch (PDOException $e) {
    die("Error setting up availability table: " . $e->getMessage() . "\n");
}
?>
