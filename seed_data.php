<?php
require_once __DIR__ . '/config/database.php';

try {
    echo "Starting fresh seeding...\n";

    // Disable foreign key checks for truncation
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Truncate tables
    $tables = ['prescriptions', 'payments', 'appointments', 'patients', 'doctors', 'departments', 'admins'];
    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE $table");
        echo "Truncated table: $table\n";
    }

    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // 1. Seed Admin
    echo "Seeding Admin...\n";
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['Super Admin', 'admin@healthcenter.com', $admin_pass]);

    // 2. Seed Departments
    echo "Seeding Departments...\n";
    $depts = ['Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics', 'General Physician', 'Dermatology'];
    foreach ($depts as $dept) {
        $stmt = $pdo->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
        $stmt->execute([$dept, "Specialized $dept department providing world-class care."]);
    }

    // 3. Seed Doctors
    echo "Seeding Doctors...\n";
    $doctor_pass = password_hash('doctor123', PASSWORD_DEFAULT);
    $doctors = [
        ['Dr. Sadiya Akter', 'doctor@healthcenter.com', $doctor_pass, '01711111111', 1, 'Cardiology', '10 years', 1000, 'Senior Cardiology Specialist'],
        ['Dr. Ashraful Islam', 'ashraf@healthcenter.com', $doctor_pass, '01711111112', 2, 'Neurology', '8 years', 1200, 'Associate Professor of Neurology'],
        ['Dr. Nasrin Sultana', 'nasrin@healthcenter.com', $doctor_pass, '01711111113', 3, 'Orthopedics', '12 years', 800, 'Senior Bone Specialist'],
    ];
    foreach ($doctors as $dr) {
        $stmt = $pdo->prepare("INSERT INTO doctors (name, email, password, phone, department_id, specialization, experience, fee, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($dr);
    }

    // 4. Seed Patients
    echo "Seeding Patients...\n";
    $patient_pass = password_hash('patient123', PASSWORD_DEFAULT);
    $patients = [
        ['John Doe', 'patient@healthcenter.com', $patient_pass, '01811111111', 'Male', '1990-05-15', '123 Medical Way, Dhaka'],
        ['Jane Smith', 'jane@healthcenter.com', $patient_pass, '01811111112', 'Female', '1995-10-20', '456 Health St, Chittagong'],
    ];
    foreach ($patients as $pt) {
        $stmt = $pdo->prepare("INSERT INTO patients (name, email, password, phone, gender, dob, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($pt);
    }

    // 5. Seed Appointments
    echo "Seeding Appointments...\n";
    $appointments = [
        [1, 1, date('Y-m-d'), '10:00:00', 'pending', 'Heart checkup'],
        [2, 2, date('Y-m-d'), '11:30:00', 'approved', 'Neurology consultation'],
        [1, 3, date('Y-m-d', strtotime('+1 day')), '09:45:00', 'approved', 'Orthopedic follow-up'],
    ];
    foreach ($appointments as $app) {
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute($app);
    }

    // 6. Seed Payments
    echo "Seeding Payments...\n";
    $stmt = $pdo->prepare("INSERT INTO payments (appointment_id, patient_id, amount, status, transaction_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([2, 2, 1200, 'paid', 'TXN' . time()]);

    // 7. Seed Prescriptions
    echo "Seeding Prescriptions...\n";
    $medicines = json_encode([
        ['name' => 'Napa Extend', 'dosage' => '1+0+1', 'duration' => '3 days'],
        ['name' => 'Seclo 20mg', 'dosage' => '1+0+1', 'duration' => '7 days']
    ]);
    $stmt = $pdo->prepare("INSERT INTO prescriptions (appointment_id, patient_id, doctor_id, diagnosis, medicines, advice) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([2, 2, 2, 'Mild Migraine', $medicines, 'Rest well and avoid loud noises.']);

    echo "\nFresh Seeding completed successfully!\n";
    echo "--------------------------------------------------\n";
    echo "CREDENTIALS SUMMARY:\n";
    echo "Admin:   admin@healthcenter.com   | admin123\n";
    echo "Doctor:  doctor@healthcenter.com  | doctor123\n";
    echo "Patient: patient@healthcenter.com | patient123\n";
    echo "--------------------------------------------------\n";

} catch (PDOException $e) {
    die("Error during seeding: " . $e->getMessage() . "\n");
}
