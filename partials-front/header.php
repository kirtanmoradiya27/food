<?php include('config/constants.php'); ?>

<?php 
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set username if logged in
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Guest";
?>

<html>
<head>
    <title>Delmi - Food Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1,900,700,500,300,400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.svg">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet">

    <style>
        /* Navbar Styling */
        .navbar {
            background: white;
            padding: 15px 0;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }
        .nav-logo img {
            height: 40px;
        }
        .nav-center {
            display: flex;
            gap: 20px;
        }
        .nav-link {
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #ff5733;
        }
        .nav-btn {
            background: #ff5733;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        .nav-btn:hover {
            background: #e64a19;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-icon {
            font-size: 24px;
            color: #333;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            color: #d32f2f;
            text-decoration: none;
            font-weight: 500;
            gap: 5px;
            transition: color 0.3s;
        }
        .logout-btn:hover {
            color: #ff5733;
        }
    </style>
</head>

<body>
    <!-- Navbar Starts -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-inner">
                <!-- Logo -->
                <a href="<?php echo SITEURL; ?>" class="nav-logo">
                    <img src="/food-order/images/nav-logo.svg" alt="Delmi Logo">
                </a>

                <!-- Center Navigation Links -->
                <div class="nav-center">
                    <a href="<?php echo SITEURL; ?>home.php" class="nav-link">Home</a>
                    <a href="<?php echo SITEURL; ?>categories.php" class="nav-link">Categories</a>
                    <a href="<?php echo SITEURL; ?>foods.php" class="nav-link">Foods</a>
                    <a href="<?php echo SITEURL; ?>review.php" class="nav-link">Review</a>
                    <a href="<?php echo SITEURL; ?>feedback.php" class="nav-link">Feedback</a>
                    <a href="<?php echo SITEURL; ?>contact.php" class="nav-link">Contacts</a>
                </div>

                <!-- Right Side (User & Logout) -->
                <div class="user-info">
                    <span class="material-symbols-rounded user-icon">person</span>
                    <span><?php echo $user_name; ?></span>  

                    <a href="logout.php" class="logout-btn">
                        <span class="material-symbols-rounded">logout</span> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>
