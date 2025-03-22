
<?php include('partials-front/header.php'); ?>
<!-- Search Section Starts -->
    <section class="search-section">
        <div class="container">
            <form action="<?php echo SITEURL ?>food-search.php" class="search" method="POST">
                <input type="search" name="search" class="search-input" placeholder="Search restorants, dishes or goods">
                <input type="submit" name="submit" class="submit-btn" value="search" >
            </form>
        </div>
    </section>

    <!-- Category Section Starts -->
    <section class="category-section">
        <div class="container">
            <div class="category-inner">
                <div class="title-block">
                    <h1>Explore Foods</h1>
                    <a class="see-all" href="<?php echo SITEURL; ?>categories.php">
                        <div>See all</div>
                        <img alt="arrow-icon" src="./images/arrow-icon.svg">
                    </a>
                </div>

                <div class="category-wrapper">
                    <?php
                        $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3";
                        $res = mysqli_query($conn, $sql);
                        $count = mysqli_num_rows($res);

                        if($count>0)
                        {
                            while($row=mysqli_fetch_assoc($res))
                            {
                                $id = $row['id'];
                                $title = $row['title'];
                                $image_name = $row['image_name'];
                                ?>
                                    <a class="category-link" href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>"> 
                                    <?php 
                                        if($image_name=="")
                                        {
                                            echo "<div class='error'>Image not Available</div>";
                                        }
                                        else
                                        {
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
                        }
                        else
                        {
                            echo "<div class='error'>Category not Added.</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Food Menu Section Starts -->
    <section class="food-menu-section" style="padding-bottom: 0;">
        <div class="container">
            <div class="food-menu-inner">
                <div class="title-block">
                    <h1>Food Menu</h1>
                    <a class="see-all" href="./foods.php">
                        <div>See all</div>
                        <img alt="arrow-icon" src="./images/arrow-icon.svg">
                    </a>
                </div>
                <div class="food-menu-wrapper">
                    <?php
                        $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='yes' LIMIT 3";
                        $res2 = mysqli_query($conn, $sql2);
                        $count2 = mysqli_num_rows($res2);

                        if($count2>0){
                            while($row=mysqli_fetch_assoc($res2)){
                                $id = $row['id'];
                                $title = $row['title'];
                                $price = $row['price'];
                                $description = $row['description'];
                                $image_name = $row['image_name'];
                                ?>
                                    <div class="product-block">
                                        <div class="product-image-block">
                                        <?php
                                            if($image_name==""){
                                                echo"<div class='error'>Image is not Available.</div>";
                                            }
                                            else{
                                                ?>
                                                    <img alt="food-image" class="product-image" src="<?php echo SITEURL; ?>admin/pages/images/food/<?php echo $image_name; ?>">
                                                <?php
                                            }
                                        ?>
                                            <div class="shade"></div>
                                            <div class="price">
                                                <div>₹<?php echo $price; ?></div>
                                            </div>
                                            <h3 class="product-title"><?php echo $title; ?></h3>
                                        </div>
                                        <div class="product-content-block">
                                            <h4><?php echo $description; ?></h4>
                                            <a href="<?php echo SITEURL ?>order.php?food_id=<?php echo $id; ?>" class="order-now">Order Now</a>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
                        else{
                            echo "<div class='error'>Food is not available.</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section Starts -->
 <!-- Reviews Section Starts -->
 <section class="reviews-section" style="background-color: #f8f8f8; padding: 40px 0;">
        <div class="container">
            <div class="reviews-inner">
                <div class="title-block" style="text-align: center; margin-bottom: 20px;">
                    <h1 style="font-size: 2rem; color: #333;">Reviews</h1>
                </div>
                <div class="reviews-wrapper" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                <?php
    $sql3 = "SELECT * FROM tbl_reviews ORDER BY created_at DESC LIMIT 3";
    $result = mysqli_query($conn, $sql3);
?>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                                $name = htmlspecialchars($row["reviewer_name"]);
                                $rating = intval($row["rating"]);
                                $comment = htmlspecialchars($row["review"]);
                                $date = date("F j, Y", strtotime($row["created_at"]));
                            ?>
                            <div class="review-block" style="background: #000000; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px;">
                                <div class="review-header" style="display: flex; align-items: center; gap: 10px;">
                                    <img src="https://i.pravatar.cc/150?u=<?php echo urlencode($name); ?>" alt="<?php echo $name; ?>" class="review-avatar" style="border-radius: 50%; width: 50px; height: 50px;">
                                    <div class="review-info">
                                        <h3 class="reviewer-name" style="margin: 0; font-size: 1.2rem; color: #444;"><?php echo $name; ?></h3>
                                        <div class="review-rating" style="color: #f4c542;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star" style="font-size: 1.2rem; <?php echo ($i <= $rating) ? 'color: #f4c542;' : 'color: #ccc;'; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <p class="review-text" style="font-size: 1rem; color: #666; margin: 10px 0;">"<?php echo $comment; ?>"</p>
                                <span class="review-date" style="font-size: 0.9rem; color: #999;">Posted on <?php echo $date; ?></span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-600 text-center" style="text-align: center; color: #999; font-size: 1.2rem;">No reviews found.</p>
                    <?php endif; ?>
                    <?php $conn->close(); ?>
                </div>
            </div>
        </div>
    </section>


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
                            <a href="<?php echo SITEURL; ?>foods.php" class="special-offer-btn">Discover</a>
                        </div>
    
                        <div class="special-offer-inner bg-lblue bg-pig">
                            <p class="special-offer-text">Free delivery</p>
                            <h2 class="special-offer-title">from $20</h2>
                            <a href="<?php echo SITEURL; ?>foods.php" class="special-offer-btn">Shop now</a>
                        </div>
    
                        <div class="special-offer-inner bg-lorange bg-puff">
                            <p class="special-offer-text">-25% on bakery</p>
                            <h2 class="special-offer-title">after 20:00</h2>
                            <a href="<?php echo SITEURL; ?>foods.php" class="special-offer-btn">See prices</a>
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