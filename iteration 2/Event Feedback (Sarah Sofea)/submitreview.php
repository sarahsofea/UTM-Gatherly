<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';
    
    $participant_name = $conn->real_escape_string($_POST['participant_name']);
    $review_text = $conn->real_escape_string($_POST['review_text']);
    $rating = intval($_POST['rating']);
    $heard_from = $conn->real_escape_string($_POST['heard_from']);
    $enjoyed = isset($_POST['enjoyed']) ? implode(', ', $_POST['enjoyed']) : '';
    $join_again = $conn->real_escape_string($_POST['join_again']);
    $reason = isset($_POST['reason']) ? $conn->real_escape_string($_POST['reason']) : null;
    $event_id = intval($_POST['event_id']);

    $sql = "INSERT INTO reviews (event_id, participant_name, review_text, rating, heard_from, enjoyed, join_again, reason) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississss", $event_id, $participant_name, $review_text, $rating, $heard_from, $enjoyed, $join_again, $reason);

    if ($stmt->execute()) {
        // Redirect to the event summary page
        header("Location: eventsummary.php?id=" . $event_id);
        exit();
    } else {
        $error_message = "Error submitting review. Please try again later.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 3rem;
            color: lightgray;
            cursor: pointer;
            padding: 0 5px;
        }

        .star-rating input:checked ~ label {
            color: gold;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
        }

        .custom-card {
            background-color: #f8f9fa;
            border: 1px solid #d6d8db;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="header">
    <div class="header-logo">
        <img src="images/gatherly.png" alt="Logo">
        <h1>Review Form</h1>
    </div>
    <div class="profile">
        <img src="images/profilepicture.jpg" alt="Profile Picture">
        <span>Username</span>
    </div>
</div>

<div class="content mt-4">
    <a href="eventsummary.php?id=<?php echo $_GET['event_id']; ?>" class="btn btn-secondary mb-3">&larr; Back </a>
    <form method="POST" action="" class="card p-4 custom-card">
        <div class="mb-3">
            <label for="participant_name" class="form-label">Your Name:</label>
            <input type="text" class="form-control" id="participant_name" name="participant_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rating <i>[1 to 5 stars]</i>:</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1" title="1 star">★</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">How did you hear about the event?</label>
            <select class="form-select" name="heard_from" required>
                <option value="" disabled selected>Select an option</option>
                <option value="Social Media">Social Media</option>
                <option value="Friend">Friend</option>
                <option value="UTM Gatherly">UTM Gatherly</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">What did you enjoy most about the event? <i>[can choose more than one]</i></label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="enjoyed[]" value="Venue" id="venue">
                <label class="form-check-label" for="venue">Venue</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="enjoyed[]" value="Activities" id="activities">
                <label class="form-check-label" for="activities">Activities</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="enjoyed[]" value="Networking" id="networking">
                <label class="form-check-label" for="networking">Networking</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="enjoyed[]" value="Experiences" id="experiences">
                <label class="form-check-label" for="experiences">Experiences</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Would you like to join us again?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="join_again" id="yes" value="Yes" required>
                <label class="form-check-label" for="yes">Yes</label>
            </div>
            <div class="form-check d-flex align-items-center">
                <input class="form-check-input" type="radio" name="join_again" id="no" value="No" required>
                <label class="form-check-label" for="no">No</label>
                <input type="text" class="form-control d-inline-block ms-2" id="reason" name="reason" placeholder="Enter your reason" style="display: none; width: 50%;" />
            </div>
        </div>
        <div class="mb-3">
            <label for="review_text" class="form-label">Honest Review:</label>
            <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
        </div>
        <input type="hidden" name="event_id" value="<?php echo $_GET['event_id']; ?>">
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
    <?php
    if (isset($error_message)) {
        echo "<p class='text-danger'>$error_message</p>";
    }
    ?>
</div>
<script>
    const yesRadio = document.getElementById('yes');
    const noRadio = document.getElementById('no');
    const reasonInput = document.getElementById('reason');

    noRadio.addEventListener('change', () => {
        reasonInput.style.display = 'inline-block';
        reasonInput.required = true;
    });

    yesRadio.addEventListener('change', () => {
        reasonInput.style.display = 'none';
        reasonInput.required = false;
        reasonInput.value = '';
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
