<?php
// edit_event.php

// Get the event details and manager credentials from query parameters
$managerName = $_GET['managerName'] ?? '';
$managerID = $_GET['managerID'] ?? '';
$eventId = $_GET['eventId'] ?? ''; // Retrieve the event ID passed

// Validate that eventId, managerName, and managerID are provided
if (empty($managerName) || empty($managerID) || empty($eventId)) {
    echo "Missing manager credentials or event ID.";
    exit;
}

// Fetch event details from database using the event ID
$event = getEventDetails($eventId);

// Check if the logged-in manager is the event owner
if ($event['ownerID'] !== $managerID) {
    echo "You are not authorized to edit or delete this event.";
    exit;
}

// If the manager is the owner, allow editing and deletion
// Process form submission for editing or deleting event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // Handle event update logic
        $title = $_POST['eventTitle'];
        $description = $_POST['eventDescription'];
        $date = $_POST['eventDate'];
        $location = $_POST['eventLocation'];

        // Update the event in the database
        updateEvent($eventId, $title, $description, $date, $location);

        echo "Event updated successfully!";
    } elseif (isset($_POST['delete'])) {
        // Handle event deletion logic
        deleteEvent($eventId);
        echo "Event deleted successfully!";
    }
}

// Fetch event details from the database
function getEventDetails($eventId) {
    // Placeholder for fetching event data
    return [
        'title' => 'Sample Event',
        'description' => 'Sample event description',
        'date' => '2024-12-01',
        'location' => 'Sample location',
        'ownerID' => '12345' // The event's owner ID
    ];
}

// Update event function (adjust for your DB schema)
function updateEvent($eventId, $title, $description, $date, $location) {
    // Perform database update logic
    // Example: UPDATE events SET title='$title', description='$description', date='$date', location='$location' WHERE eventId='$eventId';
}

// Delete event function (adjust for your DB schema)
function deleteEvent($eventId) {
    // Perform database deletion logic
    // Example: DELETE FROM events WHERE eventId='$eventId';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
</head>
<body>
    <h1>Edit Event</h1>

    <form method="POST">
        <!-- Event Title -->
        <div class="form-group">
                    <label for="eventTitle">Event Title</label>
                    <input type="text" name="eventTitle" id="eventTitle" placeholder="Enter event title" required>
                </div>

        <!-- Event Date -->
        <div class="form-group">
                    <label for="eventDate">Event Date</label>
                    <input type="date" name="eventDate" id="eventDate" required>
                </div>

        <!-- Event Time -->
        <div class="form-group">
                    <label for="eventTime">Event Time</label>
                    <input type="time" name="eventTime" id="eventTime" required>
                </div>

        <!-- Event Location -->
        <div class="form-group">
                    <label for="eventLocation">Event Location</label>
                    <input type="text" name="eventLocation" id="eventLocation" placeholder="Enter event location" required>
                </div>

        <!-- Event Description -->
        <div class="form-group">
                    <label for="eventDescription">Event Description</label>
                    <textarea name="eventDescription" id="eventDescription" placeholder="Enter event description" required></textarea>
                </div>

        <!-- Event Image (optional) -->
        <div class="form-group">
                    <label for="eventImage">Event Image</label>
                    <input type="file" name="eventImage" id="eventImage" accept="image/*">
                </div>

        <!-- Submit buttons -->
        <button type="submit" name="edit">Edit Event</button>
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete Event</button>
    </form>
</body>
</html>


