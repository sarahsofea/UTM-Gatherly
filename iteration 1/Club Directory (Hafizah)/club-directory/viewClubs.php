<?php
require 'db.php'; // Replace with the path to your database connection file

// Fetch all clubs
$clubsQuery = "SELECT * FROM clubs";
$clubsResult = $conn->query($clubsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Clubs</title>
    <link rel="stylesheet" href="viewClubs.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h1>Clubs</h1>
        <div class="clubs-list">
            <?php while ($club = $clubsResult->fetch_assoc()): ?>
                <div class="club-card" onclick="showEvents(<?= $club['club_id'] ?>)">
                    <h2><?= htmlspecialchars($club['club_name']) ?></h2>
                    <p><?= htmlspecialchars($club['description']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
        <div id="eventsModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 id="clubName"></h2>
                <div id="clubDetails"></div>
                <h3>Events</h3>
                <ul id="eventsList"></ul>
            </div>
        </div>
    </div>

    <script>
        function showEvents(clubId) {
            fetch(`fetchEvents.php?club_id=${clubId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('clubName').innerText = data.clubName;

                    const clubDetails = `
                        <p><strong>Description:</strong> ${data.description}</p>
                        <p><strong>Faculty:</strong> ${data.faculty}</p>
                        <p><strong>Email:</strong> <a href="mailto:${data.email}">${data.email}</a></p>
                        <p><strong>Phone Number:</strong> ${data.phoneNumber}</p>
                        <p><strong>Established Year:</strong> ${data.establishedYear}</p>
                        <p><strong>Website:</strong> <a href="${data.websiteUrl}" target="_blank">${data.websiteUrl}</a></p>
                    `;
                    document.getElementById('clubDetails').innerHTML = clubDetails;

                    const eventsList = document.getElementById('eventsList');
                    eventsList.innerHTML = '';

                    if (data.events.length > 0) {
                        data.events.forEach(event => {
                            const li = document.createElement('li');
                            li.textContent = `${event.name} - ${event.date}`;
                            eventsList.appendChild(li);
                        });
                    } else {
                        eventsList.innerHTML = '<li>No events available for this club.</li>';
                    }

                    document.getElementById('eventsModal').style.display = 'block';
                });
        }

        function closeModal() {
            document.getElementById('eventsModal').style.display = 'none';
        }
    </script>
</body>
</html>
