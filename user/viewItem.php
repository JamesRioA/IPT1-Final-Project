<?php
session_start();

include '../database/config.php';

// Get productID from URL parameter
if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Fetch product details from product_table
    $productQuery = "SELECT * FROM product_table WHERE productID = $productID";
    $productResult = mysqli_query($conn, $productQuery);

    if (mysqli_num_rows($productResult) > 0) {
        $product = mysqli_fetch_assoc($productResult);
    } else {
        die("Product not found."); // Or handle the error more gracefully (e.g., redirect)
    }

    // Fetch available sizes from inventory_table
    $sizesQuery = "SELECT productSize, productQuantity 
               FROM inventory_table 
               WHERE productID = $productID AND productQuantity > 0";
    $sizesResult = mysqli_query($conn, $sizesQuery);
} else {
    die("Invalid product ID."); // Or redirect to another page
}

$showSuccessModal = false;
$showError = false;

if (isset($_POST['add_to_cart']) && isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $userID = $_SESSION['id'];
    if (isset($_POST['options'])) {  
        $sizeName = $_POST['options'];
        $quantity = 1;
        $checkCartQuery = "SELECT * FROM cart_table WHERE userID = $userID AND productID = $productID AND sizeName = '$sizeName'";
        $checkCartResult = mysqli_query($conn, $checkCartQuery);

        if (mysqli_num_rows($checkCartResult) > 0) {
            // Update existing cart item
            $updateCartQuery = "UPDATE cart_table SET quantity = quantity + 1 WHERE userID = $userID AND productID = $productID AND sizeName = '$sizeName'";
            if (mysqli_query($conn, $updateCartQuery)) { 
                $showSuccessModal = true;
            }
        } else {
            // Insert new cart item
            $insertCartQuery = "INSERT INTO cart_table (userID, productID, sizeName, quantity) VALUES ($userID, $productID, '$sizeName', $quantity)";
            if (mysqli_query($conn, $insertCartQuery)) { 
                $showSuccessModal = true;
            }
        }
    } else {
        $showError = true; // No size selected
    }
} elseif (isset($_POST['add_to_cart'])) {
    echo '<script>alert("You must log in to add to cart."); window.location.href = "login.php";</script>';
}
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
        <link rel="stylesheet" href="../styles/viewItem.css">
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
        <h2 class="mb-4">View Item</h2>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <?php
                if (isset($product['productImage'])) {
                    echo '<img src="../productImages/' . $product['productImage'] . '" alt="Product Image" class="img-fluid rounded">';
                } else {
                    //Placeholder Image
                    echo '<img src="../productImages/default_image.jpg" alt="Default Image" class="img-fluid rounded">'; 
                }
                ?>
            </div>
            <div class="col-md-6">
                <form method="post">
                <h3><?php echo $product['productName']; ?></h3>
                <p><?php echo $product['productDescription']; ?></p>

                <div class="size mb-5">
                <?php
                    $sizeQuantities = [];
                    while ($sizeRow = mysqli_fetch_assoc($sizesResult)) {
                        $sizeQuantities[$sizeRow["productSize"]] = $sizeRow["productQuantity"];
                
                        $checked = ""; 
                        if (isset($_POST['options']) && $_POST['options'] == $sizeRow["productSize"]) {
                            $checked = "checked"; 
                        }
                
                        echo '<input type="radio" class="btn-check size-option" name="options" id="option' . $sizeRow["productSize"] . '" autocomplete="off" data-quantity="' . $sizeRow["productQuantity"] . '" value="' . $sizeRow["productSize"] . '" ' . $checked . ' required>'; // Add value attribute
                        echo '<label class="btn btn-secondary" for="option' . $sizeRow["productSize"] . '">' . $sizeRow["productSize"] . '</label>';
                    }
                ?>
                </div>
                <div id="quantity-display">Select a size to see the quantity.</div> <p><strong>Price: <?php echo 'P' . number_format($product['productPrice'], 2); ?></strong></p>
                <button type="submit" class="btn btn-primary" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Success!</h5>
                </div>
                <div class="modal-body">
                    Item added to cart!
                </div>
            </div>
        </div>
    </div>
<script>
        var sizeQuantities = <?php echo json_encode($sizeQuantities); ?>;
        const sizeOptions = document.querySelectorAll('.size-option');
        const quantityDisplay = document.getElementById('quantity-display');

        sizeOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Deselect other options
                sizeOptions.forEach(otherOption => {
                    if (otherOption !== option) {
                        otherOption.checked = false;
                    }
                });

                const selectedSize = option.id.replace('option', '');
                const quantity = sizeQuantities[selectedSize] || 0;
                quantityDisplay.textContent = `Available Quantity for size ${selectedSize}: ${quantity}`;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
        var showSuccessModal = <?php echo json_encode($showSuccessModal); ?>;
        var selectedSize = document.querySelector('.size-option:checked');

            if (showSuccessModal && selectedSize) {
                var myModal = new bootstrap.Modal(document.getElementById("exampleModal"), {backdrop: "static", keyboard: false});
                myModal.show();
                setTimeout(function() { 
                    myModal.hide(); 
                    // Reset the selected size
                    selectedSize.checked = false;
                }, 2000); // Adjust timeout as needed
            }
        });
</script>

</body>
</html>
