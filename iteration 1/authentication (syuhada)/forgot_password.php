<?php
require 'config.php';

$message = ''; // To display feedback messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];

    // Check if the email and username combination exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // If valid, store the username in session and redirect to password reset
        session_start();
        $_SESSION['reset_username'] = $username;
        header("Location: reset_password.php");
        exit();
    } else {
        $message = "Invalid email or username.";
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
    <div class="reset-password-container">
        <h1>Forgot Password</h1>
        <form action="forgot_password.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="text" name="username" placeholder="Enter your username" required>
            <button type="submit">Verify</button>
        </form>
        <?php if ($message): ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
