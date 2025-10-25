<?php
$host = "localhost";
$user = "root";
$pass = "root"; 
$dbname = "aklny";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
