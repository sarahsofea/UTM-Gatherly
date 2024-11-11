<?php
require 'config.php';

$token = $_GET['token'] ?? ''; // Get the token from the URL, if it exists
$message = ''; // Message to display after reset

// If a token is provided, check its validity
if ($token) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    // If the token is valid, allow password reset
    if ($user && $_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL WHERE reset_token = :token");
        $stmt->execute(['password' => $new_password, 'token' => $token]);

        // Display success message and redirect to login
        $message = "Password has been reset successfully. <a href='login.php'>Click here to login</a>";
    } elseif (!$user) {
        // Redirect if token is invalid
        header("Location: forgot_password.php");
        exit();
    }
} else {
    // Redirect if no token is provided
    header("Location: forgot_password.php");
    exit();
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

        <!-- Display reset form only if no message (message only appears after reset) -->
        <?php if (!$message): ?>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <input type="password" name="password" placeholder="Enter new password" required>
                <button type="submit">Reset Password</button>
            </form>
        <?php else: ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
