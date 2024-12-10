<?php
$mysqli = new mysqli("localhost", "root", "", "club_directory");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if 'managerName', 'managerId', and 'managerEmail' are set in POST
    if (isset($_POST['managerName']) && isset($_POST['managerId']) && isset($_POST['managerEmail'])) {
        $managerName = $mysqli->real_escape_string($_POST['managerName']);
        $managerId = $mysqli->real_escape_string($_POST['managerId']);
        $managerEmail = $mysqli->real_escape_string($_POST['managerEmail']);
        
        // Check if any fields are empty
        if (empty($managerName) || empty($managerId) || empty($managerEmail)) {
            echo "All fields are required. Please fill in all the fields.";
        } else {
            // Validate email format
            if (!filter_var($managerEmail, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email format. Please enter a valid email.";
            } else {
                // Prepare and bind the query
                $stmt = $mysqli->prepare("INSERT INTO event_managers (name, matric_id, email) VALUES (?, ?, ?)");
                if ($stmt === false) {
                    die("Error preparing query: " . $mysqli->error);
                }
                $stmt->bind_param("sss", $managerName, $managerId, $managerEmail);

                // Execute the query
                if ($stmt->execute()) {
                    // Send success response back to the frontend
                    echo "<script> 
                            alert('Manager registered successfully. Please fill in event details.');
                        </script>";
                } else {
                    error_log("SQL Execution Error: " . $stmt->error);
                    echo "<script>alert('Error creating event.');</script>";
                }

                // Close the statement
                $stmt->close();
            }
        }
    } else {
        echo "Please ensure all fields are filled in the form: Name, Matric ID, and Email.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
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
</html>