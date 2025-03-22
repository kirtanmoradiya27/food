<?php 
include('partials-front/header.php');
include('db.php');

// Ensure User is Logged In
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to place an order.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check whether food id is set
if (!isset($_GET['food_id'])) {
    echo "<script>window.location.href='http://localhost/food-order/';</script>";
    exit();
}

$food_id = $_GET['food_id'];
$sql = "SELECT * FROM tbl_food WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $food_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $price = $row['price'];
    $image_name = $row['image_name'];
} else {
    echo "<script>window.location.href='http://localhost/food-order/';</script>";
    exit();
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $qty = $_POST['qty'];
    $total = $price * $qty;
    $order_date = date("Y-m-d H:i:s");
    $status = "Ordered";
    $customer_name = $_POST['full-name'];
    $customer_contact = $_POST['contact'];
    $customer_email = $_POST['email'];
    $customer_address = $_POST['address'];

    // Start transaction for data consistency
    $conn->begin_transaction();
    
    try {
        // First insert main order - check if prepare statement works
        $sql2 = "INSERT INTO tbl_orders (user_id, total_price, order_date, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql2);
        
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("idss", $user_id, $total, $order_date, $status);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $order_id = $stmt->insert_id;
        $stmt->close();
        
        // Then insert order items
        $sql3 = "INSERT INTO tbl_order_items (order_id, food_id, price, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql3);
        
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("idii", $order_id, $food_id, $price, $qty);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
        
        // If we got here, commit the transaction
        $conn->commit();
        
        $_SESSION['order'] = "<div style='text-align:center;padding-bottom:10px;' class='success'>Food Ordered Successfully</div>";
        // Use JavaScript redirect instead of header()
        echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "?food_id=" . $food_id . "&success=1';</script>";
        exit();
    } catch (Exception $e) {
        // If there's an error, roll back
        $conn->rollback();
        $_SESSION['order'] = "<div class='error text-center'>Failed to Order Food: " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    /* Order History Table Styling */
    .order-history {
        padding: 4% 0;
        background: #f9f9f9;
        width: 100%;
    }
    
    .order-history h2 {
        color: #ff6b81;
        font-size: 2rem;
        margin-bottom: 30px;
    }
    
    .order-table-wrapper {
        overflow-x: auto;
    }
    
    .order-table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .order-table th, 
    .order-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .order-table th {
        background-color: #ff6b81;
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .order-table tr:hover {
        background-color: #f5f5f5;
    }
    
    .order-table tr:last-child td {
        border-bottom: none;
    }
    
    
.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.status-ordered {
    background-color: #ffeaa7;  /* Light yellow */
    color: #b7791f;  /* Dark amber */
}

.status-processing {
    background-color: #fef9c3;  /* Light yellow */
    color: #854d0e;  /* Dark amber */
}

.status-preparing {
    background-color: #bee3f8;  /* Light blue */
    color: #2b6cb0;  /* Dark blue */
}

.status-ontheway {
    background-color: #c6f6d5;  /* Light green */
    color: #2f855a;  /* Dark green */
}

.status-delivered {
    background-color: #9ae6b4;  /* Medium green */
    color: #276749;  /* Dark green */
}

.status-cancelled {
    background-color: #fed7d7;  /* Light red */
    color: #c53030;  /* Dark red */
}

.btn-primary {
    background-color: #007bff; /* Bootstrap primary blue */
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

.btn-primary:hover {
    background-color: #0056b3; /* Darker blue for hover effect */
}

.btn-primary:active {
    background-color: #004085; /* Even darker blue for active click */
}

.btn-primary:disabled {
    background-color: #a0c4ff; /* Muted blue for disabled state */
    cursor: not-allowed;
}
    
    .text-center {
        text-align: center;
    }
    
    
    @media screen and (max-width: 768px) {
        .order-table th, 
        .order-table td {
            padding: 10px;
            font-size: 0.9rem;
        }
        
        .order-history h2 {
            font-size: 1.5rem;
        }
    }
</style>




<section class="order-section">
    <div class="container">
        <form method="POST" class="order-wrapper">
            <h1 class="order-title">Fill this form to confirm your order.</h1>
            <?php if(isset($_SESSION['order'])) { echo $_SESSION['order']; unset($_SESSION['order']); } ?>
            <div class="order-inner mb-40">
                <h2>Selected Food</h2>
                <div class="product-info">
                    <?php if($image_name != "") { ?>
                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" class="img-responsive img-curve">
                    <?php } else { echo "<div class='error'>Image not Available.</div>"; } ?>
                    <div class="food-content">
                        <h1 class="food-title"><?php echo $title; ?></h1>
                        <p><?php echo $price; ?></p>
                        <label>Quantity</label>
                        <input type="number" name="qty" class="text-input" value="1" min="1" required>
                    </div>
                </div>
                <button type="submit"  class="btn-primary">Confirm Order</button>
            </div>
            <!-- <div class="order-inner"> -->
            <section class="order-history">
    <div class="">
        <h2 class="text-center">Your Order History</h2>
        <div class="order-table-wrapper">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Food</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get user's order history - ensure we only get THIS user's orders
                    $history_sql = "SELECT o.id, o.total_price, o.order_date, o.status, 
                                    f.title, oi.quantity 
                                    FROM tbl_orders o
                                    JOIN tbl_order_items oi ON o.id = oi.order_id
                                    JOIN tbl_food f ON oi.food_id = f.id
                                    WHERE o.user_id = ?
                                    ORDER BY o.order_date DESC";
                    
                    $stmt = $conn->prepare($history_sql);
                    $stmt->bind_param("i", $user_id); // This ensures only logged-in user orders appear
                    $stmt->execute();
                    $history_result = $stmt->get_result();
                    
                    if ($history_result->num_rows > 0) {
                        while($order = $history_result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['title']; ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td><?php echo number_format($order['total_price'], 2); ?></td>
                                <td><?php echo date('d/m/y', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">No order history found.</td>
                        </tr>
                        <?php
                    }
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
            <!-- </div> -->
        </form>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>