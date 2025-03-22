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
    $user_id = "";
    $total_price = "";
    $order_date = "";
    $status = "Ordered";
    $customer_name = "";
    $customer_contact = "";
    $customer_email = "";
    $customer_address = "";

    // Fetch Foods for dropdown
    $food_sql = "SELECT * FROM tbl_food WHERE active='Yes'";
    $food_result = $conn->query($food_sql);

    // Handle Order Details View
    if (isset($_GET['view'])) {
        $order_id = intval($_GET['view']);
        // This will be handled in the HTML part to show a modal with order items
    }

// Get order items for an order
if (isset($_GET['get_items'])) {
    $order_id = intval($_GET['get_items']);
    $items_sql = "SELECT oi.*, f.title AS food_name FROM tbl_order_items oi JOIN tbl_food f ON oi.food_id = f.id WHERE oi.order_id = $order_id";
    $items_result = $conn->query($items_sql);

    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }

    echo json_encode(["success" => true, "items" => $items]);
    exit();
}

// Get customer information for an order
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT o.*,u.name AS username,u.email AS user_email,u.mobile AS user_contact,u.address AS user_address,oi.food_id, oi.price, oi.quantity FROM tbl_orders o JOIN tbl_users u ON o.user_id = u.id JOIN tbl_order_items oi ON oi.order_id = o.id WHERE o.id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "id" => $row['id'],
            "user_id" => $row['user_id'],
            "total_price" => $row['total_price'],
            "order_date" => $row['order_date'],
            "status" => $row['status'],
            "customer_name" => $row['username'],
            "customer_email" => $row['user_email'],
            "customer_contact" => $row['user_contact'],
            "customer_address" => $row['user_address']
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit();
}

    // Handle Form Submission (Add or Edit)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update_order'])) {
            $id = intval($_POST['order_id']);
            $status = $conn->real_escape_string($_POST['status']);

            // Update order status
            $conn->query("UPDATE tbl_orders SET
                status='$status'
                WHERE id=$id");

            $_SESSION['update'] = "<div class='success'>Order Updated Successfully.</div>";
            header("Location: manage-order.php");
            exit();
        }
    }

    // Handle Deletion
    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);

        // First delete the order items
        $conn->query("DELETE FROM tbl_order_items WHERE order_id=$id");

        // Then delete the order
        $conn->query("DELETE FROM tbl_orders WHERE id=$id");

        $_SESSION['delete'] = "<div class='success'>Order Deleted Successfully.</div>";
        header("Location: manage-order.php");
        exit();
    }

// Fetch Order Data
$sql = "SELECT o.*, u.name as username FROM tbl_orders o LEFT JOIN tbl_users u ON o.user_id = u.id ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Manage Orders</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
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
                                <h6 class="text-white text-capitalize ps-3">Orders</h6>
                            </div>
                        </div>

                        <div class="card-body px-0 pb-2">
                            <?php
                                if(isset($_SESSION['update']))
                                {
                                    echo '<div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                                            '.$_SESSION['update'].'
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                          </div>';
                                    unset($_SESSION['update']);
                                }

                                if(isset($_SESSION['delete']))
                                {
                                    echo '<div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                                            '.$_SESSION['delete'].'
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                          </div>';
                                    unset($_SESSION['delete']);
                                }
                            ?>

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                                Order ID</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                                                Customer</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                                                Total Amount</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                                                Order Date</th>
                                            <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                                                Status</th>
                                            <th class="text-secondary opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-2">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <?= $row['id'] ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $row['username'] ?? 'User #' . $row['user_id'] ?></td>
                                            <td>₹<?= number_format($row['total_price'], 2) ?></td>
                                            <td><?= date('M d, Y H:i', strtotime($row['order_date'])) ?></td>
                                            <td>
                                                <?php
                $status = $row['status'];
                $badge_class = "bg-gradient-secondary";

                if($status == "Ordered") {
                    $badge_class = "bg-gradient-info";
                } elseif($status == "Processing") {
                    $badge_class = "bg-gradient-warning";
                } elseif($status == "Delivered") {
                    $badge_class = "bg-gradient-success";
                } elseif($status == "Cancelled") {
                    $badge_class = "bg-gradient-danger";
                }
            ?>
                                                <span class="badge badge-sm <?= $badge_class ?>"><?= $status ?></span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info view-items-btn"
                                                    data-id="<?= $row['id'] ?>">
                                                    view items
                                                </button>
                                                <button class="btn btn-sm btn-primary edit-btn"
                                                    data-id="<?= $row['id'] ?>">
                                                    update
                                                </button>
                                                <a href="manage-order.php?delete=<?= $row['id'] ?>"
                                                    onclick="return confirm('Are you sure you want to delete this order?');"
                                                    class="btn btn-sm btn-danger">
                                                    delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No orders available</td></tr>";
    }
    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Modal -->
        <div id="orderItemsModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order #<span id="order-id-display"></span> Items</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            <i class="material-symbols-rounded text-dark">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Item</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Price</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Quantity</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="order-items-body">
                                    <!-- Items will be loaded here via AJAX -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th id="order-total">₹0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-4">
                            <h6>Customer Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> <span id="customer-name-display"></span></p>
                                    <p><strong>Contact:</strong> <span id="customer-contact-display"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> <span id="customer-email-display"></span></p>
                                    <p><strong>Address:</strong> <span id="customer-address-display"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Status Update Modal -->
        <div id="updateStatusModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            <i class="material-symbols-rounded text-dark">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="updateStatusForm">
                            <input type="hidden" name="order_id" id="edit_order_id">
                            <input type="hidden" name="update_order" value="1">

                            <div class="mb-3">
                                <label class="text-dark">Status</label>
                                <select name="status" id="edit_status" class="form-control bg-light p-2">
                                    <option value="Ordered">Ordered</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

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


<script>
$(document).ready(function() {
    // View Order Items button functionality
    $(document).on("click", ".view-items-btn", function(e) {
        e.preventDefault();
        var orderId = $(this).data("id");
        $("#order-id-display").text(orderId);

        // Fetch order items
        $.ajax({
            url: "manage-order.php?get_items=" + orderId,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var items = response.items;
                    var itemsHtml = "";
                    var total = 0;

                    // Generate HTML for each item
                    items.forEach(function(item) {
                        var subtotal = parseFloat(item.price) * parseInt(item
                            .quantity);
                        total += subtotal;

                        itemsHtml += `<tr>
                            <td>${item.food_name}</td>
                            <td>₹${parseFloat(item.price).toFixed(2)}</td>
                            <td>${item.quantity}</td>
                            <td>₹${subtotal.toFixed(2)}</td>
                        </tr>`;
                    });

                    // Update the modal with items
                    $("#order-items-body").html(itemsHtml);
                    $("#order-total").text("₹" + total.toFixed(2));

                    // Also fetch customer info
                    $.ajax({
                        url: "manage-order.php?edit=" + orderId,
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $("#customer-name-display").text(response
                                    .customer_name);
                                $("#customer-contact-display").text(response
                                    .customer_contact);
                                $("#customer-email-display").text(response
                                    .customer_email);
                                $("#customer-address-display").text(response
                                    .customer_address);
                            }
                        }
                    });

                    // Show the modal
                    $("#orderItemsModal").modal("show");
                }
            }
        });
    });

    // Edit Status button functionality
    $(document).on("click", ".edit-btn", function(e) {
        e.preventDefault();
        var orderId = $(this).data("id");

        $.ajax({
            url: "manage-order.php?edit=" + orderId,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#edit_order_id").val(response.id);
                    $("#edit_status").val(response.status);

                    // Show the modal
                    $("#updateStatusModal").modal("show");
                }
            }
        });
    });

    // Delete button functionality
    $(document).on("click", ".delete-btn", function(e) {
        e.preventDefault();
        var orderId = $(this).data("id");

        if (confirm("Are you sure you want to delete this order?")) {
            $.ajax({
                url: "manage-order.php?delete=" + orderId,
                type: "GET",
                success: function() {
                    location.reload();
                }
            });
        }
    });

    // Automatically remove success messages after 3 seconds
    setTimeout(function() {
        $(".alert").fadeOut("slow", function() {
            $(this).remove(); // Remove the alert from the DOM
        });
    }, 3000); // 3 seconds delay
});
</script>