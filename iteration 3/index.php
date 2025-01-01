<?php
$title = "Dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Light grey background */
            color: #333;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: #343a40; /* Dark grey shade */
            color: #fff;
        }

        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header .nav-links {
            display: flex;
            gap: 20px;
        }

        .header .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .header .nav-links a:hover {
            color: #ffc107; /* Accent color */
        }

        /* Hero Section */
        .hero {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
            background: #343a40;
            width: 98%;
            max-height: 400px; /* Set maximum height */
            aspect-ratio: 16 / 9; /* Consistent height-to-width ratio */
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures image scales to fit without distortion */
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hero img.active {
            opacity: 1;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: #fff;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .hero p {
            font-size: 1.2rem;
        }

        .hero a {
            margin-top: 20px;
            text-decoration: none;
            background: #ffc107;
            color: #343a40;
            padding: 10px 30px;
            border-radius: 30px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .hero a:hover {
            background: #ffcd39;
        }

        .slider-nav {
            position: absolute;
            bottom: 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 2;
        }

        .slider-nav button {
            width: 10px;
            height: 10px;
            background: #ffc107;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .slider-nav button.active {
            opacity: 1;
        }

        /* Main Content */
        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #343a40;
            text-align: center;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .menu-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .menu-item img {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }

        .menu-item h4 {
            font-size: 1.3rem;
            color: #343a40;
            margin: 10px 0;
        }

        .menu-item p {
            font-size: 1rem;
            color: #555;
        }

        .menu-item a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            background: #343a40;
            color: #fff;
            padding: 10px 20px;
            border-radius: 20px;
            transition: background 0.3s ease;
        }

        .menu-item a:hover {
            background: #ffc107;
        }

        /* Footer */
        footer {
            background: #343a40;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }

        footer p {
            margin-top: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <img src="https://cdn-az.allevents.in/events8/banners/f7d838b0ec575f97b5390c6fd8635a62a049e31d0506d928cf0383a88c1c1a5d-rimg-w959-h720-gmir.jpg?v=1653383926" alt="Past Program 1" class="active">
        <img src="https://events.utm.my/wp-content/uploads/2023/05/WhatsApp-Image-2023-06-11-at-10.38.05-AM-scaled.jpeg" alt="Past Program 2">
        <img src="https://events.utm.my/wp-content/uploads/2021/03/egames.jpg" alt="Past Program 3">
        <div class="hero-content">
        </div>
        <div class="slider-nav">
            <button class="active"></button>
            <button></button>
            <button></button>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <h2 class="section-title">Explore Your Dashboard</h2>
        <div class="menu-grid">
            <div class="menu-item">
                <img src="https://cdn-icons-png.flaticon.com/512/1999/1999106.png" alt="Events">
                <h4>Events</h4>
                <p>Participate in exciting activities.</p>
                <a href="#events">View More</a>
            </div>
            <div class="menu-item">
                <img src="https://cdn-icons-png.flaticon.com/512/2038/2038898.png" alt="Feedback">
                <h4>Feedback</h4>
                <p>Share your valuable thoughts.</p>
                <a href="#feedback">Give Feedback</a>
            </div>
            <div class="menu-item">
                <img src="https://www.freeiconspng.com/uploads/history-timeline-icon-33.png" alt="Event History">
                <h4>History</h4>
                <p>Review your past engagements.</p>
                <a href="#history">Check History</a>
            </div>
        </div>
    </main>

    <script>
        const images = document.querySelectorAll('.hero img');
        const buttons = document.querySelectorAll('.slider-nav button');
        let currentIndex = 0;

        function showImage(index) {
            images.forEach((img, i) => {
                img.classList.toggle('active', i === index);
                buttons[i].classList.toggle('active', i === index);
            });
            currentIndex = index;
        }

        buttons.forEach((button, index) => {
            button.addEventListener('click', () => {
                showImage(index);
            });
        });

        setInterval(() => {
            let nextIndex = (currentIndex + 1) % images.length;
            showImage(nextIndex);
        }, 5000);
    </script>
</body>
</html>
