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

        .add-review-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        /* Review Modal Styling */
.modal-content {
    background-color: #f8f9fa;
    border-radius: 10px;
}

.modal-header {
    background-color: #007bff;
    color: #fff;
    border-radius: 10px 10px 0 0;
    padding: 15px;
}

.review-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.review-box {
    background-color: #e9ecef;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    font-size: 1rem;
    margin-bottom: 10px;
}

.review-box strong {
    color: #495057;
    display: inline-block;
    min-width: 150px;
}

.review-content {
    margin-left: 5px;
    color: #212529;
}

#reviewRating {
    color: #ffc107; /* Gold stars */
    font-size: 1.2rem;
}

.back-button-container {
    margin-top: 20px; /* Adds spacing below the header */
    margin-left: 20px; /* Indents the button from the left */
}

.back-button-container a {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background-color: #6c757d;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
}

.back-button-container a span {
    margin-right: 6px; /* Adds spacing between arrow and text */
}

.button-container {
    display: flex;
    gap: 10px; /* Space between buttons */
}

#viewMoreReviewsBtn {
    flex-shrink: 0; /* Prevents the button from resizing */
}

.add-review-container {
    flex-shrink: 0; /* Keeps the Add Review button size consistent */
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
    <!-- Back Button -->
<div class="back-button-container" style="margin-top: 20px; margin-left: 20px;">
    <a href="eventhistory.php" class="btn btn-secondary" style="display: inline-flex; align-items: center; padding: 6px 12px; background-color: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: 500;">
        <span style="margin-right: 6px;">&larr;</span> Back
    </a>
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
            <br>
<h3>Reviews:</h3>
<div class="reviews-list">
    <?php
    $review_sql = "SELECT id, participant_name, review_text, rating, heard_from, enjoyed, join_again, reason, created_at FROM reviews WHERE event_id = ? ORDER BY created_at DESC";
    $review_stmt = $conn->prepare($review_sql);
    $review_stmt->bind_param("i", $event_id);
    $review_stmt->execute();
    $reviews = $review_stmt->get_result();

    $review_count = $reviews->num_rows; // Count the number of reviews
    $review_index = 0; // Keep track of the number of displayed reviews

    while ($review = $reviews->fetch_assoc()) {
        $review_index++;
        if ($review_index > 3) {
            echo "<div class='review-card d-none additional-review' data-bs-toggle='modal' data-bs-target='#reviewModal' data-review='" . htmlspecialchars(json_encode($review)) . "'>";
        } else {
            echo "<div class='review-card' data-bs-toggle='modal' data-bs-target='#reviewModal' data-review='" . htmlspecialchars(json_encode($review)) . "'>";
        }
        echo "<h5>" . htmlspecialchars($review["participant_name"]) . "</h5>";
        echo "<p><strong>Date:</strong> " . htmlspecialchars($review["created_at"]) . "</p>";
        echo "<h6>" . htmlspecialchars($review["review_text"]) . "</h6>";
        echo "</div>";
    }
    ?>
</div>

<!-- Buttons Section -->
<div class="button-container d-flex align-items-center mt-3">
    <?php if ($review_count > 3): ?>
        <button id="viewMoreReviewsBtn" class="btn btn-secondary me-2">View More</button>
    <?php endif; ?>
    <a href="submitreview.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Add Review</a>
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

  <!-- Modal for Review Details -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa; border-radius: 10px;">
            <div class="modal-header" style="background-color: #007bff; color: #fff; border-radius: 10px 10px 0 0;">
                <h5 class="modal-title" id="reviewModalLabel">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="review-section">
                    <div class="review-box">
                        <strong>Participant:</strong> <span id="reviewParticipant" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Date:</strong> <span id="reviewDate" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Rating:</strong> <span id="reviewRating" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Heard From:</strong> <span id="reviewHeardFrom" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Enjoyed:</strong> <span id="reviewEnjoyed" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Would Join Again:</strong> <span id="reviewJoinAgain" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Reason (if not joining again):</strong> <span id="reviewReason" class="review-content"></span>
                    </div>
                    <div class="review-box">
                        <strong>Review:</strong>
                        <p id="reviewText" class="review-content"></p>
                    </div>
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

    
        const reviewModal = document.getElementById('reviewModal');
reviewModal.addEventListener('show.bs.modal', function (event) {
    const reviewCard = event.relatedTarget;
    const reviewData = JSON.parse(reviewCard.getAttribute('data-review'));

    document.getElementById('reviewParticipant').textContent = reviewData.participant_name || 'N/A';
    document.getElementById('reviewDate').textContent = new Date(reviewData.created_at).toLocaleDateString();
    
    // Render stars for rating
    const rating = reviewData.rating || 0;
    const stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
    document.getElementById('reviewRating').textContent = stars;

    document.getElementById('reviewHeardFrom').textContent = reviewData.heard_from || 'N/A';
    document.getElementById('reviewEnjoyed').textContent = reviewData.enjoyed || 'N/A';
    document.getElementById('reviewJoinAgain').textContent = reviewData.join_again || 'N/A';
    document.getElementById('reviewReason').textContent = reviewData.reason || 'N/A';
    document.getElementById('reviewText').textContent = reviewData.review_text || 'N/A';
});

const viewMoreReviewsBtn = document.getElementById('viewMoreReviewsBtn');
    const additionalReviews = document.querySelectorAll('.additional-review');

    if (viewMoreReviewsBtn) {
        viewMoreReviewsBtn.addEventListener('click', function () {
            additionalReviews.forEach(review => {
                review.classList.remove('d-none'); // Show hidden reviews
            });
            viewMoreReviewsBtn.style.display = 'none'; // Hide the "View More" button
        });
    }


    </script>
</body>
</html>
