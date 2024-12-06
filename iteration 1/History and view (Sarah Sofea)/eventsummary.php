<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Summary</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .event-thumbnail {
            max-width: 250px; /* Restrict width for smaller thumbnails */
            max-height: 150px;
            margin: 5px;
            cursor: pointer;
        }

        .past-photos-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .past-photo {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .past-photo-overlay {
            position: relative;
        }

        .past-photo-overlay .photo-blur {
            filter: blur(4px);
            opacity: 0.6;
        }

        .photo-count-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="images/gatherly.png" alt="Logo">
            <h1>Event Summary</h1>
        </div>
        <div class="profile">
            <img src="images/profilepicture.jpg" alt="Profile Picture">
            <span>Username</span>
        </div>
    </div>
    
    <div class="container my-4">
        <div class="content">
            <?php
            include 'db.php';
            $event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            $event_sql = "SELECT event_name, event_date, description, image_path, category, organized_by, past_event_photos FROM events WHERE id = ?";
            $stmt = $conn->prepare($event_sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $event = $stmt->get_result()->fetch_assoc();

            $photos = explode(',', $event["past_event_photos"]);
            $photo_count = count($photos);

            echo "<div class='event-details'>";
            echo "<img src='" . htmlspecialchars($event["image_path"]) . "' class='event-image'>";
            echo "<div class='event-info'>";
            echo "<h2>" . htmlspecialchars($event["event_name"]) . "</h2>";
            echo "<p class='event-date'>" . date("F j, Y", strtotime($event["event_date"])) . "</p>";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($event["category"]) . "</p>";
            echo "<p><strong>Organized by:</strong> " . htmlspecialchars($event["organized_by"]) . "</p>";
            echo "<p>" . htmlspecialchars($event["description"]) . "</p>";
            echo "</div></div>";

            echo "<div class='past-photos-container'>";
            for ($i = 0; $i < min(3, $photo_count); $i++) {
                if ($i === 2 && $photo_count > 3) {
                    echo "<div class='past-photo-overlay'>";
                    echo "<img src='" . trim($photos[$i]) . "' class='past-photo photo-blur'>";
                    echo "<div class='photo-count-overlay' data-bs-toggle='modal' data-bs-target='#photoModal'>+" . ($photo_count - 2) . "</div>";
                    echo "</div>";
                } else {
                    echo "<img src='" . trim($photos[$i]) . "' class='past-photo' data-bs-toggle='modal' data-bs-target='#photoModal' data-index='" . $i . "'>";
                }
            }
            echo "</div>";
            ?>

            <!-- Reviews Section -->
            <h3>Reviews:</h3>
            <?php
            $review_sql = "SELECT participant_name, review_text, created_at FROM reviews WHERE event_id = ? ORDER BY created_at DESC";
            $review_stmt = $conn->prepare($review_sql);
            $review_stmt->bind_param("i", $event_id);
            $review_stmt->execute();
            $reviews = $review_stmt->get_result();

            while ($review = $reviews->fetch_assoc()) {
                echo "<div class='review-card'><h5>" . htmlspecialchars($review["participant_name"]) . "</h5>";
                echo "<p>" . htmlspecialchars($review["review_text"]) . "</p></div>";
            }
            ?>
        </div>
    </div>

    <!-- Modal for Full-Size Image with Carousel -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Photo Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            foreach ($photos as $index => $photo) {
                                $photo = trim($photo);
                                $activeClass = $index === 0 ? 'active' : '';
                                echo "<div class='carousel-item $activeClass'>";
                                echo "<img src='" . htmlspecialchars($photo) . "' class='d-block w-100 img-fluid'>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const photoModal = document.getElementById('photoModal');
        const photoCarousel = document.getElementById('photoCarousel');
        photoModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const photoIndex = button.getAttribute('data-index') || 0;
            const carouselInstance = bootstrap.Carousel.getInstance(photoCarousel);
            carouselInstance.to(photoIndex);
        });
    </script>
</body>
</html>
