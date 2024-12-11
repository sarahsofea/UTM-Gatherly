<?php
// Database connection (ensure this is correct for your setup)
$conn = new mysqli("localhost", "root", "", "club_directory");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current month
$currentMonth = date('m');

// Fetch events from the database
$sql = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);

// Separate events into two categories: Current month and Future events
$currentMonthEvents = [];
$futureEvents = [];

while ($row = $result->fetch_assoc()) {
    $eventMonth = date('m', strtotime($row['date']));
    
    if ($eventMonth == $currentMonth) {
        $currentMonthEvents[] = $row;
    } else {
        $futureEvents[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    
</head>
<body>

<!-- Current Month Events -->
<section class="upcoming-events">
    <h3>Upcoming Events for This Month</h3>
    <div class="container">
        <?php
        // Display events for the current month
        foreach ($currentMonthEvents as $event) {
            echo '
            <div class="card">
                <img src="' . htmlspecialchars($event['image']) . '" alt="Event Image">
                <h4 class="card-title">' . htmlspecialchars($event['title']) . '</h4>
                <p><strong>' . htmlspecialchars($event['date']) . ' at ' . htmlspecialchars($event['time']) . '</strong></p>
                <div class="btn-container">
                    <a href="javascript:void(0);" class="btn-secondary" onclick="showModal(
                        \'' . htmlspecialchars($event['title'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['date'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['time'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['location'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['organizer'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['description'], ENT_QUOTES) . '\')">Learn More</a>
                    <a href="javascript:void(0);" class="btn-primary" onclick="showRegisterModal()">Register</a>
                </div>
            </div>';
        }
        ?>
    </div>
</section>

<!-- Future Events -->
<section class="upcoming-events">
    <h3>All Events</h3>
    <div class="container">
        <?php
        // Display future events
        foreach ($futureEvents as $event) {
            echo '
            <div class="card">
                <img src="' . htmlspecialchars($event['image']) . '" alt="Event Image">
                <h4 class="card-title">' . htmlspecialchars($event['title']) . '</h4>
                <p><strong>' . htmlspecialchars($event['date']) . ' at ' . htmlspecialchars($event['time']) . '</strong></p>
                <div class="btn-container">
                    <a href="javascript:void(0);" class="btn-secondary" onclick="showModal(
                        \'' . htmlspecialchars($event['title'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['date'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['time'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['location'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['organizer'], ENT_QUOTES) . '\', 
                        \'' . htmlspecialchars($event['description'], ENT_QUOTES) . '\')">Learn More</a>
                    <a href="javascript:void(0);" class="btn-primary" onclick="showRegisterModal()">Register</a>
                </div>
            </div>';
        }
        ?>
    </div>
</section>

<!-- Updated Learn More Modal -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle"></h2>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Time:</strong> <span id="modalTime"></span></p>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <p><strong>Organizer:</strong> <span id="modalOrganizer"></span></p>
        <p><strong>Description:</strong> <span id="modalDescription"></span></p>
        
        <!-- Edit icon for event managers -->
        <span class="edit-icon" onclick="showEditModal()">
            🖉 <!-- Or use an icon library for this -->
        </span>
    </div>
</div>

<!-- Edit/Delete Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h2>Edit or Delete Event</h2>

        <!-- Manager Verification Form -->
        <form id="managerVerificationForm" onsubmit="event.preventDefault()">
            <div class="form-group">
                <label for="managerName">Manager Name</label>
                <input type="text" id="managerName" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="managerID">Matric ID</label>
                <input type="text" id="managerID" placeholder="Enter your matric ID" required>
            </div>
            <button type="button" onclick="verifyManager()">Verify and Edit</button>
        </form>

        <!-- Event Edit Form (hidden initially) -->
        <form id="eventEditForm" style="display: none;" onsubmit="handleEventSubmit(event)">
            <div class="form-group">
                <label for="eventTitle">Event Title</label>
                <input type="text" id="eventTitle" placeholder="Enter event title" required>
            </div>
            <div class="form-group">
                <label for="eventDescription">Event Description</label>
                <textarea id="eventDescription" placeholder="Enter event description" required></textarea>
            </div>
            <div class="form-group">
                <label for="eventDate">Event Date</label>
                <input type="date" id="eventDate" required>
            </div>
            <div class="form-group">
                <label for="eventLocation">Event Location</label>
                <input type="text" id="eventLocation" placeholder="Enter event location" required>
            </div>
            
            <!-- Submit and Delete buttons -->
            <button type="submit">Edit Event</button>
            <button type="button" onclick="deleteEvent()">Delete Event</button>
        </form>
    </div>
</div>


<!-- Register Modal -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeRegisterModal()">&times;</span>
        <h2>Event Registration</h2>
        <form action="register.php" method="POST" class="register-form">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="matric_number">Matric Number</label>
                <input type="text" name="matric_number" placeholder="Enter your matric number" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" name="faculty" placeholder="Enter your faculty" required>
            </div>

            <input type="submit" value="Register" class="btn-primary">
        </form>
    </div>
</div>

<script>
    // Modal functionality
    function showModal(title, date, time, location, organizer, description) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalTime').innerText = time;
        document.getElementById('modalLocation').innerText = location;
        document.getElementById('modalOrganizer').innerText = organizer;
        document.getElementById('modalDescription').innerText = description;
        document.getElementById('eventModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('eventModal').style.display = "none";
    }

    function showRegisterModal() {
        document.getElementById('registerModal').style.display = "block";
    }

    function closeRegisterModal() {
        document.getElementById('registerModal').style.display = "none";
    }

    // Show the Edit/Delete Modal
    function showEditModal() {
        document.getElementById('editModal').style.display = "block";
    }

    // Close the Edit/Delete Modal
    function closeEditModal() {
        document.getElementById('editModal').style.display = "none";
    }

    // Verify Manager Function
    function verifyManager() {
        const name = document.getElementById('managerName').value;
        const matricID = document.getElementById('managerMatricID').value;

        // Sample logic for verification (replace with actual verification)
        if (name === "ManagerName" && matricID === "123456") { // Mock data
            alert("Manager verified. You can proceed with editing or deleting the event.");
            // Proceed to display edit form or other functionality
            enableEditOptions();
        } else {
            alert("Verification failed. Please enter valid credentials.");
        }
    }

    // Function to enable edit options after successful verification
    function enableEditOptions() {
        // Display an edit form or show edit fields for the event
        // For example, you might unlock form fields or show an "Edit" button
        alert("Edit options are now enabled.");
        // You can add more logic here to show an actual form for editing.
    }
</script>

</body>
</html>
