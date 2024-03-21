<?php
// Database configuration
$host = 'db';
$dbname = 'bgp_database';
$username = 'admin';
$password = 'admin123';

// Establish a database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set the default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle database connection error
    die("Database connection failed: " . $e->getMessage());
}

// Optional: Set character set to utf8 (if needed)
$pdo->exec("SET NAMES 'utf8'");
$pdo->exec("SET CHARACTER SET utf8");
$pdo->exec("SET SESSION collation_connection = 'utf8_unicode_ci'");
?>
