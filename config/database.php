<?php
// Database configuration
define('DB_HOST', 'localhost');     // Change this to your MySQL host
define('DB_USER', 'your_username'); // Change this to your MySQL username
define('DB_PASS', 'your_password'); // Change this to your MySQL password
define('DB_NAME', 'meowtopia');     // Change this to your database name

// Create database connection
function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?> 