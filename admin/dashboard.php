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

$salesQuery = "SELECT productCategory, SUM(invoiceAmount) AS totalSales 
                FROM sales_invoice_table
                GROUP BY productCategory
                ORDER BY totalSales DESC
                LIMIT 5"; // Get top 5 categories
$salesResult = mysqli_query($conn, $salesQuery);

if (!$salesResult) {
    die("Error fetching sales data: " . mysqli_error($conn));
}

function getTop5Products($conn)
{
    // Replace the placeholders with your actual column names from sales_invoice_table
    $sql = "SELECT p.productName, p.productImage, p.productBrand, od.price, SUM(od.quantity) AS quantitySold
            FROM order_details_table od
            JOIN product_table p ON od.productID = p.productID
            GROUP BY od.productID
            ORDER BY quantitySold DESC
            LIMIT 5";
    $result = mysqli_query($conn, $sql);

    $topProducts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $topProducts[] = $row;
    }
    return $topProducts;
}

$topProducts = getTop5Products($conn);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMK - Admin Dashboard</title>
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
                <h3 class="fw-bold fs-4 mb-3">Admin Dashboard</h3>
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
                        <div class="row">
                        <?php 
                            $rank = 1; // Initialize rank counter
                            while ($row = mysqli_fetch_assoc($salesResult)) : ?>
                                <div class="col-12 col-md-4">
                                    <div class="card border-0">
                                        <div class="card-body py-4">
                                            <h5 class="mb-2 fw-bold"><?php echo $row['productCategory']; ?></h5>
                                            <p class="mb-2 fw-bold">₱<?php echo number_format($row['totalSales'], 2); ?></p>
                                            <div class="mb-0">
                                                <span class="badge text-primary me-2">Rank <?php echo $rank++; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        </div>
                        <h3 class="fw-bold fs-4 my-3">Top 5 Products
                        </h3>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="highlight">
                                            <th scope="col">Rank #</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Brand</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">No. of Items Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $rank = 1;
                                    foreach ($topProducts as $product) {
                                        echo "<tr>";
                                        echo "<th scope='row'>$rank</th>";
                                        echo "<td><img src='../productImages/". $product['productImage'] . "' style='width:50px; height:50px;'></td>"; // Add image column
                                        echo "<td>" . $product['productName'] . "</td>";
                                        echo "<td>" . $product['productBrand'] . "</td>";
                                        echo "<td>" . $product['price'] . "</td>";
                                        echo "<td>" . $product['quantitySold'] . "</td>";
                                        echo "</tr>";
                                        $rank++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
