<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "club_directory");

// Check the database connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if `Id` is provided to fetch a specific event
if (isset($_GET['Id']) && !empty($_GET['Id'])) {
    // Sanitize input
    $Id = intval($_GET['Id']); 
    $sql = "SELECT Id, title, date, time, location, description, image FROM events WHERE Id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $Id); // `i` for integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $event = $result->fetch_assoc();
            echo json_encode($event);
        } else {
            echo json_encode(['error' => 'Event not found.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
    }
} else {
    // Fetch all events if no specific `Id` is provided
    $sql = "SELECT Id, title, date, time, location, description, image FROM events";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode($events);
    } else {
        echo json_encode(['error' => 'No events found.']);
    }
}

// Close the connection
$conn->close();
?>
