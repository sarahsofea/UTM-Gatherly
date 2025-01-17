<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* header color gradient */
        .bg-maroon-gradient {
            color: #fff !important;
            background: linear-gradient(to right, #4b0000, #800000, #b22222) !important;
        }

        .user-name-text {
            color: var(--vz-header-item-color);
        }
        
        .avatar-xs {
            height: 2rem;
            width: 2rem;
        }

        .text-muted {
        color: #6c757d !important;
        }

        .dropdown-item i {
            font-size: 1.5rem; /* Adjust icon size */
        }
    </style>
</head>

<body>
    <header class="p-3 bg-maroon-gradient">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="<?php echo BASE_URL; ?>index.php?r=site/index" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <img class="me-2" src="<?php echo IMAGE_URL; ?>utmlogobw.png" alt="UTM Logo" width="157"
                        height="42">
                    <img class="me-2" src="<?php echo IMAGE_URL; ?>gatherly.png" alt="Gatherly Logo" width="60"
                        height="42">
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=site/index"
                            class="nav-link px-2 text-white">Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=calendar/index"
                            class="nav-link px-2 text-white">Calendar</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=club/club"
                            class="nav-link px-2 text-white">Club</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=event/index"
                            class="nav-link px-2 text-white">Event</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?r=history/eventHistory"
                            class="nav-link px-2 text-white">History</a></li>
                </ul>

                <div class="dropdown">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" 
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown" 
                            data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bxs-bell bx-sm'></i>
                        <span id="notification-badge" class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-maroon-gradient bg-pattern rounded-top">
                            <div class="p-3">
                                <h6 class="fs-16 fw-semibold text-white"> Notifications </h6>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                            <!-- Notifications will be dynamically inserted here -->
                        </div>
                    </div>
                </div>

                <div class="dropdown header-item topbar-user">
                    <button type="button" class="btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="<?php echo IMAGE_URL; ?>profilepicture.jpg" width="32" height="32" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium text-white"><?php echo $_SESSION['username']?></span>
                                <!-- <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">User Role</span> -->
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 65px, 0px);">
                        <!-- list dropdown item-->
                        <h6 class="dropdown-header">Welcome <?php echo $_SESSION['username']?>!</h6>
                        <!-- profile -->
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>index.php?r=profile/createProfile"><i class="fa-solid fa-circle-user"></i> <span class="align-middle">Profile</span></a>
                        <div class="dropdown-divider"></div>
                        <!-- logout -->
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>index.php?r=site/logout"><i class="fa-solid fa-right-from-bracket fa-fade fa-sm align-middle me-2" style="color: #f06060;"></i><span class="align-middle" style="color: #f06060;">Logout</span></a>
                    </div>

                </div>
            </div>
        </div>
        <script>
            async function updateNotificationBadge() {
                try {
                    const badge = document.getElementById('notification-badge'); // Select the badge element

                    if (!badge) {
                        console.error('Badge element not found');
                        return;
                    }

                    const response = await fetch('<?= BASE_URL; ?>index.php?r=site/notification'); // Replace with your backend route
                    const notifications = await response.json();

                    // Update badge content
                    badge.textContent = notifications.length;

                    // If there are no notifications, hide the badge
                    if (notifications.length === 0) {
                        badge.textContent = ''; // Clear the badge content
                    }
                } catch (error) {
                    console.error('Error updating notification badge:', error);
                }
            }

            async function loadNotifications() {
                try {
                    const container = document.querySelector('.dropdown-menu .pe-2');
                    const badge = document.getElementById('notification-badge'); // Select the badge element

                    if (!container || !badge) {
                        console.error('Container or badge element not found');
                        return;
                    }

                    const response = await fetch('<?= BASE_URL; ?>index.php?r=site/notification'); // Replace with your backend route
                    const notifications = await response.json();

                    // Clear existing notifications
                    container.innerHTML = '';

                    // Update badge content
                    badge.textContent = notifications.length;

                    // If there are no notifications, display "No upcoming events" and hide badge
                    if (notifications.length === 0) {
                        badge.textContent = ''; // Clear the badge content
                        container.innerHTML = `
                            <div class="text-center p-3">
                                <p class="text-muted fs-13">No upcoming events</p>
                            </div>`;
                        return;
                    }

                    // Populate the dropdown with notifications
                    notifications.forEach(notification => {
                        const categoryIcons = {
                            "Personal": { icon: "fa-solid fa-user-circle", color: "#007bff" },
                            "Academic": { icon: "fa-solid fa-graduation-cap", color: "#dc3545" },
                            "Entrepreneurship": { icon: "fa-solid fa-briefcase", color: "#28a745" },
                            "Sport": { icon: "fa-solid fa-football", color: "#ffc107" },
                            "Volunteering": { icon: "fa fa-hand-holding-heart", color: "#fd7e14" }
                        };

                        const category = categoryIcons[notification.category] || { icon: "bx bxs-bell", color: "#6c757d" };
                        const { icon, color } = category;

                        const item = `
                            <div class="text-reset dropdown-item" style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f1f1;">
                                <div class="d-flex">
                                    <i class='${icon} bx-sm pe-2' style="color: ${color};"></i>
                                    <div class="flex-1">
                                        <a href="#!" class="stretched-link" style="text-decoration: none; color:rgba(21, 23, 24, 0.75);">
                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">${notification.name}</h6>
                                        </a>
                                        <div class="fs-13 text-muted">
                                            <p class="mb-1">${notification.description}</p>
                                        </div>
                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                            <span><i class="mdi mdi-clock-outline"></i> Starts in ${notification.time_left} hours</span>
                                        </p>
                                    </div>
                                </div>
                            </div>`;
                        container.insertAdjacentHTML('beforeend', item);
                    });

                } catch (error) {
                    console.error('Error loading notifications:', error);
                }
            }

            // Attach the event listener
            document.addEventListener('DOMContentLoaded', () => {
                // Update the badge count on page load
                updateNotificationBadge();

                // Attach the dropdown loading functionality to the button click
                document.querySelector('#page-header-notifications-dropdown').addEventListener('click', loadNotifications);
            });
        </script>
    </header>

</body>

</html>
