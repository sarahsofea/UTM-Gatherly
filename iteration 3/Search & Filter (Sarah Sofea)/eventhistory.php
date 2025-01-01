<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event History</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .event-card {
            display: flex;
            align-items: center;
            border-left: 5px solid #0056b3;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            text-decoration: none;
            color: inherit;
            border-radius: 8px;
        }
        .event-card:hover {
            background-color: #e2e6ea;
        }
        .event-image {
            flex: 0 0 120px;
            margin-right: 15px;
            max-width: 120px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .event-info {
            flex: 1;
        }
        .event-date {
            color: #a30000;
            font-weight: bold;
        }
        .event-name {
            font-size: 1.25rem;
            font-weight: 500;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="images/gatherly.png" alt="Logo">
            <h1>Event History</h1>
        </div>
        <div class="profile">
            <img src="images/profilepicture.jpg" alt="Profile Picture">
            <span>Username</span>
        </div>
    </div>

    <div class="container my-4">
        <div class="content">
            <!-- Search and Filter Form -->
            <form method="GET" action="eventhistory.php" class="mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php
                            // Fetch categories for filtering
                            include 'db.php';
                            $category_sql = "SELECT DISTINCT category FROM events";
                            $category_result = $conn->query($category_sql);
                            while ($category = $category_result->fetch_assoc()):
                            ?>
                                <option value="<?php echo htmlspecialchars($category['category']); ?>" <?php echo isset($_GET['category']) && $_GET['category'] === $category['category'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['category']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <?php
            // Get the selected search and category filter values
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $category_filter = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

            // Base SQL query
            $sql = "SELECT id, event_name, event_date, description, image_path, category, organized_by FROM events WHERE 1=1";

            // Add search filter for event name only
            if (!empty($search)) {
                $sql .= " AND LOWER(event_name) LIKE LOWER('%$search%')";
            }

            // Add category filter if selected
            if (!empty($category_filter)) {
                $sql .= " AND category = '$category_filter'";
            }

            // Order by event date
            $sql .= " ORDER BY event_date DESC";

            // Execute the query
            $result = $conn->query($sql);

            // Display events
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<a href='eventsummary.php?id=" . $row["id"] . "' class='event-card'>";
                    echo "<img src='" . htmlspecialchars($row["image_path"]) . "' class='event-image'>";
                    echo "<div class='event-info'>";
                    echo "<p class='event-date'>" . date("F j, Y", strtotime($row["event_date"])) . "</p>";
                    echo "<h3 class='event-name'>" . htmlspecialchars($row["event_name"]) . "</h3>";
                    echo "<p><strong>Category:</strong> " . htmlspecialchars($row["category"]) . "</p>";
                    echo "<p><strong>Organized by:</strong> " . htmlspecialchars($row["organized_by"]) . "</p>";
                    echo "</div></a>";
                }
            } else {
                echo "<p>No events found.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
