<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "club_directory");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the categories from the request (POST)
$categories = isset($_POST['categories']) ? $_POST['categories'] : [];

// Function to fetch all events with optional filters (category)
function fetchEvents($conn, $categories = []) {
    $sql = "SELECT * FROM events WHERE DATE(date) >= CURDATE()"; // Fetch future events

    // Add category filter if provided
    if (!empty($categories)) {
        $placeholders = implode(' OR ', array_fill(0, count($categories), 'category LIKE ?'));
        $sql .= " AND ($placeholders)";
    }

    $sql .= " ORDER BY date ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('MySQL prepare failed: ' . $conn->error);
    }

    // Bind parameters dynamically
    if (!empty($categories)) {
        $params = [];
        $types = str_repeat('s', count($categories));
        foreach ($categories as $category) {
            $params[] = "%$category%";
        }
        $stmt->bind_param($types, ...$params);
    }

    // Execute and fetch results
    if (!$stmt->execute()) {
        die("Execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

// Function to fetch current month's events
function fetchEventsForMonth($events) {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $currentMonthEvents = [];

    foreach ($events as $key => $event) {
        $eventDate = strtotime($event['date']);
        if (date('m', $eventDate) == $currentMonth && date('Y', $eventDate) == $currentYear) {
            $currentMonthEvents[] = $event;
            unset($events[$key]); // Remove from the main events array
        }
    }
    return $currentMonthEvents;
}

// Initialize filters
$categoryFilter = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];

// Fetch filtered events
$events = fetchEvents($conn, $categories);

// // Return events as JSON
// echo json_encode($events);

// Split events into current month and future events
$currentMonthEvents = fetchEventsForMonth($events);
$futureEvents = $events;

// Close the connection
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

<!-- Intro Section -->
<section class="intro-section">
        <div class="section-content">
            <h2 class="title">Discover What's New Event in UTM Gatherly</h2>
            <p class="description">Discover a variety of clubs and activities at UTM Gatherly! Find events that suit your interests and connect with like-minded students.</p>
            <button id="filterBtn">Filter Events</button>
            </div>
        </div>
</section>

<!-- Current Month Events -->
<section class="upcoming-events">
    <h3>Upcoming Events for This Month</h3>
    <div class="container">
        <?php foreach ($currentMonthEvents as $event): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
                <h4 class="card-title"><?= htmlspecialchars($event['title']) ?></h4>
                <p><strong><?= htmlspecialchars($event['date']) ?> at <?= htmlspecialchars($event['time']) ?></strong></p>
                <div class="btn-container">
                    <a href="javascript:void(0);" class="btn-secondary" onclick="showModal(
                        '<?= htmlspecialchars($event['title'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['date'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['time'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['location'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['club_name'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['description'], ENT_QUOTES) ?>',
                        <?= $event['id'] ?>)">Learn More</a>
                    <a href="javascript:void(0);" class="btn-primary" onclick="showRegisterModal()">Register</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Future Events -->
<section class="upcoming-events">
    <h3>All Events</h3>
    <div class="container">
        <?php foreach ($futureEvents as $event): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
                <h4 class="card-title"><?= htmlspecialchars($event['title']) ?></h4>
                <p><strong><?= htmlspecialchars($event['date']) ?> at <?= htmlspecialchars($event['time']) ?></strong></p>
                <div class="btn-container">
                    <a href="javascript:void(0);" class="btn-secondary" onclick="showModal(
                        '<?= htmlspecialchars($event['title'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['date'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['time'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['location'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['club_name'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($event['description'], ENT_QUOTES) ?>',
                        <?= $event['id'] ?>)">Learn More</a>
                    <a href="javascript:void(0);" class="btn-primary" onclick="showRegisterModal()">Register</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Filter Modal -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <form id="filterForm">
            <div class="filter-box">
                <h4>Filter by Category</h4>
                <!-- Category checkboxes -->
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Academy" onchange="filterEvents()">
                    <label>Academy</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Sports" onchange="filterEvents()">
                    <label>Sports</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Cultural" onchange="filterEvents()">
                    <label>Cultural</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Social" onchange="filterEvents()">
                    <label>Social</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Career" onchange="filterEvents()">
                    <label>Career</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Health and Wellness" onchange="filterEvents()">
                    <label>Health and Wellness</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Volunteer" onchange="filterEvents()">
                    <label>Volunteer</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Leadership" onchange="filterEvents()">
                    <label>Leadership</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Tech and Innovation" onchange="filterEvents()">
                    <label>Tech and Innovation</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" class="category-filter" value="Entertainment" onchange="filterEvents()">
                    <label>Entertainment</label>
                </div>
            </div>
            <button type="button" class="apply-filter" onclick="filterEvents()">Search</button>
        </form>
    </div>
</div>

<!-- Modal for Displaying Events -->
<div id="eventFetchModal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('eventFetchModal').style.display='none'">&times;</span>
        <div class="event-list"></div>
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

<!-- Updated Learn More Modal -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEventModal()">&times;</span>
        <h2 id="modalTitle"></h2>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Time:</strong> <span id="modalTime"></span></p>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <p><strong>Organizer:</strong> <span id="modalClub_name"></span></p>
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
            <button type="submit">Verify Manager</button>
        </form>
    </div>
</div>

<!-- Modal for Adding Event -->
<div id="editEventDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('editEventDetailsModal')">&times;</span>
            <h2>Edit Event</h2>
            <!-- <form action="create_event.php" method="POST" enctype="multipart/form-data"> -->
            <form id="eventEditForm" onsubmit="event.preventDefault()">
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

                <!-- <button id="eventId" onclick="openEditModal()">Submit</button> -->
                <button type="submit" class="btn-primary">Submit</button>
            </form>
        </div>
    </div>

<script>
    let currentEventId = null; // Global variable to store the current event ID

    // Show modal and store event ID
    function showModal(title, date, time, location, club_name, description, eventId) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalTime').innerText = time;
        document.getElementById('modalLocation').innerText = location;
        document.getElementById('modalClub_name').innerText = club_name;
        document.getElementById('modalDescription').innerText = description;
        
        currentEventId = eventId; // Store the event ID when opening the modal
        console.log("Event ID set to: " + currentEventId); // Debugging line to confirm ID is set
        document.getElementById('eventModal').style.display = 'block'; // Show modal
    }

    function closeEventModal() {
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

    document.addEventListener('DOMContentLoaded', function () {
        // Attach the verifyManager function to the form submit
        const form = document.getElementById('managerVerificationForm');
        form.addEventListener('submit', verifyManager);
        
        function verifyManager(event) {
            // Prevent form from submitting traditionally
            event.preventDefault();

            const managerName = document.getElementById('managerName').value;
            const managerID = document.getElementById('managerID').value;

            // Send the verification data to the server
            fetch('verify_manager.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'managerName': managerName,
                    'managerId': managerID
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
                if (data.status === 'success') {
                    const verificationForm = document.getElementById('managerVerificationForm');
                    const editForm = document.getElementById('eventEditForm');

                    // Check if elements exist before modifying their styles
                    if (verificationForm && editForm) {
                        verificationForm.style.display = 'none';
                        editForm.style.display = 'block';
                        alert('Verification successful. You can now edit the event.');
                    } else {
                        console.log('One or both elements do not exist.');
                    }
                } else {
                    alert(data.message); // Show error message if verification fails
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // Handle Event Form Submission (Placeholder function)
    function handleEventSubmit(event) {
        event.preventDefault();
        // Implement AJAX or form submission logic here
        alert('Event Edited Successfully!');
        closeEditModal();
    }

    // Function to Open Edit Modal and Populate Fields
    function openEditModal(eventId) {
        // Fetch event details from the server
        fetch(`get_event_details.php?eventId=${eventId}`)
            .then(response => response.json())
            .then(data => {
                // Populate form fields with event data
                document.getElementById('eventId').value = data.eventId; // Hidden event ID
                document.getElementById('eventTitle').value = data.title;
                document.getElementById('eventDate').value = data.date;
                document.getElementById('eventTime').value = data.time;
                document.getElementById('eventLocation').value = data.location;
                document.getElementById('eventDescription').value = data.description;

                // Display the modal
                document.getElementById('editEventDetailsModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching event details:', error);
                alert('Failed to load event details.');
            });
    }

    // Function to Close Modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';

        // Reset form fields when the modal is closed
        document.getElementById('eventEditForm').reset();
    }

    // Handle Form Submission
    document.getElementById('eventEditForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        // Send form data to the server via AJAX
        fetch('update_event.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Event updated successfully!');
                    closeModal('editEventDetailsModal');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error updating event: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error updating event:', error);
                alert('Failed to update the event.');
            });
    });


    // Close the Edit/Delete Modal
    function closeEditModal() {
        document.getElementById('editModal').style.display = "none";
        // Reset forms when modal is closed
        document.getElementById('managerVerificationForm').reset();
        document.getElementById('eventEditForm').style.display = "none";
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

    // Open the filter modal
    document.getElementById('filterBtn').onclick = function() {
        document.getElementById('filterModal').style.display = 'block';
    };

    // Close the filter modal
    function closeModal() {
        document.getElementById('filterModal').style.display = 'none';
    }
    
    // Wait for the DOM content to be loaded before executing JavaScript
document.addEventListener('DOMContentLoaded', function() {

// Function to get selected categories from checkboxes or other UI components
function getSelectedCategories() {
    const checkboxes = document.querySelectorAll('.category-filter:checked');
    const selectedCategories = [];
    checkboxes.forEach(checkbox => {
        selectedCategories.push(checkbox.value);
    });
    return selectedCategories;
}

// Function to filter events based on selected categories
function filterEvents() {
    const selectedCategories = getSelectedCategories();
    fetchFilteredEvents(selectedCategories);
}

// Function to trigger the fetch for filtered events
function fetchFilteredEvents() {
    // Get selected categories and search term from the filter UI
    const selectedCategories = getSelectedCategories();  // This should return an array of selected categories
    const searchTerm = document.getElementById('searchInput') ? document.getElementById('searchInput').value : '';  // Ensure the element exists before accessing

    const filterData = {
        categories: selectedCategories,  // Array of selected categories
        search: searchTerm               // The search term
    };

    // Send AJAX request using fetch
    fetch('fetchEvents.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(filterData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
        } else {
            console.log('Filtered Events:', data);  // Process the returned data here
            displayEvents(data);  // Call the function to display events
        }
    })
    .catch(error => console.error('Error:', error));
}

// Function to display events in a modal or on the page
function displayEvents(events) {
    const modal = document.getElementById('eventFetchModal'); // Assuming you have a modal with this ID
    const eventList = modal.querySelector('.event-list');  // Assuming you have a container for the events in the modal

    eventList.innerHTML = '';  // Clear any existing events

    // Create a list of events
    events.forEach(event => {
        const eventItem = document.createElement('div');
        eventItem.classList.add('event-item');
        eventItem.innerHTML = `
            <h3>${event.title}</h3>
            <p>${event.date}</p>
            <p>${event.description}</p>
            <p>${event.location}</p>
            <p>${event.club_name}</p>
            <img src="${event.image}" alt="${event.title}" />
        `;
        eventList.appendChild(eventItem);
    });

    // Show the modal
    modal.style.display = 'block';
}

// Event listener to trigger filtering on button click (make sure the button exists)
const filterButton = document.getElementById('filterButton');
if (filterButton) {
    filterButton.addEventListener('click', function() {
        fetchFilteredEvents();
    });
}

// Event listener to trigger filtering when changing the search input or category checkboxes
const categoryCheckboxes = document.querySelectorAll('.category-filter');
categoryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', filterEvents);  // Attach filterEvents to the change event
});

const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('change', filterEvents);  // Attach filterEvents to the change event
}

// Close modal when clicking outside of it (optional)
const modal = document.getElementById('eventFetchModal');
if (modal) {
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

});

// Function to close modal
function closeModal() {
    const modal = document.getElementById('eventFetchModal');
    if (modal) {
        modal.style.display = 'none';
    }
}


    // Close the modal if clicked outside the modal content
    window.onclick = function(event) {
        if (event.target == document.getElementById('filterModal')) {
            closeModal();
        }
    }
</script>

</body>
</html>