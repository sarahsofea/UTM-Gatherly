<?php
// Include database connection
include 'db_connection.php'; // Modify as per your DB connection setup

// Get POST data
$managerName = $_POST['name'];
$managerID = $_POST['id'];
$eventId = $_POST['eventId'];

// Query to verify if the manager is associated with the event
$query = "SELECT * FROM event_managers WHERE event_id = ? AND name = ? AND matric_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $eventId, $managerName, $managerID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Manager is found, send success response
    echo json_encode(['success' => true]);
} else {
    // Manager not found, send failure response
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
