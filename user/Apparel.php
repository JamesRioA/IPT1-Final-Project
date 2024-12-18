<?php
session_start();

// Function to get the display name from the session or "Login"
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
    <title>Apparel - KMK</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/allproduct-style.css">
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
                            <form class="d-flex" method="get" action="Apparel.php">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
                            <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                            </form>
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
  <div class="row">
    <!-- Sidebar for filters -->
    <aside class="col-md-3">
      <h2>Filters</h2>
      <form>
        <div class="mb-3">
          <label for="category">Category:</label>
          <select class="form-select" id="category">
            <option selected>All</option>
            <option value="1">Trending</option>
            <option value="2">Best Sellers</option>
            <option value="3">Hot</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="price">Price: P50 - P10,000</label>
          <input type="range" class="form-range" id="price" min="0" max="100" value="50">
        </div>
        <button type="submit" class="btn btn-primary">Apply Filters</button>
      </form>
    </aside>

    <!-- Main content area for products -->
    <div class="col-md-9 mt-5">
      <h1>Products</h1>
      <div class="row row-cols-1 row-cols-sm-2">
      <?php
          include '../database/config.php';
          
          // Initialize the query
          $query = "SELECT * FROM product_table WHERE productID IN (SELECT productID FROM category_table WHERE productCategory ='Apparel')";

          // Check if there's a search query
          if(isset($_GET['query']) && !empty($_GET['query'])) {
              // Get the search query
              $search = $_GET['query'];
              // Append the search condition to the query
              $query .= " AND (productName LIKE '%$search%' OR productDescription LIKE '%$search%')";
          }

          // Perform the query
          $result = mysqli_query($conn, $query);

          // Check if there are any results
          if(mysqli_num_rows($result) > 0) {
              // Loop through each row of the result set
              while($row = mysqli_fetch_assoc($result)) {
                  // Output the product card
                  echo '<div class="col-md-4">';
                  echo '  <div class="card mb-4">';
                  echo '    <a href="viewItem.php?productID=' . $row['productID'] . '">';
                  echo '    <img src="../productImages/' . $row['productImage'] . '" class="card-img-top" alt="' . $row['productName'] . '">';
                  echo '    <div class="card-body">';
                  echo '      <h5 class="card-title">' . $row['productName'] . '</h5>';
                  echo '      <p class="card-text">' . $row['productBrand'] . '</p>';
                  echo '    <h5 class= "card-text">₱' . $row['productPrice'] . '</h5>';
                  echo '    </a>';
                  echo '    </div>';
                  echo '  </div>';
                  echo '  </div>';
              }
          } else {
              // If no products found
              echo '<p>No products found.</p>';
          }
          ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>