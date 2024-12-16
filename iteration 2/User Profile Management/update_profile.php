<?php
include 'db_connection.php';


$user_id = 1;

// Fetch user profile
$query = "SELECT * FROM user_profiles WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_profile = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $update_query = "UPDATE user_profiles SET name = ?, email = ?, phone_number = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $name, $email, $phone_number, $user_id);
    $update_stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="profile-container">
        <h1>Update Profile</h1>

        <form method="POST" action="update_profile.php">
            <div class="profile-row">
                <div class="label"><i class="fas fa-user"></i> Name:</div>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user_profile['name']); ?>" required>
            </div>

            <div class="profile-row">
                <div class="label"><i class="fas fa-envelope"></i> Email:</div>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user_profile['email']); ?>" required>
            </div>

            <div class="profile-row">
                <div class="label"><i class="fas fa-phone-alt"></i> Phone:</div>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user_profile['phone_number']); ?>" required>
            </div>

            <div class="profile-row">
                <button type="submit" class="update-btn"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
