<?php
// Enable error reporting to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
if (file_exists('db.php')) {
    include('db.php');
} else {
    die('Database connection file not found!');
}

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get data from the form
$managerName = $_POST['managerName'];
$managerId = $_POST['managerId'];

// Sanitize inputs to avoid SQL injection
$managerName = mysqli_real_escape_string($conn, $managerName);
$managerId = mysqli_real_escape_string($conn, $managerId);

// Prepare SQL query to check if the name and matric ID exist
$sql = "SELECT * FROM event_managers WHERE managerName = ? AND managerId = ?";
$stmt = $conn->prepare($sql);

// Check if prepare failed
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("ss", $managerName, $managerId);
$stmt->execute();
$result = $stmt->get_result();

// If there's a match, allow access to the event edit page
if ($result->num_rows > 0) {
    echo "Verification successful. You can now edit the event.";
    // Redirect to the edit event page (optional, depending on your flow)
    // header("Location: editEvent.php"); // Uncomment to redirect
} else {
    // If no match, show an error
    echo "Verification failed. Please enter valid credentials.";
}
?>

