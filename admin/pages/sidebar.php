<?php
  $current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="" target="_blank">
            <img src="../assets/img/nav-logo.png" class="navbar-brand-img" height="100%" alt="main_logo">
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="dashboard.php">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?php echo ($current_page == 'manage-category.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?> "
                    href="manage-category.php">
                    <i class="material-symbols-rounded opacity-5">table_view</i>
                    <span class="nav-link-text ms-1">Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-food.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-food.php">
                    <i class="material-symbols-rounded opacity-5">restaurant</i>
                    <span class="nav-link-text ms-1">Foods</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-order.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-order.php">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Orders</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-user.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-user.php">
                    <i class="material-symbols-rounded opacity-5">person</i>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-admin.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-admin.php">
                    <i class="material-symbols-rounded opacity-5">admin_panel_settings</i>
                    <span class="nav-link-text ms-1">Admin</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">User Reviews</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-reviews.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-reviews.php">
                    <i class="material-symbols-rounded opacity-5">star_rate</i>
                    <span class="nav-link-text ms-1">Reviews</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-contacts.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-contacts.php">
                    <i class="material-symbols-rounded opacity-5">contact_mail</i>
                    <span class="nav-link-text ms-1">Contacts</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage-feedback.php') ? 'active bg-gradient-dark text-white' : 'text-dark'; ?>"
                    href="manage-feedback.php">
                    <i class="material-symbols-rounded opacity-5">feedback</i>
                    <span class="nav-link-text ms-1">Feedback</span>
                </a>
            </li>
        </ul>
    </div>
</aside>