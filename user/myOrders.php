<?php

session_start();

function getDisplayName() {
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["userFullname"])) { // Check all conditions
        return htmlspecialchars($_SESSION["userFullname"]); 
    } else {
        return "Login";
    }
}

// Function to get the correct link (profile or login)
function getProfileLink() {
    return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true ? "userProfile.php" : "login.php"; 
}
function redirectLink(){
return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true ? "userLoggedIn.php" : "../Landingpage.php"; 
}

include '../database/config.php'; 

$userID = $_SESSION['id'];

// Fetch the user's orders
$orderQuery = "SELECT * FROM order_table WHERE userID = $userID";
$orderResult = mysqli_query($conn, $orderQuery);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - KMK</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/myOrders.css">
</head>
<body>
<header>
    <div class="top-container">

    </div>
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="<?php echo redirectLink(); ?>""><img class="logoHolder"src="../images/Logo-small.png" alt="Logo"></a>
            <button class="navbar-toggler custom-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span></button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <img class="offcanvas-title" id="offcanvasNavbarLabel" src="../images/Logo-small.png" alt="">
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1 pe-3 my-auto mx-auto">
                            <li class="nav-item">
                                <a class="nav-link mx-lg-4" href=""></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-4" href=""></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-4" href="Allproduct.php">All Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-4" href="Apparel.php">Apparel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-4" href="Footwear.php">Footwear</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-4" href="Equipment.php">Equipment</a>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center align-items-center gap-3 my-auto mx-auto">
                            <a class="nav-link mx-lg-2" href="" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Search"><i class="bi bi-search"></i></a>
                            <a class="nav-link mx-lg-2" href="myOrders.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="My Orders"><i class="bi bi-bag-check"></i></a>
                            <a class="nav-link mx-lg-2" href="myCart.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="My Cart"><i class="bi bi-cart2"></i></a>
                            <a class="nav-link mx-lg-2" href="<?php echo getProfileLink(); ?>" data-bs-toggle="tooltip"       data-bs-placement="bottom" title="<?php echo getDisplayName(); ?>"> 
                              <i class="bi bi-person"></i> 
                            </a>
                            <a class="nav-link mx-lg-2" href=""><i></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>   
    <div class="container mt-5">
            <h1 class="mb-4">My Orders</h1>

            <?php if (mysqli_num_rows($orderResult) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($orderResult)) : ?>
                                <tr>
                                    <td><?php echo $order['orderID']; ?></td>
                                    <td><?php echo $order['order_date']; ?></td>
                                    <td>₱<?php echo number_format($order['totalAmount'], 2); ?></td>
                                    <td><?php echo $order['orderStatus']; ?></td>
                                    <td>
                                        <a href="order_confirmation.php?orderID=<?php echo $order['orderID']; ?>" class="btn btn-info btn-sm">View Details</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <p>You have no past orders.</p>
            <?php endif; ?>
                
        </div>
</body>
</html>
