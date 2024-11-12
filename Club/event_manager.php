<?php
header('Content-Type: application/json'); // Ensure JSON response

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'club_directory';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $managerName = $_POST['managerName'] ?? '';
    $managerId = $_POST['managerId'] ?? '';
    $managerEmail = $_POST['managerEmail'] ?? '';

    if (empty($managerName) || empty($managerId) || empty($managerEmail)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Use the new column names: managerName, managerId, email
    $stmt = $mysqli->prepare("INSERT INTO event_managers (managerName, managerId, email) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param("sss", $managerName, $managerId, $managerEmail);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful. Proceed to add event.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while registering: ' . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
