<?php
    session_start();
    include('../db.php');

    // Check if admin is logged in
    if (!isset($_SESSION['admin'])) {
        header("Location: ../index.php"); // Redirect to login page
        exit();
    }
$sql = "SELECT * FROM tbl_reviews ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Manage Review</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=star" />
    <style>
    body {
        background-color: #343a40;
        /* Dark background */
        color: white;
        /* White text */
    }

    .review-card {
        background-color: #495057;
        /* Darker card background */
        border-radius: 8px;
        padding: 20px;
        margin: 10px;
        min-height: 180px;
    }

    .star-rating {
        color: #FFD700;
    }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include('sidebar.php'); ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-2">
            <?php include('navbar.php'); ?>

            <div class="row">
                <div class="col-12">
                    <h2 class="text-dark text-capitalize ps-3">Customer Reviews</h2>
                    <div class="row my-4">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-4">
                            <div class="review-card">
                                <h5 class="text-white"><?= htmlspecialchars($row['reviewer_name']) ?></h5>
                                <div class=" star-rating">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $row['rating']) {
                                            echo '<span class="material-symbols-outlined">star</span>'; // Filled star
                                        } else {
                                            echo ''; // Empty star
                                        }
                                    }
                                    ?>
                                </div>
                                <p class="text-white"><?= htmlspecialchars($row['review']) ?></p>
                                <p class="text-white"><small><?= htmlspecialchars($row['created_at']) ?></small></p>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo "<p class='text-center'>No reviews available</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>



</body>

</html>