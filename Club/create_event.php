<?php
header('Content-Type: text/html'); // Set header to text/html for JavaScript output

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
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
    // Retrieve form data
    $eventTitle = $_POST['eventTitle'] ?? '';
    $eventDate = $_POST['eventDate'] ?? '';
    $eventTime = $_POST['eventTime'] ?? '';
    $eventLocation = $_POST['eventLocation'] ?? '';
    $eventDescription = $_POST['eventDescription'] ?? '';
    $eventOrganizer = $_POST['eventOrganizer'] ?? '';

    // Validate required fields
    if (empty($eventTitle) || empty($eventDate) || empty($eventTime) || empty($eventLocation)) {
        echo "<script>alert('All required fields must be filled.');</script>";
        exit;
    }

    // Handle file upload
    $eventImage = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the uploads directory if it doesn't exist
        }

        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $eventImage = $imagePath; // Store the path of the uploaded image
        } else {
            echo "<script>alert('There was an error uploading your file.');</script>";
            exit;
        }
    }

    // Prepare SQL statement to insert event details into the database
    $stmt = $mysqli->prepare("INSERT INTO events (title, date, time, location, description, image, organizer) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "<script>alert('Prepare failed: " . $mysqli->error . "');</script>";
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param("sssssss", $eventTitle, $eventDate, $eventTime, $eventLocation, $eventDescription, $eventImage, $eventOrganizer);

    if ($stmt->execute()) {
        // Display success alert and redirect
        echo "<script>
            alert('Event successfully created.');
            window.location.href = 'viewEvents.php';
        </script>";
    } else {
        echo "<script>alert('Error creating event: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "<script>alert('Invalid request method.');</script>";
}
?>
