<?php
// Database connection (replace with your own credentials)
$host = 'localhost'; // Database host
$dbname = 'club_directory'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password (empty for localhost)
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if matric number exists in POST data
    if (isset($_POST['matric_number']) && !empty($_POST['matric_number'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $matric = $_POST['matric_number']; // Change to matric_number
        $email = $_POST['email'];
        $faculty = $_POST['faculty'];

        // Check if the matric already exists in the registrations table
        $query = "SELECT * FROM registrations WHERE matric = :matric"; 
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':matric', $matric);
        $stmt->execute();
        
        // If the matric number exists, update the record
        if ($stmt->rowCount() > 0) {
            // Matric exists, update the record
            $updateQuery = "UPDATE registrations 
                            SET name = :name, email = :email, faculty = :faculty 
                            WHERE matric = :matric"; 
            
            try {
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':name', $name);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->bindParam(':faculty', $faculty);
                $updateStmt->bindParam(':matric', $matric);

                if ($updateStmt->execute()) {
                    // Send success message to JavaScript alert and redirect
                    echo "<script type='text/javascript'>
                            alert('Registration updated successfully!');
                            window.location.href = document.referrer; // Redirect to the previous page
                          </script>";
                } else {
                    echo "Error updating registration.";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        } else {
            // If the matric number doesn't exist, insert a new record
            $insertQuery = "INSERT INTO registrations (name, matric, email, faculty) 
                            VALUES (:name, :matric, :email, :faculty)";
            
            try {
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->bindParam(':name', $name);
                $insertStmt->bindParam(':matric', $matric);
                $insertStmt->bindParam(':email', $email);
                $insertStmt->bindParam(':faculty', $faculty);

                if ($insertStmt->execute()) {
                    // Send success message to JavaScript alert and redirect
                    echo "<script type='text/javascript'>
                            alert('Registration successful!');
                            window.location.href = document.referrer; // Redirect to the previous page
                          </script>";
                } else {
                    echo "Error registering.";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    } else {
        echo "Matric number is required!";
    }
}
?>
