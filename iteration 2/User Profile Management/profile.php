<?php
include 'db_connection.php';

$user_id = 1;

$query = "SELECT * FROM user_profiles WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();

// Check if profile exists
if (!$user_profile) {
    echo "Profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="profile-container">
    <h1>Profile</h1>
    <div class="profile-row">
        <div class="label"><i class="fas fa-user"></i> Name:</div>
        <div><?php echo htmlspecialchars($user_profile['name']); ?></div>
    </div>
    <div class="profile-row">
        <div class="label"><i class="fas fa-envelope"></i> Email:</div>
        <div><?php echo htmlspecialchars($user_profile['email']); ?></div>
    </div>
    <div class="profile-row">
        <div class="label"><i class="fas fa-phone-alt"></i> Phone:</div>
        <div><?php echo htmlspecialchars($user_profile['phone_number']); ?></div>
    </div>
    
    <a href="update_profile.php" class="action-btn">
        <i class="fas fa-edit"></i> Update Profile
    </a>
</div>

</body>
</html>
