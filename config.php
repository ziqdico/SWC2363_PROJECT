<?php
// Database configuration
$host = "localhost";      // Database host
$db_name = "movietheatre"; // Database name
$username = "root";       // Database username
$password = "";           // Database password


$con = new mysqli($host, $username, $password, $db_name);


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
