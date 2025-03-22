<?php include('config/constants.php') ?>

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
    </head>

    <body>

        <!-- Navbar Starts -->
        <nav class="navbar">
            <div class="container">
                <div class="nav-inner">
                    <a href="<?php echo SITEURL; ?>" class="nav-logo"><img src="/food-order/images/nav-logo.svg" alt=""></a>

                    <div class="nav-center">
                        <a id="my-element" href="<?php echo SITEURL; ?>" class="nav-link">Home</a>
                        <a id="my-element" href="<?php echo SITEURL; ?>login.php" class="nav-link">Categories</a>
                        <a id="my-element" href="<?php echo SITEURL; ?>login.php" class="nav-link">foods</a>
                        <a id="my-element" href="<?php echo SITEURL; ?>login.php" class="nav-link">Review</a>
                        <!-- <a id="my-element" href="<?php echo SITEURL; ?>login.php" class="nav-link">login</a> -->
                        <!-- <a id="my-element" href="<?php echo SITEURL; ?>register.php" class="nav-link">Register</a> -->
                    </div>

                    <!-- <a href="<?php echo SITEURL; ?>contact.php" class="nav-btn">Contact</a> -->
                    <a href="<?php echo SITEURL; ?>login.php" class="nav-btn">Login</a>
                    
                </div>
            </div>
        </nav>
<!-- Search Section Starts -->
<section class="search-section">
    <div class="container">
        <form action="<?php echo SITEURL ?>login.php" class="search" method="POST">
            <input type="search" name="search" class="search-input" placeholder="Search restaurants, dishes or goods">
            <input type="submit" name="submit" class="submit-btn" value="search">
        </form>
    </div>
</section>

<!-- Category Section Starts -->
<section class="category-section">
    <div class="container">
        <div class="category-inner">
            <div class="title-block">
                <h1>Explore Foods</h1>
                <a class="see-all" href="<?php echo SITEURL; ?>login.php">
                    <div>See all</div>
                    <img alt="arrow-icon" src="./images/arrow-icon.svg">
                </a>
            </div>

            <div class="category-wrapper">
                <?php
                // Create SQL Query to Display Categories from Database
                $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3";

                // Execute the Query
                $res = mysqli_query($conn, $sql);

                // Count rows to check whether the category is available or not
                $count = mysqli_num_rows($res);

                if ($count > 0) {
                    // Categories Available
                    while ($row = mysqli_fetch_assoc($res)) {
                        // Get the Values like id, title, image_name
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        ?>
                        <a class="category-link" href="<?php echo SITEURL; ?>login.php">
                            <?php
                            // Check whether Image is available or not
                            if ($image_name == "") {
                                // Display Message
                                echo "<div class='error'>Image not Available</div>";
                            } else {
                                // Image Available
                                ?>
                                <img alt="category-images" class="category-image" src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>">
                                <?php
                            }
                            ?>
                            <div class="over-view"></div>
                            <h2 class="category-title"><?php echo $title; ?></h2>
                        </a>
                        <?php
                    }
                } else {
                    // Categories not Available
                    echo "<div class='error'>Category not Added.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Food Menu Section Starts -->
<section class="food-menu-section">
    <div class="container">
        <div class="food-menu-inner">

            <div class="title-block">
                <h1>Food Menu</h1>
                <a class="see-all" href="<?php echo SITEURL; ?>login.php">
                    <div>See all</div>
                    <img alt="arrow-icon" src="./images/arrow-icon.svg">
                </a>
            </div>

            <div class="food-menu-wrapper">
                <?php
                // Getting foods from database that are active and featured
                // SQL Query 
                $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='yes' LIMIT 6";

                // Execute the query
                $res2 = mysqli_query($conn, $sql2);

                // Count rows
                $count2 = mysqli_num_rows($res2);

                // Check whether the food is available or not 
                if ($count2 > 0) {
                    // Food available
                    while ($row = mysqli_fetch_assoc($res2)) {
                        // Get all the values
                        $id = $row['id'];
                        $title = $row['title'];
                        $price = $row['price'];
                        $description = $row['description'];
                        $image_name = $row['image_name'];
                        ?>

                        <div class="product-block">
                            <div class="product-image-block">
                                <?php
                                // Check whether image available or not 
                                if ($image_name == "") {
                                    // Image not available 
                                    echo "<div class='error'>Image is not Available.</div>";
                                } else {
                                    // Image is available
                                    ?>
                                    <img alt="food-image" class="product-image" src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>">
                                    <?php
                                }
                                ?>
                                <div class="shade"></div>
                                <div class="price">
                                 
                                    <div><?php echo $price; ?></div>
                                </div>
                                <h3 class="product-title"><?php echo $title; ?></h3>
                            </div>
                            <div class="product-content-block">
                                <h4><?php echo $description; ?></h4>
                                <a href="<?php echo SITEURL ?>login.php" class="order-now">Order Now</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // Food is not available
                    echo "<div class='error'>Food is not available.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section Starts -->

<!-- Reviews Section Ends -->

<!-- Shop Section Starts -->
<section class="shop-section">
    <div class="container">
        <div class="shop-wrapper">

            <div class="shop-top">

                <div class="title-block">
                    <h1>Shops</h1>
                </div>

                <div class="special-offer-block">

                    <div class="special-offer-inner bg-lpink bg-ice-cream">
                        <p class="special-offer-text">Special offers on</p>
                        <h2 class="special-offer-title">Ice Cream!</h2>
                        <a href="<?php echo SITEURL; ?>login.php" class="special-offer-btn">Discover</a>
                    </div>

                    <div class="special-offer-inner bg-lblue bg-pig">
                        <p class="special-offer-text">Free delivery</p>
                        <h2 class="special-offer-title">from $20</h2>
                        <a href="<?php echo SITEURL; ?>login.php" class="special-offer-btn">Shop now</a>
                    </div>

                    <div class="special-offer-inner bg-lorange bg-puff">
                        <p class="special-offer-text">-25% on bakery</p>
                        <h2 class="special-offer-title">after 20:00</h2>
                        <a href="<?php echo SITEURL; ?>login.php" class="special-offer-btn">See prices</a>
                    </div>
                </div>
            </div>

            <div class="shop-bottom">
                <img class="logo-image" alt="client-logo" src="./images/logo-01.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-02.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-03.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-04.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-05.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-06.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-07.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-08.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-09.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-10.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-11.svg">
                <img class="logo-image" alt="client-logo" src="./images/logo-12.svg">
            </div>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>