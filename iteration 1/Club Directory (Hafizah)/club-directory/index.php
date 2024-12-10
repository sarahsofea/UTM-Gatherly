<?php 

// Database connection
$conn = new mysqli("localhost", "root", "", "club_directory");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current month
$currentMonth = date('m');

// Fetch events from the database
$sql = "SELECT * FROM events ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

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

$stmt->close();
$conn->close();

// Define BASE_URL for dynamic linking
define('BASE_URL', 'http://localhost/club-directory/');

// Include header if needed
include 'layout/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Directory - UTM Gatherly</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="index.css">
</head>
<body>
<main>
    <!-- Intro Section -->
    <section class="intro-section">
        <div class="section-content">
            <h2 class="title">UTM Gatherly</h2>
            <h3 class="subtitle">Welcome to the Club Directory</h3>
            <p class="description">Discover a variety of clubs and activities at UTM Gatherly! Find events that suit your interests and connect with like-minded students.</p>
            <div class="buttons">
                <a href="viewEvents.php" class="button" role="button" aria-label="View Events">View Events</a>
                <button class="button add-event" onclick="checkManagerRegistration()" role="button" aria-label="Add Event">Add Event</button>
            </div>
        </div>
    </section>

    <!-- Current Month Events -->
    <section class="upcoming-events">
        <h3>Upcoming Events for This Month</h3>
        <div class="container">
            <?php
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

    <!-- Updated Learn More Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('eventModal')">&times;</span>
            <h2 id="modalTitle"></h2>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
            <p><strong>Time:</strong> <span id="modalTime"></span></p>
            <p><strong>Location:</strong> <span id="modalLocation"></span></p>
            <p><strong>Organizer:</strong> <span id="modalOrganizer"></span></p>
            <p><strong>Description:</strong> <span id="modalDescription"></span></p>
            
            
        </div>
    </div>

    <!-- Edit/Delete Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h2>Edit or Delete Event</h2>
            
            <!-- Manager Verification Form -->
            <form id="managerVerificationForm">
                <div class="form-group">
                    <label for="managerName">Manager Name</label>
                    <input type="text" id="managerName" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="managerMatricID">Matric ID</label>
                    <input type="text" id="managerMatricID" placeholder="Enter your matric ID" required>
                </div>
                <!-- Use type="button" to prevent form submission -->
                <button type="submit" class="btn-primary">Verify</button>
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

    <!-- Modal for Event Manager Registration -->
    <div id="registerEventManagerModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('registerEventManagerModal')">&times;</span>
            <h2>Register as Event Manager</h2>
            <form action="event_manager.php" method="POST" class="register-form">
                <label for="managerName">Name:</label>
                <input type="text" id="managerName" name="managerName" required>

                <label for="managerId">Matric ID:</label>
                <input type="text" id="managerId" name="managerId" required>

                <label for="managerEmail">Email:</label>
                <input type="email" id="managerEmail" name="managerEmail" required>

                <input type="submit" value="Submit">
            </form>


            <!-- Loading indicator (hidden by default) -->
            <div id="loading" class="loading-indicator" style="display:none;">
                <p>Processing your registration...</p>
            </div>

            <!-- Success/Error message -->
            <div id="message" class="message" style="display:none;">
                <p id="messageText"></p>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Event -->
    <div id="addEventDetailsModal" class="modal">
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


</main>

<script>
    // Function to show the 'Learn More' modal
function showModal(title, date, time, location, organizer, description) {
    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalDate").innerText = date;
    document.getElementById("modalTime").innerText = time;
    document.getElementById("modalLocation").innerText = location;
    document.getElementById("modalOrganizer").innerText = organizer;
    document.getElementById("modalDescription").innerText = description;

    document.getElementById("eventModal").style.display = "block";
}

// Function to show the 'Edit/Delete Event' modal
function showEditModal() {
    document.getElementById("editModal").style.display = "block";
}

// Function to close the modal by id
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Function to open the 'Event Manager Registration' modal
function showRegisterModal() {
    document.getElementById("registerModal").style.display = "block";
}

// Function to close the 'Event Manager Registration' modal
function closeRegisterModal() {
    document.getElementById("registerModal").style.display = "none";
}

// Function to open the 'Event Manager Registration' modal
function showRegisterEventManagerModal() {
    document.getElementById("registerEventManagerModal").style.display = "block";
}

// Function to close the 'Event Manager Registration' modal
function closeRegisterEventManagerModal() {
    document.getElementById("registerEventManagerModal").style.display = "none";
}

// Function to show the 'Add Event' modal for registered event managers
function showAddEventModal() {
    document.getElementById("addEventDetailsModal").style.display = "block";
}

// Function to close the 'Add Event' modal
function closeAddEventModal() {
    document.getElementById("addEventDetailsModal").style.display = "none";
}

function openEditModal() {
    const editModal = document.getElementById("editModal");
    editModal.style.display = "block";
}

// Event listener for opening the modal when the edit button is clicked
verifyButton.addEventListener("click", openEditModal);

// Function to close the modal
function closeEditModal() {
        const editModal = document.getElementById("editModal");
        editModal.style.display = "none";
    }

// Function to handle event manager verification form submission
function verifyManager() {
    console.log("Verify button clicked."); // This should appear in the console if the button is clicked

    const name = document.getElementById("managerName").value;
    const matricId = document.getElementById("managerMatricID").value;

    if (name && matricId) {
        console.log("Manager Name: ", name);
        console.log("Manager Matric ID: ", matricId);

        // Send data to backend for validation
        fetch("verify_manager.php", {
            method: "POST",
            body: JSON.stringify({ name, matricId }),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Manager verified successfully! You can now edit or delete the event.");
                closeModal("editModal");  // Close the verification modal
                // Call function to open edit event form/modal here
            } else {
                alert("Invalid credentials. Please try again.");
            }
        })
        .catch(error => {
            alert("Error verifying manager.");
            console.error("Error: ", error);
        });
    } else {
        alert("Please fill in both fields.");
    }
}


// Function to show the welcome popup alert and proceed to the event details form
function showWelcomePopup() {
    alert("Welcome back! Proceed to add or view events.");
    showAddEventModal();  // Show the Add Event modal after "OK" is clicked
}

// Modified function to check if the user is a registered manager and show the welcome message
function checkManagerRegistration() {
    const isManagerRegistered = <?php echo isset($_SESSION['manager_id']) ? 'true' : 'false'; ?>;
    
    if (isManagerRegistered) {
        // Show welcome message popup, then the Add Event form
        showWelcomePopup();
    } else {
        // If not a registered manager, show the registration modal
        showRegisterEventManagerModal();
    }
}

// To trigger opening of the Register Event Manager modal
document.getElementById("openRegisterModalBtn").addEventListener("click", function() {
    openModal('registerEventManagerModal');
});

// Function to validate and submit event creation form via AJAX
document.getElementById("addEventDetailsModal").querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    
    // Send data to the backend to save the event
    fetch("create_event.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);  // Show success message
            closeAddEventModal();  // Close the modal after successful event creation
        } else {
            alert(data.message);  // Show error message
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("There was an error with the event creation.");
    });
});

verifyButton.addEventListener("click", function () {
    console.log("Verify button clicked!"); // Check if this is printed when you click
    // ...rest of your verification code
});


// Event listener to close modal when clicking outside of the modal content
window.onclick = function(event) {
    const modal = document.querySelectorAll(".modal");
    modal.forEach((m) => {
        if (event.target === m) {
            m.style.display = "none";
        }
    });
}

</script>

</body>
</html>
