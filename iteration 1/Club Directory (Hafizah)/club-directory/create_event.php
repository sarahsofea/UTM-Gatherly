<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'club_directory';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    echo "<script>alert('Database connection failed: " . $mysqli->connect_error . "');</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Display some information to check if POST data is correctly sent
    echo "<script>alert('Form submitted successfully.');</script>";

    // Collect the form data
    $eventTitle = $_POST['eventTitle'] ?? '';
    $eventDate = $_POST['eventDate'] ?? '';
    $eventTime = $_POST['eventTime'] ?? '';
    $eventLocation = $_POST['eventLocation'] ?? '';
    $eventDescription = $_POST['eventDescription'] ?? '';
    $eventOrganizer = $_POST['eventOrganizer'] ?? '';

    // Check for required fields
    if (empty($eventTitle) || empty($eventDate) || empty($eventTime) || empty($eventLocation)) {
        echo "<script>alert('All required fields must be filled.');</script>";
        exit;
    }

    // Handle file upload
    $eventImage = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            echo "<script>alert('Invalid file type. Only JPG, PNG, and GIF files are allowed.');</script>";
            exit;
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            echo "<script>alert('File size exceeds the 5MB limit.');</script>";
            exit;
        }

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $eventImage = $imagePath;
        } else {
            echo "<script>alert('Error uploading the file.');</script>";
            exit;
        }
    }

    // Prepare and insert data into database
    $stmt = $mysqli->prepare("INSERT INTO events (title, date, time, location, description, image, organizer) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $mysqli->error);
        echo "<script>alert('Please try again later.');</script>";
        exit;
    }

    $stmt->bind_param("sssssss", $eventTitle, $eventDate, $eventTime, $eventLocation, $eventDescription, $eventImage, $eventOrganizer);
    if ($stmt->execute()) {
        echo "<script>
            alert('Event successfully created.');
            window.location.href = 'viewEvents.php';  // Redirect to the view events page after successful event creation
        </script>";
    } else {
        error_log("SQL Execution Error: " . $stmt->error);
        echo "<script>alert('Error creating event.');</script>";
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "<script>alert('Invalid request method.');</script>";
}
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="create_event.css">
</head>
<body>
    <div class="form-container">
        <h2>Create Event</h2>
        <form action="create_event.php" method="POST" enctype="multipart/form-data">
            <label for="eventTitle">Event Title:</label>
            <input type="text" name="eventTitle" id="eventTitle" required>

            <label for="eventDate">Event Date:</label>
            <input type="date" name="eventDate" id="eventDate" required>

            <label for="eventTime">Event Time:</label>
            <input type="time" name="eventTime" id="eventTime" required>

            <label for="eventLocation">Event Location:</label>
            <input type="text" name="eventLocation" id="eventLocation" required>

            <label for="eventDescription">Event Description:</label>
            <textarea name="eventDescription" id="eventDescription"></textarea>

            <label for="eventOrganizer">Event Organizer:</label>
            <input type="text" name="eventOrganizer" id="eventOrganizer">

            <label for="image">Event Image:</label>
            <input type="file" name="image" id="image" accept="image/*">

            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html> -->
