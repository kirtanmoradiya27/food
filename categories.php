<?php include('partials-front/header.php') ?>

    <!-- Category Section Starts -->
    <section class="category-section">
        <div class="container">
            <div class="category-inner">
                <div style="text-align:center" class="title-block">
                    <h1 style="width:100%">Explore Foods</h1>
                </div>

                <div class="category-wrapper">
                    <?php
                            //Create SQL Query to Display CAtegories from Database
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes'";

                            //Execute the Query
                            $res = mysqli_query($conn, $sql);

                            //Count rows to check whether the category is available or not
                            $count = mysqli_num_rows($res);

                            if($count>0)
                            {
                                //CAtegories Available
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    //Get the Values like id, title, image_name
                                    $id = $row['id'];
                                    $title = $row['title'];
                                    $image_name = $row['image_name'];
                                    ?>
                                        <a class="category-link" href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>"> 
                                        <?php 
                                            //Check whether Image is available or not
                                            if($image_name=="")
                                            {
                                                //Display MEssage
                                                echo "<div class='error'>Image not Available</div>";
                                            }
                                            else
                                            {
                                                //Image Available
                                                ?>
                                                <img alt="category-images"style="Height: 100%" class="category-image" src="<?php echo SITEURL; ?>admin/pages/images/category/<?php echo $image_name; ?>">
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
                                //Categories not Available
                                echo "<div class='error'>Category not Added.</div>";
                            }
                        ?>
                </div>  
            </div>
        </div>
    </section>

<?php include('partials-front/footer.php') ?>