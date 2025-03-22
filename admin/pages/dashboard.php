<?php
session_start();
include('../db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php"); // Redirect to login page
    exit();
}

// Fetch Total Categories
$sql1 = "SELECT COUNT(id) AS count FROM tbl_category";
$res1 = mysqli_query($conn, $sql1);
$total_categories = ($res1) ? mysqli_fetch_assoc($res1)['count'] : 0;

// Fetch Total Foods
$sql2 = "SELECT COUNT(id) AS count FROM tbl_food";
$res2 = mysqli_query($conn, $sql2);
$total_foods = ($res2) ? mysqli_fetch_assoc($res2)['count'] : 0;

// Fetch Total Orders
$sql3 = "SELECT COUNT(id) AS count FROM tbl_orders";  // Fixed table name
$res3 = mysqli_query($conn, $sql3);
$total_orders = ($res3) ? mysqli_fetch_assoc($res3)['count'] : 0;

// Fetch Total Revenue (Ensure NULL is handled)
$sql4 = "SELECT IFNULL(SUM(total_price), 0) AS Total FROM tbl_orders WHERE status='Delivered'"; // Ensure NULL is replaced with 0
$res4 = mysqli_query($conn, $sql4);
$total_revenue = ($res4) ? intval(mysqli_fetch_assoc($res4)['Total']) : 0;

// Fetch Monthly Revenue Data for Chart
$sql_monthly_revenue = "
    SELECT MONTH(order_date) AS month, IFNULL(SUM(total_price), 0) AS total
    FROM tbl_orders
    WHERE status='Delivered'
    GROUP BY MONTH(order_date)
    ORDER BY MONTH(order_date)
";
$res_monthly_revenue = mysqli_query($conn, $sql_monthly_revenue);
$monthly_revenue = [];

// Fill array with fetched data
while ($row = mysqli_fetch_assoc($res_monthly_revenue)) {
    $monthly_revenue[intval($row['month'])] = floatval($row['total']);
}

// Ensure all 12 months are filled (even if some months have no orders)
$months = range(1, 12);
$revenue_data = [];
foreach ($months as $month) {
    $revenue_data[] = isset($monthly_revenue[$month]) ? $monthly_revenue[$month] : 0;
}

// Debugging Output (Remove in production)
error_log("Total Revenue: " . $total_revenue);
error_log("Monthly Revenue Data: " . json_encode($revenue_data));
?>




<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Dashboard
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include('sidebar.php'); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <?php include('navbar.php'); ?>
        <!-- End Navbar -->
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-3">
                    <h3 class="mb-0 h4 font-weight-bolder mb-4">Dashboard</h3>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Categories</p>
                                    <h4 class="mb-0"><?php echo $total_categories; ?></h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">category</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Total available categories</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Foods</p>
                                    <h4 class="mb-0"><?php echo $total_foods; ?></h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">restaurant</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Total food items available</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Orders</p>
                                    <h4 class="mb-0"><?php echo $total_orders; ?></h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">shopping_cart</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Total orders processed</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Revenue</p>
                                    <h4 class="mb-0">$<?php echo number_format($total_revenue, 2); ?></h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">monetization_on</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Total revenue from delivered</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Monthly Revenue</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyRevenueChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Orders Overview</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="ordersOverviewChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>

    <script>
    // Monthly Revenue Chart
    var ctx = document.getElementById("monthlyRevenueChart").getContext("2d");
    var monthlyRevenueChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Revenue",
                data: <?= json_encode($revenue_data) ?>, // Use PHP to inject the revenue data
                borderColor: "#43A047",
                backgroundColor: "rgba(67, 160, 71, 0.2)",
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Orders Overview Chart (Example Data)
    var ctx2 = document.getElementById("ordersOverviewChart").getContext("2d");
    var ordersOverviewChart = new Chart(ctx2, {
        type: "bar",
        data: {
            labels: ["Order 1", "Order 2", "Order 3", "Order 4", "Order 5"],
            datasets: [{
                label: "Orders",
                data: [12, 19, 3, 5, 2], // Example data
                backgroundColor: "#43A047",
                borderColor: "#43A047",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>