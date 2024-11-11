<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - <?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css" >
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>
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

