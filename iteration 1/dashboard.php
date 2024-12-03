<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - UTM Gatherly</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to UTM Gatherly</h1>
        <p>You are logged in!</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
