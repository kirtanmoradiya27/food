<?php
    session_start();
    include('../db.php');

    // Check if admin is logged in
    if (!isset($_SESSION['admin'])) {
        header("Location: ../index.php"); // Redirect to login page
        exit();
    }
// Fetch Contacts Data
$sql = "SELECT * FROM tbl_contact"; // Assuming your table is named 'contacts'
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Manage Contacts</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- jQuery (missing in original file) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include('sidebar.php'); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-2">
            <?php include('navbar.php'); ?>
            <div class="row">
                <div class="col-12">
                    <h4 class="text-dark text-capitalize ps-3">Contacts List</h4>
                    <div class=" my-4">
                        <div class=" px-0 pb-2">
                            <div class=" table-responsive p-0">
                                <table class=" table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-dark font-weight-bolder opacity-7">
                                                ID</th>
                                            <th class="text-uppercase text-dark font-weight-bolder opacity-7 ps-2">
                                                Name</th>
                                            <th class="text-uppercase text-dark font-weight-bolder opacity-7 ps-2">
                                                Email</th>
                                            <th class="text-uppercase text-dark font-weight-bolder opacity-7 ps-2">
                                                Your Question</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class="ps-2"><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><a href="https://mail.google.com/mail/u/0/mailto:<?= htmlspecialchars($row['email']) ?>?subject=Response%20to%20Contact%20us"><?= htmlspecialchars($row['email']) ?></td></a>
                                            <td><?= htmlspecialchars($row['your_question']) ?></td>
                                        </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center'>No contacts available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src=" ../assets/js/core/popper.min.js">
    </script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

    <script>
    $(document).ready(function() {
        // Copy button functionality
        $(".copy-btn").click(function() {
            var id = $(this).data("id");
            // Implement copy functionality here
            alert("Copy functionality for ID: " + id +
                " is not implemented yet.");
        });
    });
    </script>
</body>

</html>

<?php
$conn->close();
?>