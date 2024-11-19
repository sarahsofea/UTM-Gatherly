<?php
$servername = "localhost"; // Change if your server settings are different
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "event"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
