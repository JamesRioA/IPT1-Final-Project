<?php
session_start();
include '../database/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    // Check if cart item ID is set
    if (isset($_POST['cartItemID'])) {
        $cartItemID = $_POST['cartItemID'];

        // Delete the item from the database
        $deleteQuery = "DELETE FROM cart_table WHERE cartID = $cartItemID";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        // Check if deletion was successful
        if ($deleteResult) {
            // Reload the page to reflect the changes
            header("Location: myCart.php");
            exit;
        } else {
            // Handle error if deletion fails
            echo "Error: Unable to remove item from cart.";
        }
    } else {
        // Handle error if cart item ID is not set
        echo "Error: Cart item ID not provided.";
    }
}

// ... (Your existing include and session start code) ...

// Update Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    $cartItemID = $_POST['cartItemID'];
    $newSize = $_POST['size'];
    $newQuantity = $_POST['quantity'];

    // Sanitize the input
    $newSize = mysqli_real_escape_string($conn, $newSize);
    $newQuantity = intval($newQuantity); // Ensure quantity is an integer

    if ($newQuantity > 0) {
        // Check available quantity in the inventory
        $inventoryQuery = "SELECT productQuantity FROM inventory_table WHERE productID = ? AND productSize = ?";
        $inventoryStmt = mysqli_prepare($conn, $inventoryQuery);
        mysqli_stmt_bind_param($inventoryStmt, "is", $_POST['productID'], $newSize); // Use $_POST['productID'] to get product ID from the form
        mysqli_stmt_execute($inventoryStmt);
        $inventoryResult = mysqli_stmt_get_result($inventoryStmt);
        $inventoryRow = mysqli_fetch_assoc($inventoryResult);
        $availableQuantity = $inventoryRow['productQuantity'];

        if ($newQuantity <= $availableQuantity) {
            // Check if item exists
            $checkQuery = "SELECT * FROM cart_table WHERE cartID = $cartItemID";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                // Update the cart item
                $updateQuery = "UPDATE cart_table SET sizeName = '$newSize', quantity = $newQuantity WHERE cartID = $cartItemID";
                if (mysqli_query($conn, $updateQuery)) {
                    header("Location: myCart.php");
                    exit;
                } else {
                    echo "Error updating cart: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['error_message'] =  "Cart item not found.";
            }
        } else {
            $_SESSION['error_message'] =  "Chosen quantity exceeds available stock.";
        }
    } else {
        $_SESSION['error_message'] =  "Quantity must be greater than 0.";

    }
}


// Check if the user is logged in
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id']; 

    // Fetch cart items for the logged-in user from the database
    $cartQuery = "SELECT cart_table.cartID, cart_table.productID, cart_table.quantity, cart_table.sizeName, product_table.productName, product_table.productImage, product_table.productPrice, category_table.productCategory
                  FROM cart_table
                  JOIN product_table ON cart_table.productID = product_table.productID
                  JOIN category_table ON product_table.productID = category_table.id
                  WHERE userID = $userID"; 

    $cartResult = mysqli_query($conn, $cartQuery);
} else {
    // If not logged in, use cart data from the session
    $cartResult = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
}

// Calculate total
$totalPrice = 0;


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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - KMK</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/myCart.css">
</head>
<body>
<header>
    <div class="top-container">

    </div>
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="<?php echo redirectLink(); ?>"><img class="logoHolder"src="../images/Logo-small.png" alt="Logo"></a>
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
                            <a class="nav-link mx-lg-2" href="LandingPage.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Search"><i class="bi bi-search"></i></a>
                            <a class="nav-link mx-lg-2" href="myOrders.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="My Orders"><i class="bi bi-bag-check"></i></a>
                            <a class="nav-link mx-lg-2" href="myCart.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="My Cart"><i class="bi bi-cart2"></i></a>
                            <a class="nav-link mx-lg-2" href="<?php echo getProfileLink(); ?>" data-bs-toggle="tooltip"       data-bs-placement="bottom" title="<?php echo getDisplayName(); ?>"> 
                              <i class="bi bi-person"></i> 
                            </a>
                            <a class="nav-link mx-lg-2" href="Login.php"><i></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>   
    <div class="container mt-5">
    <h2>Your Cart</h2>
    <hr>
    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error_message'] ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
    <?php 
        // Check if cart has items (different conditions for logged-in and guest users)
        if (isset($_SESSION['id']) && mysqli_num_rows($cartResult) > 0 || !isset($_SESSION['id']) && !empty($cartResult)) { 
        ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Size & Quantity</th>
                        <th colspan="2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($cartItem = mysqli_fetch_assoc($cartResult)) { // Fetch cart item data inside the loop
                        $itemTotal = $cartItem["productPrice"] * $cartItem["quantity"];
                        $totalPrice += $itemTotal; 
                        ?>
                        <tr>
                            <td style="width: 10rem; height: 10rem; object-fit: fill;">
                                <img src="../productImages/<?php echo $cartItem['productImage']; ?>" class="card-img-top" alt="<?php echo $cartItem['productName']; ?>">
                            </td>
                            <td><?php echo $cartItem['productName']; ?></td>
                            <td>₱<?php echo number_format($cartItem['productPrice'], 2); ?></td>
                            <td>
                                <form action="myCart.php" method="post">
                                <input type="hidden" name="cartItemID" value="<?php echo $cartItem['cartID']; ?>"> 
                                <input type="hidden" name="productID" value="<?php echo $cartItem['productID']; ?>"> 
                                    <select name="size" class="form-select" required>
                                        <?php
                                        // Fetch available sizes from inventory table
                                        $sizeQuery = "SELECT DISTINCT productSize FROM inventory_table WHERE productID = ?";
                                        $sizeStmt = mysqli_prepare($conn, $sizeQuery);
                                        mysqli_stmt_bind_param($sizeStmt, "i", $cartItem['productID']);
                                        mysqli_stmt_execute($sizeStmt);
                                        $sizeResult = mysqli_stmt_get_result($sizeStmt);

                                        while ($sizeRow = mysqli_fetch_assoc($sizeResult)) {
                                            $size = $sizeRow['productSize'];
                                            $selected = ($size == $cartItem['sizeName']) ? "selected" : "";
                                            echo "<option value='$size' $selected>$size</option>";
                                        }

                                        mysqli_stmt_close($sizeStmt);
                                        ?>
                                    </select>
                                    <input type="number" name="quantity" class="form-control mt-2" value="<?php echo $cartItem['quantity']; ?>" min="1">
                                    <button type="submit" class="btn btn-primary btn-sm mt-2" name="update_cart">Update</button>
                                    <button type="submit" class="btn btn-outline-danger btn-sm mt-2" name="remove_from_cart">Remove</button>
                                </form>
                            </td>
                            <td colspan="2">₱<?php echo number_format($itemTotal, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <tr class="table-info">
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2">₱<?php echo number_format($totalPrice, 2); ?></td> 
                    </tr>
                </tbody>
            </table>

            <div class="text-end">
                <a href="checkout.php"><button class="btn btn-primary">Checkout</button></a>
            </div>
        <?php } else { ?>
            <?php if (isset($_SESSION['id'])) { ?> 
                <h1>Your cart is empty.</h1>
            <?php } else { ?>
                <div class="empty-cart-message">
                    <h2>Your cart is empty. Login first to add items to your cart. <a href="login.php"> Click Here!</a></h2> 
                </div> 
            <?php } ?>
        <?php } ?>

</div>
</body>
</html>
