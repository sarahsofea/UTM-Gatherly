<?php
$conn = new mysqli("localhost", "root", "", "club_directory");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$eventID = $conn->real_escape_string($data['eventID']);

$sql = "DELETE FROM events WHERE id = '$eventID'";
if ($conn->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>
