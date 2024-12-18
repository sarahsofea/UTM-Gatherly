<?php
// Include database connection
require 'db.php';

if (isset($_GET['eventId'])) {
    $eventId = intval($_GET['eventId']); // Sanitize input

    // Query to fetch event details
    $query = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'eventId' => $event['id'],
            'title' => $event['title'],
            'date' => $event['date'],
            'time' => $event['time'],
            'location' => $event['location'],
            'description' => $event['description']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Event not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
