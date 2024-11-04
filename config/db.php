<?php
$host = 'localhost';          // Database host
$dbname = 'gatherly';     // Database name
$username = 'root';   // Database username
$password = '';   // Database password

// Create the MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";