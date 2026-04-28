# Health Center Management System

A premium, modern medical portal designed for 2025. This project provides a comprehensive solution for managing patients, doctors, appointments, and prescriptions with a high-end "White Theme" aesthetic.

## 🚀 Getting Started

### Prerequisites
- PHP 8.x
- MySQL / MariaDB
- Apache or any PHP-compatible web server

### Setup Instructions

1.  **Clone the project** to your local server directory.
2.  **Configure Database**:
    - Ensure your MySQL server is running.
    - Open `config/database.php` and verify your database connection settings (host, user, pass).
3.  **Initialize Database & Seed Data**:
    - Open your terminal in the project root.
    - Run the following commands to create the database and populate it with fresh data:
      ```bash
      php setup_db.php
      php seed_data.php
      ```
4.  **Start the Server**:
    - You can use the built-in PHP server for local development:
      ```bash
      php -S localhost:8000
      ```
5.  **Access the Portal**:
    - Open your browser and navigate to `http://localhost:8000`.

## 🔑 Login Credentials (Seed Data)

The following accounts are created automatically during the seeding process:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@healthcenter.com` | `admin123` |
| **Doctor** | `doctor@healthcenter.com` | `doctor123` |
| **Patient** | `patient@healthcenter.com` | `patient123` |

## 🛠️ Project Structure

- `/admin`: Management dashboard for hospital administrators.
- `/doctor`: Specialized panel for doctors to manage schedules and prescriptions.
- `/patients`: Patient-facing portal for booking and profile management.
- `/config`: Database and global configurations.
- `/database`: SQL schema and database-related files.
- `/assets`: CSS, JS, and image assets for the entire platform.

## ✨ Features
- **Premium 2025 UI**: Glassmorphism, fluid animations, and high-contrast design.
- **Dynamic Appointments**: Real-time scheduling and status tracking.
- **Prescription Management**: Digital prescriptions with JSON-based storage.
- **Responsive Design**: Optimized for all devices from desktop to mobile.

---
*Created with ❤️ for Health Center Excellence.*
# doctor_appoinment_system_php
