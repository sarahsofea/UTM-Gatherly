<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "club_directory");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if `club_id` is provided
if (isset($_GET['club_id'])) {
    $clubId = $_GET['club_id'];

    // Validate club_id (e.g., numeric check)
    if (!is_numeric($clubId)) {
        echo json_encode(['error' => 'Invalid club ID']);
        exit;
    }

    // Query to fetch club details using club_id
    $clubQuery = "SELECT * FROM clubs WHERE club_id = ?";
    $stmt = $conn->prepare($clubQuery);
    $stmt->bind_param("i", $clubId);
    $stmt->execute();
    $clubResult = $stmt->get_result();

    if ($clubResult->num_rows > 0) {
        $club = $clubResult->fetch_assoc();

        // Fetch events
        $eventsQuery = "SELECT * FROM events WHERE club_id = ?";
        $stmt = $conn->prepare($eventsQuery);
        $stmt->bind_param("i", $clubId);
        $stmt->execute();
        $eventsResult = $stmt->get_result();

        $events = [];
        while ($event = $eventsResult->fetch_assoc()) {
            $events[] = $event;
        }

        echo json_encode([
            'clubName' => $club['club_name'],
            'events' => $events
        ]);
    } else {
        echo json_encode(['error' => 'Club not found']);
    }

    $stmt->close();
    exit; // Stop further script execution
}

// If no `club_id` is provided, show the main club list
$clubsQuery = "SELECT * FROM clubs";
$clubsResult = $conn->query($clubsQuery);

// Include header and display club list page
include 'layout/header.php'; 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Clubs - UTM Gatherly</title>
    <link rel="stylesheet" href="viewClub.css">
</head>
<body>
    <div class="container">
        <h1>Clubs in UTM Gatherly</h1>

        <div class="clubs-list">
            <?php while ($club = $clubsResult->fetch_assoc()): ?>
                <div class="club-card" onclick="showEvents(<?= htmlspecialchars($club['club_id']) ?>)">
                    <h2 class="club-name"><?= htmlspecialchars($club['club_name']) ?></h2>
                    <p><strong>Description:</strong> <?= htmlspecialchars($club['description']) ?></p>
                    <p><strong>Faculty:</strong> <?= htmlspecialchars($club['faculty']) ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($club['email']) ?>"><?= htmlspecialchars($club['email']) ?></a></p>
                    <p><strong>Phone Number:</strong> <?= htmlspecialchars($club['phone_number']) ?></p>
                    <p><strong>Established Year:</strong> <?= htmlspecialchars($club['established_year']) ?></p>
                    <p><strong>Website:</strong> <a href="<?= htmlspecialchars($club['website_url']) ?>" target="_blank"><?= htmlspecialchars($club['website_url']) ?></a></p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Modal for Events -->
        <div id="eventsModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('eventsModal')">&times;</span>
                <h2 id="modalClubName"></h2>
                <h3>Upcoming Events</h3>
                <ul id="eventsList"></ul>
            </div>
        </div>

        <!-- Modal for Event Details -->
        <div id="eventModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal('eventModal')">&times;</span>
                <h2 id="modalTitle"></h2>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
                <p><strong>Time:</strong> <span id="modalTime"></span></p>
                <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                <!-- <p><strong>Club:</strong> <span id="modalClubName"></span></p> -->
                <p><strong>Description:</strong> <span id="modalDescription"></span></p>
            </div>
        </div>
    </div>

    <script>
        // Function to fetch and show events for a specific club
        function showEvents(clubId) {
        // if (!clubId) {
        //     alert("No club ID provided.");
        //     return;
        // }

        fetch(`viewClub.php?club_id=${clubId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Populate modal data
                document.getElementById('modalClubName').innerText = data.clubName || "Club Details";
                const eventsList = document.getElementById('eventsList');
                eventsList.innerHTML = ''; // Clear previous content

                if (data.events && data.events.length > 0) {
                    data.events.forEach(event => {
                        const li = document.createElement('li');
                        li.textContent = `${event.title} - ${event.date}`;
                        li.onclick = function () { showEventDetails(event); }; // Attach event details
                        eventsList.appendChild(li);
                    });
                } else {
                    eventsList.innerHTML = '<li>No events available for this club.</li>';
                }

                // Show the events modal
                document.getElementById('eventsModal').style.display = 'block';

                // Clear query parameters from the URL immediately after displaying the modal
                window.history.replaceState({}, document.title, window.location.pathname);
            })
            .catch(error => {
                console.error("Error fetching events:", error);
                alert("Error fetching event data: " + error.message);
            });
    }


        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function showEventDetails(event) {
            document.getElementById('modalTitle').innerText = event.title;
            document.getElementById('modalDate').innerText = event.date;
            document.getElementById('modalTime').innerText = event.time;
            document.getElementById('modalLocation').innerText = event.location;
            document.getElementById('modalDescription').innerText = event.description;

            document.getElementById('eventModal').style.display = 'block';
        }

        // Function to close modals
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';

                // Clear modal content to prevent stale data
                if (modalId === 'eventsModal') {
                    document.getElementById('modalClubName').innerText = '';
                    document.getElementById('eventsList').innerHTML = '';
                } else if (modalId === 'eventModal') {
                    document.getElementById('modalTitle').innerText = '';
                    document.getElementById('modalDate').innerText = '';
                    document.getElementById('modalTime').innerText = '';
                    document.getElementById('modalLocation').innerText = '';
                    document.getElementById('modalDescription').innerText = '';
                }
            }
        }

        // Function to handle query parameters on page load
        function handleQueryParams() {
            const urlParams = new URLSearchParams(window.location.search);
            const clubId = urlParams.get('club_id');

            console.log('Club ID from URL:', clubId);

            if (clubId) {
                showEvents(clubId);
                console.log('Clearing query parameters...');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }

        // Attach event listeners and handle query parameters when DOM is loaded
        document.addEventListener("DOMContentLoaded", handleQueryParams);

    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
