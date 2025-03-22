<?php include('partials-front/header.php') ?>

    <?php

        //CHeck whether id is passed or not
        if(isset($_GET['category_id']))
        {
            //Category id is set and get the id
            $category_id = $_GET['category_id'];

            // Get the CAtegory Title Based on Category ID
            $sql = "SELECT title FROM tbl_category WHERE id=$category_id";

            //Execute the Query
            $res = mysqli_query($conn, $sql);

            //Get the value from Database
            $row = mysqli_fetch_assoc($res);

            //Get the TItle
            $category_title = $row['title'];
        }
        else
        {
            //Category not passed
            //redirect to home page 
            header('location:' .SITEURL);
        }
    ?>

    <!-- Page Header Section Starts -->
    <section class="page-header">
        <div class="container">
            <div class="page-title">
                <h1 class="page-heading">Foods on "<?php echo $category_title ?>"</h1>
            </div>
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
                    $sql2 = "SELECT * FROM tbl_food WHERE category_id=$category_id";

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
                                                
                                                <img alt="food-image" class="product-image" src="<?php echo SITEURL; ?>admin/pages/images/food/<?php echo $image_name; ?>">
                                            <?php
                                        }
                                    ?>
                                        <div class="shade"></div>
                                        <div class="price">
                                            <img src="./images/dollar-image.svg" alt="star-icon">
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
                        //food is not available
                        echo "<div class='error'>Food is not available.</div>";
                    }

                ?>

                </div>
            </div>
        </div>
    </section>

<?php include('partials-front/footer.php') ?>