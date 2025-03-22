<?php include('partials-front/header.php'); ?>



<section class="contact-section">
    <div class="container">
        <div class="contact-inner">
            
            <!--Contact Top Starts-->
            <div class="title-inner">
                <a href="./index.php" class="go-back-link">
                    <img class="left-arrow" src="./images/back-arrow-icon.svg">
                    <div>Back to main page</div>
                </a>

                <h1>Contacts</h1>
                <?php
                    if(isset($_SESSION['thank'])){    //checking weather the session is set or not
                        echo $_SESSION['thank'];  //Display the Session Message if set 
                        unset($_SESSION['thank']);    //Remove the session Message
                    }
                ?>
            </div>

            <!--Contact Bottom Starts-->
            <div class="contact-bottom">

                <!--Contact Bottom Left Starts-->
                <div class="contact-bottom-left">
                    <div class="contact-title-block">
                        <h2 class="contact-title">Contact us</h2>
                        <p class="contact-paragraph">If you have a question or a problem — contact us via this form. We will answer shortly!</p>
                    </div>
                    
                    <form class="contact-form" method="POST">
                        <div class="field-block">
                            <label for="" class="label">Name</label>
                            <input id="name" class="text-input" type="text" name="name" placeholder="Name" required>
                        </div>
                        <div class="field-block">
                            <label for="" class="label">Email</label>
                            <input class="text-input" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="field-block">
                            <label for="" class="label">Your question</label>
                            <textarea class="text-input textarea-input" type="text" name="your_question" placeholder="Your question"></textarea>
                        </div>

                        <input type="submit" class="btn-submit" name="submit">
                    </form>
                </div>

                <!--Contact Bottom right Starts-->
                <div class="contact-bottom-right">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8351.374662403712!2d0.0015685873749142426!3d51.51544424805373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a7e14d7cba57%3A0x9494d146606fff32!2s73%20Barking%20Rd%2C%20London%20E16%204HB%2C%20UK!5e0!3m2!1sen!2sin!4v1679123021408!5m2!1sen!2sin" width="100%" height="325" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <div class="contact-content">
                        <div class="contact-block">
                            <h3 class="social-title">Social media</h3>
                            <div class="follow-link">
                                <a target="_blank" href="https://www.facebook.com" class="social-link"> <img src="./images/facebook-icon.svg" alt="facebook-icon"></a>
                                <a target="_blank" href="https://www.instagram.com" class="social-link"><img src="./images/instagram-icon.svg" alt="twitter-icon"></a>
                            </div>
                        </div>
                        <div class="contact-block">
                            <h3 class="social-title">Our Location</h3>
                            <div class="contact-link">1 Carnaby Street, London,<br>W1F 9PS, England</div>
                        </div>
                        <div class="contact-block">
                            <h3 class="social-title">Contact No</h3>
                            <a href="tel:(+91 8320917663)" class="contact-link">+91 8320917663</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //validtion for Full name
        $(document).ready(function() {
            $("form").submit(function(event) {
                var name = $("#name").val();
                var nameRegex = /^[A-Za-z\s]+$/;
                if (name == "") {
                    alert("Full Name is required.");
                    event.preventDefault();
                }
                else if (!nameRegex.test(name)){
                    alert("Invalid Full Name. Please enter letters and spaces only.");
                    event.preventDefault();
                }
            });
        });
    </script>
</section>

<?php include('partials-front/footer.php'); ?>

<?php
    // Process the value from form and save it in Database
    
    //Check whether the submit button is clicked or not

    if(isset($_POST['submit'])){
        //1. Get the Data from form
    
        $name = $_POST['name'];
        $email = $_POST['email'];
        $your_question = $_POST['your_question'];

        //2. SQl Query to save the data into Database

        $sql2 = "INSERT INTO tbl_contact SET
            name= '$name',
            email= '$email',
            your_question= '$your_question'
        ";

        //3. Executing Query and Saving Data into Datbase 

        $res2 = mysqli_query($conn, $sql2) or die(mysqli_error());
        
        //check weather the query executed successfully or not
        if($res2==true)
        {
            //Query executed and admin updated
            $_SESSION['thank'] = "<div class='success'>Your Response submited Successfully.</div>";
        }
        else
        {
            //Failed to update admin
            $_SESSION['thank'] = "<div class='error'>Failed to Submit Form.</div>";
        }
    }
?>