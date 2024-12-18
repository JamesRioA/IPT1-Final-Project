<?php 
session_start();

if (!isset($_SESSION["loggedinAdmin"]) || $_SESSION["loggedinAdmin"] !== true) {
    header("Location: adminLogin.php");
    exit();
}

if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: adminLogin.php");
    exit();
}
include '../database/config.php';

$orderQuery = "SELECT * FROM order_table ORDER BY order_date DESC"; 
$orderResult = mysqli_query($conn, $orderQuery);

if (!$orderResult) {
    die("Error fetching order data: " . mysqli_error($conn));
}

// Handle status update (if form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['orderID']) && isset($_POST['newStatus'])) {
        $orderID = $_POST['orderID'];
        $newStatus = $_POST['newStatus'];

        $updateQuery = "UPDATE order_table SET orderStatus = '$newStatus' WHERE orderID = $orderID";
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['success_message'] = 'Order status updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Error updating order status: ' . mysqli_error($conn);
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - KMK Admin</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="bootstrap\js\bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/dashboard.css">
</head>
<body>
<div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">KMK Apparel</a>
                </div>
            </div>
            <ul class="sidebar-nav">
              <li class="sidebar-item">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="lni lni-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="profile.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="items.php" class="sidebar-link">
                        <i class="lni lni-database"></i>
                        <span>Items</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="orders.php" class="sidebar-link">
                        <i class="bi bi-box-seam"></i>   
                        <span>Orders</span>
                    </a>
                </li>
            </ul>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <div class="navbar-collapse collapse">
                <h3 class="fw-bold fs-4 mb-3">User Orders</h3>
                </div>
                <div class="Logout" style="text-align:right;">
                    <a href="?logout=1" class="nav-link">
                        <i class="lni lni-exit"><span>Logout</span></i>
                    </a>
                </div>
            </nav>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="mb-3">
                    <?php 
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']); // Clear the message
                    } elseif (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']); // Clear the message
                    }
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>Order Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Shipping Address</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($orderResult)) : ?>
                                    <tr>
                                        <td><?php echo $order['orderID']; ?></td>
                                        <td><?php echo $order['userID']; ?></td>
                                        <td><?php echo $order['order_date']; ?></td>
                                        <td>₱<?php echo number_format($order['totalAmount'], 2); ?></td>
                                        <td>
                                            <form method="POST" action="orders.php">
                                                <input type="hidden" name="orderID" value="<?php echo $order['orderID']; ?>">
                                                <select name="newStatus" class="form-select">
                                                    <option value="Processing" <?php if ($order['orderStatus'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                                    <option value="Shipped" <?php if ($order['orderStatus'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                                    <option value="Delivered" <?php if ($order['orderStatus'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                                    <option value="Cancelled" <?php if ($order['orderStatus'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
                                            </form>
                                        </td>
                                        <td><?php echo $order['shippingAddress']; ?></td>
                                        <td><a href="view_order_details.php?orderID=<?php echo $order['orderID']; ?>" class="btn btn-info btn-sm">View Details</a></td> 
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>