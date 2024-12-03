<?php
session_start();
require 'config.php';

$message = ''; // To display success or error messages

// Ensure the reset request is valid
if (!isset($_SESSION['reset_username'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
    $stmt->execute(['password' => $hashed_password, 'username' => $_SESSION['reset_username']]);

    // Clear the session and display a success message
    unset($_SESSION['reset_username']);
    $message = "Password has been reset successfully. <a href='login.php'>Click here to login</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - UTM Gatherly</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="reset-password-container">
        <h1>Reset Password</h1>
        <?php if (!$message): ?>
            <form action="reset_password.php" method="POST">
                <input type="password" name="password" placeholder="Enter new password" required>
                <button type="submit">Reset Password</button>
            </form>
        <?php else: ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
