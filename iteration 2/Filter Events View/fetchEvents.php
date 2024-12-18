<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "club_directory");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get input data from the AJAX request
$input = json_decode(file_get_contents('php://input'), true);
$categories = $input['categories'] ?? [];

// Base SQL query
$sql = "SELECT id, title, date, description, location, club_name, image, category FROM events WHERE 1=1";

// Add category filters
$params = [];
$types = '';

if (!empty($categories)) {
    $placeholders = implode(' OR category LIKE ?', array_fill(0, count($categories), '?'));
    $sql .= " AND ($placeholders)";
    foreach ($categories as $category) {
        $params[] = "%$category%";
        $types .= 's';
    }
}


// Prepare and execute the query
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);
} else {
    echo json_encode(['error' => 'Query preparation failed: ' . $conn->error]);
}

// Close connection
$conn->close();
?>
