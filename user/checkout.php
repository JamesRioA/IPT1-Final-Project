<?php
session_start();
include '../database/config.php';

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

// Security: Validate and Sanitize User Input
function validateInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$userFullname = $userEmail = $userAddress = $userPhone = ''; 
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
    $userInfoQuery = "SELECT userFullname, userEmail, userAddress, mobileNo FROM user_table WHERE userID = $userID";
    $userInfoResult = mysqli_query($conn, $userInfoQuery);
    
    if ($userInfoResult && mysqli_num_rows($userInfoResult) > 0) {
        $userInfo = mysqli_fetch_assoc($userInfoResult);
        $userFullname = $userInfo['userFullname'];
        $userEmail = $userInfo['userEmail'];
        $userAddress = $userInfo['userAddress'];
        $userPhone = $userInfo['mobileNo'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $errors = []; // Store validation errors

  // Validate all required fields (adjust as needed)
  $fullname = validateInput($_POST['fullname']);
  $email = validateInput($_POST['email']);
  $address = validateInput($_POST['address']);
  $phone = validateInput($_POST['phone']);
  $paymentMethod = $_POST['paymentMethod']; // Assuming this is a pre-defined option

  // Example validation checks
  if (empty($fullname) || empty($email) || empty($address) || empty($phone)) {
    $errors[] = "All fields are required.";
  }
  // ... other validation checks (email format, phone number, etc.)

  // If no errors, process the order
  if (empty($errors)) {
    // Retrieve the cart items for the logged-in user
    if (isset($_SESSION['id'])) {
      $userID = $_SESSION['id'];
      $cartQuery = "SELECT cart_table.*, product_table.productName, product_table.productImage, product_table.productPrice 
              FROM cart_table
              JOIN product_table ON cart_table.productID = product_table.productID
              WHERE userID = $userID";
      $cartResult = mysqli_query($conn, $cartQuery);

      // Start a transaction (optional, for database integrity)
      mysqli_begin_transaction($conn);

      try {
        // 1. Create the order record in the orders table
        $insertOrderQuery = "INSERT INTO order_table (userID, order_date, orderStatus, totalAmount, shippingAddress)
                             VALUES ($userID, NOW(), 'Processing', ?, ?)";
        $stmt = mysqli_prepare($conn, $insertOrderQuery);

        // Calculate total order amount
        $totalAmount = 0;
        while ($cartItem = mysqli_fetch_assoc($cartResult)) {
          $totalAmount += $cartItem['productPrice'] * $cartItem['quantity'];
        }
        
        mysqli_stmt_bind_param($stmt, "ds", $totalAmount, $address);
        mysqli_stmt_execute($stmt);
        $orderID = mysqli_insert_id($conn); // Get the newly inserted order ID

        // 2. Create order details for each cart item
        mysqli_stmt_close($stmt); // Close the previous prepared statement
        $insertOrderDetailsQuery = "INSERT INTO order_details_table (orderID, productID, quantity, price) 
                            VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertOrderDetailsQuery);
        mysqli_data_seek($cartResult, 0);
        while ($cartItem = mysqli_fetch_assoc($cartResult)) {
            mysqli_stmt_bind_param($stmt, "iiid", $orderID, $cartItem['productID'], $cartItem['quantity'], $cartItem['productPrice']); 
            mysqli_stmt_execute($stmt);   
        }
        // 3. Update product inventory based on quantities ordered
        $updateInventoryQuery = "UPDATE inventory_table 
                             SET productQuantity = productQuantity - ?
                             WHERE productID = ? AND productSize = ?"; 
        $updateStmt = mysqli_prepare($conn, $updateInventoryQuery);
        mysqli_data_seek($cartResult, 0); 

        while ($cartItem = mysqli_fetch_assoc($cartResult)) {
            mysqli_stmt_bind_param($updateStmt, "iis", $cartItem['quantity'], $cartItem['productID'], $cartItem['sizeName']);
            mysqli_stmt_execute($updateStmt);

            // Check for insufficient stock
            if (mysqli_stmt_affected_rows($updateStmt) == 0) {
                throw new Exception("Insufficient stock for product ID: " . $cartItem['productID'] . ", size: " . $cartItem['sizeName']);
            }
        }
        mysqli_stmt_close($updateStmt);

        // 5. Create a sales invoice record for each item
        $insertInvoiceQuery = "INSERT INTO sales_invoice_table (orderID, invoiceAmount, productID, sizeName, productCategory) 
                       VALUES (?, ?, ?, ?, ?)";
        $invoiceStmt = mysqli_prepare($conn, $insertInvoiceQuery);

        mysqli_data_seek($cartResult, 0); // Reset the cart result pointer (again)
        $invoiceStmt = mysqli_prepare($conn, $insertInvoiceQuery);
        while ($cartItem = mysqli_fetch_assoc($cartResult)) {
            // Fetch product category from category_table (corrected table name)
            $productCategoryQuery = "SELECT productCategory 
                                        FROM category_table 
                                        JOIN product_table ON category_table.id = product_table.productID
                                        WHERE product_table.productID = ?";
            
            $categoryStmt = mysqli_prepare($conn, $productCategoryQuery);
            if ($categoryStmt) { 
                mysqli_stmt_bind_param($categoryStmt, "i", $cartItem['productID']);
                mysqli_stmt_execute($categoryStmt);
                $categoryResult = mysqli_stmt_get_result($categoryStmt);
                $categoryRow = mysqli_fetch_assoc($categoryResult);
                $productCategory = $categoryRow['productCategory'];
            } else {
                throw new Exception("Error preparing category query: " . mysqli_error($conn));
            }
            // Calculate the item total
            $itemTotal = $cartItem["productPrice"] * $cartItem["quantity"];
            mysqli_stmt_bind_param($invoiceStmt, "idiis", $orderID, $itemTotal, $cartItem['productID'], $cartItem['sizeName'], $productCategory);
            mysqli_stmt_execute($invoiceStmt);
        }
        if ($categoryStmt) {
            mysqli_stmt_close($categoryStmt); 
        }
        mysqli_stmt_close($invoiceStmt); 

        // 5. Clear the user's cart
        $deleteCartQuery = "DELETE FROM cart_table WHERE userID = $userID";
        mysqli_query($conn, $deleteCartQuery);

        // Commit the transaction
        mysqli_commit($conn);

        // Redirect to a success page or display a confirmation message
        header("Location: order_confirmation.php");
        exit;
      } catch (Exception $e) {
        // Rollback and display error message
        mysqli_rollback($conn);
        $errors[] = $e->getMessage(); // Display the specific error message
    } finally {
        // Close any open statements in the 'finally' block to avoid memory leaks
        if(isset($stmt) && is_object($stmt)){
          mysqli_stmt_close($stmt);
        }
    } 
  }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Checkout - KMK</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/checkout.css">
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

    <div class="container">
    <div class="user-input">
        <h2>User Information</h2>

        <?php if (!empty($errors)): ?> 
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="checkout.php" method="post">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $userFullname; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $userEmail; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $userAddress; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $userPhone; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method:</label>
                    <select id="paymentMethod" class="form-select" name="paymentMethod" required>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                </div>
    </div>
    <div class="items">
        <h2>Items</h2>

        <?php
        // Fetch and display cart items from the database
        if (isset($_SESSION['id'])) {
            $userID = $_SESSION['id'];
            $cartQuery = "SELECT cart_table.*, product_table.productName, product_table.productImage, product_table.productPrice
                        FROM cart_table
                        JOIN product_table ON cart_table.productID = product_table.productID
                        WHERE userID = $userID";

            $cartResult = mysqli_query($conn, $cartQuery);

            $total = 0; // Initialize total price
            while ($cartItem = mysqli_fetch_assoc($cartResult)) {
                $itemTotal = $cartItem["productPrice"] * $cartItem["quantity"];
                $total += $itemTotal; // Calculate running total
                echo '<div class="item">';
                echo '<img src="../productImages/' . $cartItem['productImage'] . '" alt="' . $cartItem['productName'] . '">';
                echo '<div class="item-name">' . $cartItem['productName'] . ' (Size: ' . $cartItem['sizeName'] . ')</div>';
                echo '<div class="item-price">₱' . number_format($cartItem['productPrice'], 2) . ' x ' . $cartItem['quantity'] . ' = ₱' . number_format($itemTotal, 2) . '</div>';
                echo '</div>';
            }
            echo '<div style="text-align: right;"><strong><h4>Order Total: ₱' . number_format($total, 2) . '</h4></strong></div>'; 
        } else {
            echo "<p>Login to see your cart items.</p>";
        }
        ?>
            <div style="text-align: right;"> 
                <button class="btn btn-primary" type="submit">Checkout</button>
            </div>
        </form>  
    </div>
</div>
</body>
</html>