<?php include('partials-front/header.php') ?>

    <!-- Search Section Starts -->
    <section class="search-section">
        <div class="container">
            <form action="<?php echo SITEURL ?>food-search.php" class="search" method="POST">
                <input type="search" name="search" class="search-input" placeholder="Search restorants, dishes or goods">
                <input type="submit" name="submit" class="submit-btn" value="search">
            </form>
        </div>
    </section>

    <!-- Food Menu Section Starts -->
    <section class="food-menu-section">
        <div class="container">
            <div class="food-menu-inner">

                <div style="text-align:center" class="title-block">
                    <h1 style="width:100%">Food Menu</h1>
                </div>

                <div class="food-menu-wrapper">

                <?php

                    //getting foods from database that are active and featured
                    //SQl Query 
                    $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='yes'";

                    //Execute the query
                    $res2 = mysqli_query($conn, $sql2);

                    //Count rows
                    $count2 = mysqli_num_rows($res2);

                    //check weather the food is available or not 
                    if($count2>0){
                        // food available
                        while($row=mysqli_fetch_assoc($res2)){
                            //Get all the values
                            $id = $row['id'];
                            $title = $row['title'];
                            $price = $row['price'];
                            $description = $row['description'];
                            $image_name = $row['image_name'];
                            ?>

                                <div class="product-block">
                                    <div class="product-image-block">
                                    <?php
                                        //check weather image available or not 
                                        if($image_name==""){
                                            //Image not available 
                                            echo"<div class='error'>Image is not Available.</div";
                                        }
                                        else{
                                            //image is available
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
                                        <a href="<?php echo SITEURL ?>order.php?food_id=<?php echo $id; ?>" class="order-now">Order Now</a>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                    else{
                        //food is not available
                        echo "<div class='error'>Food is not available.</div>";
                    }

                ?>

                </div>
            </div>
        </div>
    </section>

<?php include('partials-front/footer.php') ?>