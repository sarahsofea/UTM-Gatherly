<?php
require 'db.php'; // Replace with the path to your database connection file

if (isset($_GET['club_id'])) {
    $clubId = intval($_GET['club_id']);

    // Fetch club details
    $clubQuery = "SELECT * FROM clubs WHERE club_id = ?";
    $stmt = $conn->prepare($clubQuery);
    $stmt->bind_param('i', $clubId);
    $stmt->execute();
    $clubResult = $stmt->get_result();
    $club = $clubResult->fetch_assoc();

    // Fetch events related to the club
    $eventsQuery = "SELECT name, date FROM events WHERE club_id = ?";
    $stmt = $conn->prepare($eventsQuery);
    $stmt->bind_param('i', $clubId);
    $stmt->execute();
    $eventsResult = $stmt->get_result();
    $events = $eventsResult->fetch_all(MYSQLI_ASSOC);

    // Combine club details and events into a response
    $response = [
        'clubName' => $club['club_name'],
        'description' => $club['description'],
        'faculty' => $club['faculty'],
        'email' => $club['email'],
        'phoneNumber' => $club['phone_number'],
        'establishedYear' => $club['established_year'],
        'websiteUrl' => $club['website_url'],
        'events' => $events,
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
