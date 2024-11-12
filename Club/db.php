<?php
$servername = "localhost";
$username = "root";       // Default username for XAMPP
$password = "";           // Default password for XAMPP (leave empty)
$dbname = "club_directory"; // Ensure this matches the database name in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
