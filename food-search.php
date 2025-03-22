<?php include('partials-front/header.php') ?>

    <!-- Page Header Section Starts -->
    <section class="page-header">
        <div class="container">

            <?php
                //get the search keyword
                $search = $_POST['search'];
            ?>
            <div class="page-title">
                <h1 class="page-heading">Foods on "<?php echo $search; ?>"</h1>
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
                    
                    // "SELECT * FROM tbl_food WHERE title LIKE '%burger'%' OR description LIKE '%burger%'";
                    $sql = "SELECT * FROM tbl_food WHERE title LIKE '%$search%' OR description LIKE '%$search%'";

                    //Execute the Query
                    $res = mysqli_query($conn, $sql);

                    //Count Rows
                    $count = mysqli_num_rows($res); 

                    //Check whether food available of not
                    if($count>0)
                    {
                        //Food Available
                        while($row=mysqli_fetch_assoc($res))
                        {
                            //Get the details
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
                                            <div>₹<?php echo $price; ?></div>
                                        </div>
                                        <h3 class="product-title"><?php echo $title; ?></h3>
                                    </div>
                                    <div class="product-content-block">
                                        <h4><?php echo $description; ?></h4>
                                        <a href="./order.html" class="order-now">Order Now</a>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                    else
                    {
                        echo "<div class='error'>Food not found.</div>";
                    }
                ?>

                </div>
            </div>
        </div>
    </section>

<?php include('partials-front/footer.php') ?>