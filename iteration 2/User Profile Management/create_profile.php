<?php
session_start();
include 'db_connection.php'; // your database connection file

// Check if the user is logged in (just a placeholder, no actual login validation in this case)
if (!isset($_SESSION['user_id'])) {
    // If no session, redirect to some login or a dummy user ID for testing
    $_SESSION['user_id'] = 1; // Assuming user_id is 1 for testing purposes
}

$user_id = $_SESSION['user_id'];

// Check if the profile already exists
$query = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();

if ($user_profile) {
    // If profile already exists, redirect to update page
    header('Location: update_profile.php');
    exit();
}

// Handle profile creation form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Insert profile data into the database
    $insert_query = "INSERT INTO user_profiles (user_id, name, email, phone_number) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isss", $user_id, $name, $email, $phone_number);
    $stmt->execute();

    // Redirect to profile page after creation
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Create Your Profile</h1>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br><br>
        
        <button type="submit">Create Profile</button>
    </form>
</body>
</html>
