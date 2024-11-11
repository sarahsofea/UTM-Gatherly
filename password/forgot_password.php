<?php
session_start();
require 'config.php';

$message = ''; // Message to display to the user
$showForm = true; // Flag to control form visibility

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Prepare the SQL statement to check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a reset token and store it in the database
        $token = bin2hex(random_bytes(16)); // Generate a random token
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token WHERE email = :email");
        $stmt->execute(['token' => $token, 'email' => $email]);

        // Success message, hide form
        $message = "A password reset link has been sent to your email. <a href='reset_password.php?token=$token'>Click here to reset your password</a>";
        $showForm = false; // Hide the form after sending the reset link
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - UTM Gatherly</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>

        <?php if ($showForm): ?>
            <!-- Show the form only if reset link hasn't been sent -->
            <form action="forgot_password.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Send Reset Link</button>
            </form>
        <?php else: ?>
            <!-- Display success message when reset link is sent -->
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>

        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
