<?php
// edit_event.php

// Include database connection (replace with your DB connection code)
require_once 'db_connection.php';

// Get query parameters
$managerName = $_GET['managerName'] ?? '';
$managerID = $_GET['managerID'] ?? '';
$eventId = $_GET['eventId'] ?? '';

// Validate inputs
if (empty($managerName) || empty($managerID) || empty($eventId)) {
    echo json_encode(['success' => false, 'message' => 'Missing credentials or event ID']);
    exit;
}

// Fetch event details
$event = getEventDetails($eventId);
if (!$event) {
    echo json_encode(['success' => false, 'message' => 'Event not found']);
    exit;
}

// Verify if the manager owns the event
if ($event['managerID'] !== $managerID) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Handle POST requests for updating or deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'edit') {
        $title = $_POST['eventTitle'];
        $date = $_POST['eventDate'];
        $time = $_POST['eventTime'];
        $location = $_POST['eventLocation'];
        $description = $_POST['eventDescription'];

        if (updateEvent($eventId, $title, $description, $date, $time, $location)) {
            echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update event']);
        }
    } elseif ($action === 'delete') {
        if (deleteEvent($eventId)) {
            echo json_encode(['success' => true, 'message' => 'Event deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete event']);
        }
    }
    exit;
}

// Function to fetch event details
function getEventDetails($eventId) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to update an event
function updateEvent($eventId, $title, $description, $date, $time, $location) {
    global $db;
    $stmt = $db->prepare("UPDATE events SET title = ?, description = ?, date = ?, time = ?, location = ? WHERE id = ?");
    return $stmt->execute([$title, $description, $date, $time, $location, $eventId]);
}

// Function to delete an event
function deleteEvent($eventId) {
    global $db;
    $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
    return $stmt->execute([$eventId]);
}
