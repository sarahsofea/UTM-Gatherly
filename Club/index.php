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

// Define BASE_URL for dynamic linking
define('BASE_URL', 'http://localhost/club-directory/');

// Include header if needed
include 'layout/header.php'; 
include('register.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Directory - UTM Gatherly</title>
    <style>
        :root {
            --primary-color: #800000;
            --secondary-color: #b22222;
            --white-color: #ffffff;
            --max-width: 1200px;
            --font-size-xxl: 2.5rem;
            --font-size-l: 1.25rem;
            --font-size-m: 1rem;
            --font-weight--semibold: 600;
            --font-weight--medium: 500;
            --border-radius: 8px;
        }

        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fafafa;
        }

        /* Intro Section Styling */
        .intro-section {
            min-height: 50vh;
            background-image: url('images/banner-intro.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            position: relative;
            color: var(--white-color);
            text-align: center;
            padding: 60px 20px;
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: visible; /* Allow modal to be visible outside this container */
        }

        .intro-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.2);
            z-index: -1;
        }

        .intro-section .section-content {
            max-width: var(--max-width);
            width: 100%;
            color: var(--white-color);
        }

        .intro-section .title {
            font-size: var(--font-size-xxl);
            color: var(--secondary-color);
            font-family: "Miniver", sans-serif;
            margin-bottom: 10px;
        }

        .intro-section .subtitle {
            margin-top: 8px;
            font-size: var(--font-size-l);
            font-weight: var(--font-weight--semibold);
            color: var(--white-color);
        }

        .intro-section .description {
            max-width: 600px;
            width: 100%;
            margin: 15px auto 30px;
            font-size: var(--font-size-m);
            color: var(--white-color);
            text-align: center;
        }

        .intro-section .buttons {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
        }

        .intro-section .button {
            padding: 12px 24px;
            font-size: var(--font-size-m);
            color: var(--primary-color);
            background-color: var(--secondary-color);
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: var(--font-weight--medium);
            transition: background 0.3s ease, color 0.3s ease;
            border: 2px solid transparent;
        }

        .intro-section .button:hover {
            background-color: var(--white-color);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .intro-section .button.add-event {
            background-color: transparent;
            border-color: var(--white-color);
            color: var(--white-color);
        }

        .intro-section .button.add-event:hover {
            color: var(--primary-color);
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        /* Modal Background and Content */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            padding-top: 60px; /* Space from top */
            transition: opacity 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            position: relative;
            z-index: 10000;  /* Ensure modal content appears on top */
        }

        /* For hiding modal with fade effect */
        .modal.hide {
            opacity: 0;
            display: none;
            transition: opacity 0.3s ease-out;
        }

        .modal.show {
            opacity: 1;
            display: block;
            transition: opacity 0.3s ease-in;
        }

        /* Close button for modal */
        .close-btn {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
            color: #aaa;
            float: right;
            padding: 12px 24px;
            font-size: var(--font-size-m);
            font-weight: var(--font-weight--medium);
            border-radius: var(--border-radius);
            text-decoration: none;
            transition: background 0.3s ease, color 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 15px;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Registration Form */
        .registration-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }

        .form-group label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
        }

        /* Register Form Styling */
        .register-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .register-form .form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .register-form label {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            align-self: flex-start;
        }

        .register-form input[type="text"],
        .register-form input[type="email"] {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .register-form input[type="text"]:focus,
        .register-form input[type="email"]:focus {
            border-color: var(--primary-color);
        }

        .register-form input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-form input[type="submit"]:hover {
            background-color: #b22222;
        }

        /* Enhanced Event Manager Registration Form */
        .event-manager-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .event-manager-form .form-group {
            display: flex;
            flex-direction: column;
            width: 10px;
        }

        .event-manager-form label {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            align-self: flex-start;
        }

        .event-manager-form input[type="text"],
        .event-manager-form input[type="email"] {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .event-manager-form input[type="text"]:focus,
        .event-manager-form input[type="email"]:focus {
            border-color: var(--primary-color);
        }

        .event-manager-form input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .event-manager-form input[type="submit"]:hover {
            background-color: #b22222;
        }


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-content,
            .register-form input[type="text"],
            .register-form input[type="email"],
            .register-form input[type="submit"] {
                width: 100%;
            }

            .intro-section {
                padding: 40px 20px;
            }

            .intro-section .buttons {
                flex-direction: column;
                gap: 15px;
            }

            .intro-section .button {
                padding: 10px 20px;
                font-size: 1rem;
            }

            .upcoming-events .container {
                flex-direction: column;
                gap: 20px;
            }
        }

        /* CSS for Upcoming Events */
        .upcoming-events {
            padding: 50px 0;
            background-color: #f9f9f9;
        }

        .upcoming-events h3 {
            text-align: center;
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 30px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        
        /* Submit Button */
        button[type="submit"] {
            background-color: #800000;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #b22222;
        }

        /* Event Card Styling */
        .card {
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            background: #ffffff;
            padding: 20px;
            max-width: 300px;
            width: 100%;
            text-align: center;
        }

        .card .btn-container {
            display: flex;
            justify-content: space-between;
            padding: 15px;
        }

        .card .btn-secondary,
        .card .btn-primary {
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
            color: white;
        }

        .card .btn-primary {
            background-color: var(--primary-color) !important;
        }

        .card .btn-secondary {
            background-color: #b22222 !important;
        }

        .card .btn-secondary:hover,
        .card .btn-primary:hover {
            opacity: 0.8;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: var(--border--radius--m);
            margin-bottom: 15px;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .card-text {
            color: #333;
            margin-bottom: 8px;
        }

        .btn-primary, .btn-secondary {
            padding: 8px 16px;
            border-radius: var(--border--radius--m);
            text-decoration: none;
            color: var(--white-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
        }

        .btn-secondary {
            background-color: #b22222;
        }

        .btn-primary:hover, .btn-secondary:hover {
            background-color: #800000;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
        }

        /* Styling for the Add Event Button */
        .add-event {
            display: inline-block;
            padding: 12px 24px;
            background-color: #800000;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-event:hover {
            background-color: #b22222;
        }

        /* Styling for Edit Icon */
        .edit-icon {
            font-size: 1.2rem;
            color: #800000; /* Matches primary color */
            cursor: pointer;
            margin-top: 20px;
            display: block;
            text-align: right;
        }
        .edit-icon:hover {
            color: #b22222;
        }

        /* Edit/Delete Event Form Styling */
        #editModal .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        #editModal h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        #managerVerificationForm {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #managerVerificationForm .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        #managerVerificationForm label {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        #managerVerificationForm input {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        #managerVerificationForm input:focus {
            border-color: var(--primary-color);
        }

        #managerVerificationForm button {
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        #managerVerificationForm button:hover {
            background-color: #b22222;
        }

        #managerVerificationForm button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Add some margin to the close button */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            border: none;
            background: none;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #b22222;
        }

        /* Add Responsive Styling */
        @media (max-width: 768px) {
            #editModal .modal-content {
                padding: 20px;
            }

            #managerVerificationForm input,
            #managerVerificationForm button {
                font-size: 0.9rem;
            }
        }

    }
    
    </style>
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
                <a href="#" class="button add-event" role="button" aria-label="Add Event">Add Event</a>
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
            <form id="managerVerificationForm">
                <div class="form-group">
                    <label for="managerName">Manager Name</label>
                    <input type="text" id="managerName" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="managerMatricID">Matric ID</label>
                    <input type="text" id="managerMatricID" placeholder="Enter your matric ID" required>
                </div>
                <button type="button" onclick="verifyManager()">Verify and Edit</button>
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
    <div id="addEventModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('addEventModal')">&times;</span>
            <h2>Register as Event Manager</h2>
            <form id="eventManagerForm" onsubmit="registerEventManager(event)">
                <div class="form-group">
                    <label for="managerName">Your Name</label>
                    <input type="text" id="managerName" name="managerName" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="managerId">Your Matric Number/ID</label>
                    <input type="text" id="managerId" name="managerId" required placeholder="Enter your matric number/ID">
                </div>

                <div class="form-group">
                    <label for="managerEmail">Your Email</label>
                    <input type="email" id="managerEmail" name="managerEmail" required placeholder="Enter your email address">
                </div>

                <button type="submit" class="btn-primary">Proceed to Add Event</button>
            </form>
        </div>
    </div>

<!-- Modal for Adding Event -->
<div id="addEventDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('addEventDetailsModal')">&times;</span>
        <h2>Add New Event</h2>
        <form id="eventDetailsForm" method="POST" action="create_event.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="eventTitle">Event Title:</label>
                <input type="text" id="eventTitle" name="eventTitle" required placeholder="Enter event title">
            </div>
            <div class="form-group">
                <label for="eventDate">Event Date:</label>
                <input type="date" id="eventDate" name="eventDate" required>
            </div>
            <div class="form-group">
                <label for="eventTime">Event Time:</label>
                <input type="time" id="eventTime" name="eventTime" required>
            </div>
            <div class="form-group">
                <label for="eventLocation">Event Location:</label>
                <input type="text" id="eventLocation" name="eventLocation" required placeholder="Enter event location">
            </div>
            <div class="form-group">
                <label for="eventDescription">Event Description:</label>
                <textarea id="eventDescription" name="eventDescription" placeholder="Enter event description"></textarea>
            </div>
            <div class="form-group">
                <label for="eventImage">Event Image (optional):</label>
                <input type="file" id="eventImage" name="eventImage">
            </div>
            <div class="form-group">
                <label for="eventOrganizer">Event Organizer:</label>
                <input type="text" id="eventOrganizer" name="eventOrganizer" required placeholder="Enter event organizer">
            </div>
            <button type="submit" class="btn-primary">Submit Event</button>
        </form>
    </div>
</div>

</main>

<?php include 'layout/footer.php'; ?>

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

    // JavaScript to submit the form via AJAX
    function registerEventManager(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(document.getElementById('eventManagerForm'));

        fetch('event_manager.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Display success message in an alert
                alert(data.message);
                
                // Optionally close the modal and open the next modal (if needed)
                closeModal('addEventModal');
                openModal('addEventDetailsModal');
            } else {
                // Display error message in an alert if registration fails
                alert(data.message);
            }
        })
        .catch(error => {
            // Handle any errors during the fetch with an alert
            console.error('Error:', error);
            alert("An error occurred while registering. Please try again.");
        });
    }

    // Function to close modals
    function closeModal() {
        document.getElementById('eventModal').style.display = "none";
    }
    
    // Function to open the modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
        }
    }

    // Show event registration modal
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

    // Example of showing the success modal after form submission
    document.getElementById("eventManagerForm").addEventListener("submit", function(event) {
        event.preventDefault();
        // Add logic to save data, and then show success modal
        openModal('successModal');
    });

    // Event listener for the 'Add Event' button
    document.querySelector('.add-event').addEventListener('click', function(e) {
        e.preventDefault();  // Prevent default link behavior
        openModal('addEventModal');
    });

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        if (event.target.classList.contains("modal")) {
            closeModal(event.target.id);
        }
    };
</script>
</body>
</html>