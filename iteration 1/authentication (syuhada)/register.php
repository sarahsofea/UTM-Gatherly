<?php
session_start();
require 'config.php'; // Include database connection

$error = ''; // Variable to store error messages
$success = ''; // Variable to store success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // Get the username from the form
    $email = $_POST['email']; // Get the email from the form
    $password = $_POST['password']; // Get the password from the form

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert the new user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    
    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password]);
        $success = "Thank you for registering! <a href='login.php'>Click here to login</a>";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage(); // Handle any errors (e.g., duplicate username/email)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UTM Gatherly</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to UTM Gatherly!</h1>
        <h2>Join the Community of Innovators!</h2>
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        
        <!-- Display the success message -->
        <p class="success">
            <?php if ($success): ?>
                <?php echo $success; ?>
            <?php endif; ?>
        </p>
        
        <!-- Display the error message -->
        <p class="error">
            <?php if ($error): ?>
                <?php echo $error; ?>
            <?php endif; ?>
        </p>
    </div>
</body>
</html>
