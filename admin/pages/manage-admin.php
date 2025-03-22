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
$name = "";
$email = "";
$status = "Active";

// Check if Edit button is clicked
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM tbl_admin WHERE id=$id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "id" => $row['id'],
            "name" => $row['name'],
            "email" => $row['email'],
            "status" => $row['status']
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit();
}

// Handle Form Submission (Add or Edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $status = $_POST['status'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Hash the password if it's provided
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    if (isset($_POST['admin_id']) && $_POST['admin_id'] !== "") {
        $id = intval($_POST['admin_id']);
        // Update admin
        if (!empty($password)) {
            $conn->query("UPDATE tbl_admin SET name='$name', email='$email', password='$password', status='$status' WHERE id=$id");
        } else {
            // Update without changing the password
            $conn->query("UPDATE tbl_admin SET name='$name', email='$email', status='$status' WHERE id=$id");
        }
    } else {
        // Insert new admin
        $conn->query("INSERT INTO tbl_admin (name, email, password, status) VALUES ('$name', '$email', '$password', '$status')");
    }

    header("Location: manage-admin.php");
    exit();
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tbl_admin WHERE id=$id");
    header("Location: manage-admin.php");
    exit();
}

// Fetch Admin Data
$sql = "SELECT * FROM tbl_admin";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Manage Admins</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                <h6 class="text-white text-capitalize ps-3">Admin Table</h6>
                                <button id="addAdminBtn" class="btn btn-success me-3" data-bs-toggle="modal"
                                    data-bs-target="#adminModal">+ Add Admin</button>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">
                                                Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Email</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Joined</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-4 py-2">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <?= htmlspecialchars($row['name']) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td class="text-center">
                                                <span
                                                    class='badge badge-sm <?= ($row['status'] == 'Active') ? "bg-gradient-success" : "bg-gradient-secondary" ?>'><?= $row['status'] ?></span>
                                            </td>
                                            <td class="text-center"><?= date("d/m/Y", strtotime($row['created_at'])) ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit-btn"
                                                    data-id="<?= $row['id'] ?>">Edit</button>
                                                <a href="manage-admin.php?delete=<?= $row['id'] ?>"
                                                    onclick="return confirm('Are you sure?');"
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

            <!-- Admin Modal -->
            <div id="adminModal" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Admin Form</h5>
                            <button type="button" class="close btn-close" data-bs-dismiss="modal"><i
                                    class="material-symbols-rounded text-dark">close</i></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <input type="hidden" name="admin_id" id="admin_id">
                                <div class="mb-3">
                                    <label class="text-dark">Name<span class="text-primary"> *</span></label>
                                    <input type="text" name="name" id="name" class="form-control bg-light p-1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Email<span class="text-primary"> *</span></label>
                                    <input type="email" name="email" id="email" class="form-control bg-light p-1"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Password</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control bg-light p-1">
                                    <small class="form-text text-muted">Leave empty to keep current password (when
                                        editing)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="text-dark">Status</label><br>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="status_active"
                                            value="Active" checked>
                                        <label for="status_active">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive"
                                            value="Inactive">
                                        <label for="status_inactive">Inactive</label>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        $(".edit-btn").click(function() {
            var id = $(this).data("id");

            $.ajax({
                url: "manage-admin.php?edit=" + id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Populate modal fields
                        $("#admin_id").val(response.id);
                        $("#name").val(response.name);
                        $("#email").val(response.email);
                        if (response.status === "Active") {
                            $("#status_active").prop("checked", true);
                        } else {
                            $("#status_inactive").prop("checked", true);
                        }
                        // Show modal
                        $("#adminModal").modal("show");
                    } else {
                        alert("Failed to load admin details.");
                    }
                },
                error: function() {
                    alert("Error fetching admin data.");
                }
            });
        });

        $("#addAdminBtn").click(function() {
            // Reset form fields for adding a new admin
            $("#admin_id").val("");
            $("#name").val("");
            $("#email").val("");
            $("#password").val(""); // Reset password field
            $("#status").val("Active");

            $("#adminModal").modal("show");
        });
    });
    </script>

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
</body>

</html>

<?php
$conn->close();
?>