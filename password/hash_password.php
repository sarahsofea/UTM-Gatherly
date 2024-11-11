<?php
// Set your plain password here
$password = 'yourPlainTextPassword'; // Change this to your desired password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Display the hashed password
echo "Hashed Password: " . $hashed_password;
?>
