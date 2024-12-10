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

include 'layout/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="viewEvents.css">
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
                        \'' . htmlspecialchars($event['description'], ENT_QUOTES) . '\',
                        ' . $event['id'] . ')">Learn More</a>
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
                        \'' . htmlspecialchars($event['description'], ENT_QUOTES) . '\',
                        ' . $event['id'] . ')">Learn More</a>
                    <a href="javascript:void(0);" class="btn-primary" onclick="showRegisterModal()">Register</a>
                </div>
            </div>';
        }
        ?>
    </div>
</section>

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

<!-- Modal for Edit/Delete -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h2>Verify Manager</h2>

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
            <button type="button" onclick="verifyManager(<?php echo $event['id']; ?>)">Verify</button>
        </form>
    </div>
</div>

<!-- Modal for Adding Event -->
<div id="editEventDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addEventDetailsModal')">&times;</span>
            <h2>Add New Event</h2>
            <form action="create_event.php" method="POST" enctype="multipart/form-data">
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

                <button type="submit" class="btn-primary">Submit</button>
            </form>
        </div>
    </div>

<script>
    let currentEventId = null; // Global variable to store the current event ID

    // Show modal and store event ID
    function showModal(title, date, time, location, organizer, description, eventId) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalTime').innerText = time;
        document.getElementById('modalLocation').innerText = location;
        document.getElementById('modalOrganizer').innerText = organizer;
        document.getElementById('modalDescription').innerText = description;
        
        currentEventId = eventId; // Store the event ID when opening the modal
        console.log("Event ID set to: " + currentEventId); // Debugging line to confirm ID is set
        document.getElementById('eventModal').style.display = 'block'; // Show modal
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

    function showEditForm(eventId) {
        // Open the modal (assuming you're using Bootstrap or a similar framework)
        const editModal = document.getElementById('editEventDetailsModal');
        editModal.style.display = 'block'; // or use your modal framework method to show it

        // Optionally, pre-fill the form with event data (if needed)
        // Fetch event details via AJAX (optional if needed)
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `getEventDetails.php?eventId=${eventId}`, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const eventDetails = JSON.parse(xhr.responseText);
                document.getElementById('eventTitle').value = eventDetails.title;
                document.getElementById('eventDescription').value = eventDetails.description;
                document.getElementById('eventDate').value = eventDetails.date;
                // Fill other form fields as needed
            } else {
                alert("Error fetching event details.");
            }
        };
        xhr.send();
    }


    // Close the Edit/Delete Modal
    function closeEditModal() {
        document.getElementById('editModal').style.display = "none";
        // Reset forms when modal is closed
        document.getElementById('managerVerificationForm').reset();
        document.getElementById('eventEditForm').style.display = "none";
    }

    // Function to handle the verification
    function verifyManager() {
        const managerName = document.getElementById('managerName').value;
        const managerID = document.getElementById('managerID').value;

        if (!managerName || !managerID) {
            alert("Please enter both name and matric ID");
            return;
        }

        if (currentEventId) {
            // Create an AJAX request to verify manager
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "verifyManager.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send data to PHP (name, ID, event ID)
            xhr.send(`name=${encodeURIComponent(managerName)}&id=${encodeURIComponent(managerID)}&eventId=${encodeURIComponent(currentEventId)}`);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Handle the response from PHP
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Manager verified successfully!");
                        
                        // Proceed to the event editing form or show the modal
                        showEditForm(currentEventId); // Call a function to show the edit form
                    } else {
                        alert("Verification failed. Please check your credentials.");
                    }
                } else {
                    alert("An error occurred during verification.");
                }
            };
        } else {
            alert("Event ID is not set");
        }
    }

    // Handle Event Form Submission (Placeholder function)
    function handleEventSubmit(event) {
        event.preventDefault();
        // Implement AJAX or form submission logic here
        alert('Event Edited Successfully!');
        closeEditModal();
    }

    // Placeholder function for deleting an event
    function deleteEvent() {
        if (confirm('Are you sure you want to delete this event?')) {
            // Implement delete logic here
            alert('Event Deleted Successfully!');
            closeEditModal();
        }
    }

    // Close the modal if the user clicks outside the content
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
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
