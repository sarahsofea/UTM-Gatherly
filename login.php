<?php
session_start();
require 'config.php'; // Include your database connection settings

$error = ''; // Variable to store any error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form was submitted
    $username = $_POST['username']; // Get the username from the form
    $password = $_POST['password']; // Get the password from the form

    // Prepare SQL statement to fetch the user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(); // Fetch the user record

    // Check if the user exists
    if ($user) {
        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Set the user ID in session
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $error = "Invalid password."; // If password doesn't match
        }
    } else {
        $error = "Invalid username."; // If username is not found
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - UTM Gatherly</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1>UTM Gatherly</h1>
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="input-container">
                <span class="icon">&#128100;</span>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <span class="icon">&#128274;</span>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <p><a href="password/forgot_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>

