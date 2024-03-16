<?php
// Database configuration
$host = 'db';
$user = 'admin'; 
$password = 'admin123'; 
$database = 'bgp_database'; 

// Establishing the connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: Set character set to utf8 (if needed)
mysqli_set_charset($conn, 'utf8');


?>
