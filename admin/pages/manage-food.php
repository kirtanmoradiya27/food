<?php
    session_start();
    include('../db.php');

    // Check if admin is logged in
    if (!isset($_SESSION['admin'])) {
        header("Location: ../index.php"); // Redirect to login page
        exit();
    }
// Default Form Values
$id = "";
$title = "";
$description = "";
$price = "";
$image_name = "";
$category_id = "";
$featured = "Yes";
$active = "Yes";

// Fetch Categories for the dropdown
$category_sql = "SELECT * FROM tbl_category";
$category_result = $conn->query($category_sql);

// Check if Edit button is clicked
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM tbl_food WHERE id=$id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "id" => $row['id'],
            "title" => $row['title'],
            "description" => $row['description'],
            "price" => $row['price'],
            "image_name" => $row['image_name'],
            "category_id" => $row['category_id'],
            "featured" => $row['featured'],
            "active" => $row['active']
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit();
}

// Handle Form Submission (Add or Edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $featured = $_POST['featured'];
    $active = $_POST['active'];
    $current_image = isset($_POST['current_image']) ? $_POST['current_image'] : '';

    // Initialize image_name with current_image (for edit case)
    $image_name = $current_image;

    // Handle file upload only if a new file is selected
    if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] == 0 && $_FILES['image_name']['size'] > 0) {
        $image_name = $_FILES['image_name']['name'];
        $target_dir = "admin/pages/images/food/";
        $target_file = $target_dir . basename($image_name);
        move_uploaded_file($_FILES['image_name']['tmp_name'], $target_file);
    }

    if (isset($_POST['food_id']) && $_POST['food_id'] !== "") {
        $id = intval($_POST['food_id']);
        // Update food
        $conn->query("UPDATE tbl_food SET title='$title', description='$description', price='$price', image_name='$image_name', category_id='$category_id', featured='$featured', active='$active' WHERE id=$id");
    } else {
        // Insert new food
        $conn->query("INSERT INTO tbl_food (title, description, price, image_name, category_id, featured, active) VALUES ('$title', '$description', '$price', '$image_name', '$category_id', '$featured', '$active')");
    }

    header("Location: manage-food.php");
    exit();
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tbl_food WHERE id=$id");
    header("Location: manage-food.php");
    exit();
}

// Fetch Food Data
$sql = "SELECT * FROM tbl_food";
$result = $conn->query($sql);
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Manage Food</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- jQuery (missing in original file) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include('sidebar.php'); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-2">
            <?php include('navbar.php'); ?>
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div
                                class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center justify-content-between">
                                <h6 class="text-white text-capitalize ps-3">Foods Table</h6>
                                <button id="addFoodBtn" class="btn btn-success me-3" data-bs-toggle="modal"
                                    data-bs-target="#foodModal">+ Add Food</button>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">
                                                Title</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Description</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Price</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Image</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Category ID</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Featured</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Active</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-4 py-2">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <?= htmlspecialchars($row['title']) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['description']) ?></td>
                                            <td><?= htmlspecialchars($row['price']) ?></td>
                                            <td>
                                                <?php if ($row['image_name']): ?>
                                                <img src="./admin/pages/images/food/<?= htmlspecialchars($row['image_name']) ?>"
                                                    width="100" class="rounded">
                                                <?php else: ?>
                                                <div class='error'>Image not added.</div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?= htmlspecialchars($row['category_id']) ?></td>
                                            <td class="text-center">
                                                <span
                                                    class='badge badge-sm <?= ($row['featured'] == 'Yes') ? "bg-gradient-success" : "bg-gradient-secondary" ?>'><?= $row['featured'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class='badge badge-sm <?= ($row['active'] == 'Yes') ? "bg-gradient-success" : "bg-gradient-secondary" ?>'><?= $row['active'] ?></span>
                                            </td>
                                            <td>
                                                <button id="edit-btn" class="btn btn-sm btn-primary edit-btn"
                                                    data-id="<?= $row['id'] ?>">Edit</button>
                                                <a href="manage-food.php?delete=<?= $row['id'] ?>"
                                                    onclick="return confirm('Are you sure you want to delete this food item?');"
                                                    class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Food Modal -->
            <div id="foodModal" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Food Form</h5>
                            <button type="button" class="close btn-close" data-bs-dismiss="modal"><i
                                    class="material-symbols-rounded text-dark">close</i></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="food_id" id="food_id">
                                <input type="hidden" name="current_image" id="current_image">
                                <div class="mb-3">
                                    <label class="text-dark">Title<span class="text-primary"> *</span></label>
                                    <input type="text" name="title" id="title" class="form-control bg-light p-1 "
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Description<span class="text-primary"> *</span></label>
                                    <textarea name="description" id="description" class="form-control bg-light p-1"
                                        required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Price<span class="text-primary"> *</span></label>
                                    <input type="number" name="price" id="price" class="form-control bg-light p-1"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Image</label>
                                    <div id="current_image_display" class="mb-2" style="display:none;">
                                        <p>Current Image: <span id="image_name_display"></span></p>
                                        <img id="preview_current_image" src="" width="100" class="rounded">
                                    </div>
                                    <input type="file" name="image_name" id="image_name"
                                        class="form-control bg-light p-1">
                                    <small class="form-text text-muted">Leave empty to keep current image (when
                                        editing)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Category ID<span class="text-primary"> *</span></label>
                                    <select name="category_id" id="category_id" class="form-control bg-light p-1"
                                        required>
                                        <option value="">Select Category</option>
                                        <?php
                                        // Reset the category result pointer to the beginning
                                        $category_result->data_seek(0);
                                        while ($category = $category_result->fetch_assoc()):
                                        ?>
                                        <option value="<?= $category['id'] ?>">
                                            <?= htmlspecialchars($category['title']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Featured</label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="featured" id="featured_yes"
                                            value="Yes" checked>
                                        <label class="form-check-label" for="featured_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="featured" id="featured_no"
                                            value="No">
                                        <label class="form-check-label" for="featured_no">No</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Active</label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="active" id="active_yes"
                                            value="Yes" checked>
                                        <label class="form-check-label" for="active_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="active" id="active_no"
                                            value="No">
                                        <label class="form-check-label" for="active_no">No</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

    <!-- Script for Edit and Add Food functionality -->
    <script>
    $(document).ready(function() {
        // Handle Edit Button Click
        $(".edit-btn").click(function() {
            var id = $(this).data("id");

            $.ajax({
                url: "manage-food.php?edit=" + id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Populate modal fields
                        $("#food_id").val(response.id);
                        $("#title").val(response.title);
                        $("#description").val(response.description);
                        $("#price").val(response.price);
                        if (response.image_name) {
                            $("#current_image").val(response.image_name);
                            $("#image_name_display").text(response.image_name);
                            $("#preview_current_image").attr("src", "./admin/pages/images/food/" +
                                response.image_name);
                            $("#current_image_display").show();
                        } else {
                            $("#current_image_display").hide();
                        }
                        $("#image_name").val("");
                        $("#category_id").val(response.category_id);
                        // Set radio buttons
                        if (response.featured == "Yes") {
                            $("#featured_yes").prop("checked", true);
                        } else {
                            $("#featured_no").prop("checked", true);
                        }

                        if (response.active == "Yes") {
                            $("#active_yes").prop("checked", true);
                        } else {
                            $("#active_no").prop("checked", true);
                        }

                        // Show modal
                        $("#foodModal").modal("show");
                    } else {
                        alert("Failed to load food details.");
                    }
                },
                error: function() {
                    alert("Error fetching food data.");
                }
            });
        });

        // Handle Add Button Click
        $("#addFoodBtn").click(function() {
            // Reset form fields for adding a new food item
            $("#food_id").val("");
            $("#title").val("");
            $("#description").val("");
            $("#price").val("");
            $("#current_image").val(""); // Reset current image
            $("#image_name").val(""); // Reset file input
            $("#image_name_display").text(""); // Clear displayed image name
            $("#preview_current_image").hide(); // Hide current image preview
            $("#category_id").val("");
            $("input[name='featured'][value='Yes']").prop("checked", true); // Set default to Yes
            $("input[name='active'][value='Yes']").prop("checked", true); // Set default to Yes

            // Show modal
            $("#foodModal").modal("show");
        });
    });
    </script>