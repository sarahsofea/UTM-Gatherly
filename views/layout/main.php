<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "UTM Gatherly"; ?></title>

    <!-- favicon -->
    <link rel="icon" href="<?php echo IMAGE_URL; ?>favicon.ico">
    <!-- Link to Bootstrap CSS -->
    <link href="<?php echo ASSET_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- header -->
    <?php include 'header.php'; ?>
    <!-- header end -->


    <!-- nav -->
    <?php include 'nav.php'; ?>
    <!-- nav end -->


    <!-- Main Content Area -->
    <main class="container my-5">
        <?php echo $content; ?>
    </main>


    <!-- Footer Section -->
    <?php include 'footer.php'; ?>
    <!-- footer end -->


    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="<?php echo ASSET_URL; ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo ASSET_URL; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo ASSET_URL; ?>fullcalendar/dist/index.global.min.js"></script>
</body>

</html>