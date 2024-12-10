<?php
if (!defined('BASE_URL')) {
    // Dynamically get the protocol and domain
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/club-directory/'); // Update with your actual project directory
}

if (!defined('IMAGE_URL')) {
    define('IMAGE_URL', BASE_URL . 'images/'); // Correct path for images
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="UTM Gatherly: Discover and join student clubs at UTM">
    <meta name="author" content="UTM Gatherly Team">
    <title>UTM Gatherly</title>

    <!-- Bootstrap CSS -->
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bg-maroon-gradient {
            color: #fff !important;
            background: linear-gradient(to right, #4b0000, #800000, #b22222) !important;
        }
    </style>
</head>

<body>
    <header class="p-3 bg-maroon-gradient">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <img class="bi me-2" src="<?php echo IMAGE_URL; ?>gatherly.png" alt="Gatherly Logo" width="40" height="32">
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="<?php echo BASE_URL; ?>index.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=calendar/index" class="nav-link px-2 text-white">Calendar</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=club/index" class="nav-link px-2 text-white">Event</a></li>
                    <li><a href="<?php echo BASE_URL; ?>viewClubs.php" class="nav-link px-2 text-white">View Clubs</a></li> <!-- Added View Clubs -->
                </ul>

                <div class="dropdown text-end">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo IMAGE_URL; ?>profilepicture.jpg" alt="Profile Picture" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
