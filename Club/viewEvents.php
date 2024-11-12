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
    <style>
        /* Define primary color */
        :root {
            --primary-color: #800000;        /* Dark red */
            --secondary-color: #ffcccc;      /* Light red */
            --white-color: #ffffff;          /* White */
            --font-size-xxl: 3rem;
            --font-size-l: 1.5rem;
            --font-size-m: 1.2rem;
            --font-weight--semibold: 600;
            --font-weight--medium: 500;
            --border--radius--m: 5px;
            --max-width: 1200px;              /* Max width for the intro section */
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fafafa;
            background-color: #E7D7C1;
        }

        .upcoming-events {
            padding: 50px 0;
            background-color: #E7D7C1;  /* Same background color for the events section */
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

        .card {
            background-color: #FCB9B2;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card .card-title {
            font-size: 1.5rem;
            margin: 15px;
            color: #333;
        }

        .card .card-text {
            padding: 0 15px;
            font-size: 1rem;
            color: #555;
            margin-bottom: 15px;
        }

        .card p {
            padding: 0 15px;
            color: #777;
            font-size: 0.9rem;
            margin: 5px 0;
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

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
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
        }

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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
            }

            .register-form input[type="text"],
            .register-form input[type="email"],
            .register-form input[type="submit"] {
                width: 100%;
            }
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

    </style>
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
