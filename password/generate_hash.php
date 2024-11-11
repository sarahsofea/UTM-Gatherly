<?php
// This script hashes a given password
$password = 'pija123'; // Example password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword; // Output the hashed password
?>
