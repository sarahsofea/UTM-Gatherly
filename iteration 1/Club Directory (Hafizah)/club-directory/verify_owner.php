<?php
// verify_owner.php

// Get the data from the request body
$data = json_decode(file_get_contents('php://input'), true);

$managerName = $data['managerName'];
$managerID = $data['managerID'];
$eventId = $data['eventId'];

// Validate manager credentials and check if they are the event owner
// Assuming we have a database connection established

// Fetch event details to check the event owner
$event = getEventDetails($eventId);

// Check if the manager is the owner (owner is stored in the event's "ownerID" field)
if ($event['ownerID'] === $managerID) {
    // If the manager is the owner, allow access
    echo json_encode(['isOwner' => true]);
} else {
    // If the manager is not the owner, deny access
    echo json_encode(['isOwner' => false]);
}

// Function to get event details
function getEventDetails($eventId) {
    // Example query: SELECT * FROM events WHERE eventId = $eventId
    // Assuming you have a function to execute the query and fetch event data from the database
    return [
        'title' => 'Sample Event',
        'description' => 'Sample event description',
        'date' => '2024-12-01',
        'location' => 'Sample location',
        'ownerID' => '12345' // This is the event owner's ID (should come from the DB)
    ];
}
?>
