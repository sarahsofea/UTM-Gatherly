<?php
// Database connection
$servername = "localhost"; // or the database server IP
$username = "root";        // your database username
$password = "";            // your database password
$dbname = "club_directory";   // your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$title = $_POST['title'];
$description = $_POST['description'];
$date = $_POST['date'];
$time = $_POST['time'];
$location = $_POST['location'];
$organizer = $_POST['organizer'];

// Handle the file upload (Image)
$image = $_FILES['image']['name'];
$imageTmpName = $_FILES['image']['tmp_name'];
$imageSize = $_FILES['image']['size'];
$imageError = $_FILES['image']['error'];
$imageExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));

// Check for image upload errors
if ($imageError === 0) {
    // Create a unique name for the image
    $imageNewName = uniqid('', true) . '.' . $imageExt;
    $imageDestination = 'images/' . $imageNewName;

    // Move the uploaded file to the target directory
    move_uploaded_file($imageTmpName, $imageDestination);
} else {
    echo "Error uploading image.";
    exit;
}

// Insert event data into the database
$sql = "INSERT INTO events (title, description, date, time, location, organizer, image) 
        VALUES ('$title', '$description', '$date', '$time', '$location', '$organizer', '$imageDestination')";

if ($conn->query($sql) === TRUE) {
    echo "New event created successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
