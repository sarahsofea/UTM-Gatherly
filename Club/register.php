<?php

include 'db.php';

// Database connection and form processing logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $matric = $_POST['matric'];
    $email = $_POST['email'];
    $faculty = $_POST['faculty'];


    // Insert registration data into a table (replace table name and column names)
    $sql = "INSERT INTO registrations (name, matric, email, faculty) VALUES ('$name', '$matric', '$email', '$faculty')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
