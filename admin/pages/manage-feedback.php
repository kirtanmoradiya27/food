<?php
session_start();
include('../db.php');

if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    $sql = "UPDATE tbl_feedback SET is_active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    $stmt->execute();
    $stmt->close();

    // Force page refresh after updating status
    header("Location: manage-feedback.php");
    exit();
}

// Fetch Feedback Data
$sql = "SELECT f.*, u.name AS username, fo.title AS food_name 
        FROM tbl_feedback f 
        JOIN tbl_users u ON f.user_id = u.id 
        LEFT JOIN tbl_food fo ON f.food_id = fo.id 
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Manage Feedback</title>
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
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
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center justify-content-between">
                                <h6 class="text-white text-capitalize ps-3">Feedback</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">ID</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">User</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Food Item</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Feedback</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Date</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['food_name'] ?? 'N/A') ?></td>
                                            <td><?= nl2br(htmlspecialchars($row['feedback_text'])) ?></td>
                                            <td><?= date('M d, Y H:i', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <form method="POST" action="manage-feedback.php">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <input type="hidden" name="status" value="<?= $row['is_active'] == 0 ? 1 : 0 ?>">
                                                    <button type="submit" class="btn btn-sm <?= $row['is_active'] == 0 ? 'btn-danger' : 'btn-success' ?>">
                                                        <?= $row['is_active'] == 0 ? 'Not Active' : 'Active' ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

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

</html>
