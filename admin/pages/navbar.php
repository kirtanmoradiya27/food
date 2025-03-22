<?php
  $current_page = basename($_SERVER['PHP_SELF']);

  $admin_name = $_SESSION['admin_name'];
  // Define page names
  $page_names = [
    'manage-admin.php' => 'Manage Admin',
    'manage-category.php' => 'Manage Category',
    'manage-food.php' => 'Manage Food',
    'manage-order.php' => 'Manage Order',
    'manage-user.php' => 'Manage User',
    'manage-reviews.php' => 'Manage Reviews',
    'manage-contacts.php' => 'Manage Contacts',
  ];

  // Get current page name
  $page_title = isset($page_names[$current_page]) ? $page_names[$current_page] : 'Dashboard';

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Manage Orders</title>
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
</head>

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur"
    data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <span class="opacity-5 text-dark">Pages</span>
                </li>
                <li class="breadcrumb-item text-sm text-dark" aria-current="page">
                    <?php echo $page_title; ?>
                </li>
            </ol>
        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <!-- <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group input-group-outline">
                    <label class="form-label">Type here...</label>
                    <input type="text" class="form-control">
                </div>
            </div> -->

            <ul class="navbar-nav d-flex align-items-center justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link text-body font-weight-bold px-0">
                        <p class="mt-3">
                            <?php echo $admin_name; ?>
                        </p>
                    </a>
                </li>
                <!-- Logout -->
                <li class="nav-item d-flex align-items-center">
                    <a href="logout.php" class=" nav-link text-body font-weight-bold px-3">
                        <i class="material-symbols-rounded">logout</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>