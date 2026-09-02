<?php
/**
 * Database connection (PDO + MySQL / XAMPP).
 * Every PHP page that needs the database includes this file:
 *   require_once __DIR__ . '/../config/db.php';
 */

$DB_HOST = 'localhost';
$DB_NAME = 'city_care_hospital';
$DB_USER = 'root';
$DB_PASS = '';   // default XAMPP password is empty — change here if yours is different

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed. Please make sure XAMPP\'s MySQL is running and the '
        . '"city_care_hospital" database has been imported (see database/schema.sql). '
        . 'Error: ' . htmlspecialchars($e->getMessage()));
}
