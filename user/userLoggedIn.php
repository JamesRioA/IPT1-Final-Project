<?php
session_start();


// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../Landingpage.php");
    exit();
}

// Include your database configuration file
include_once("../database/config.php");

// Fetch user information from the database based on the session ID
$sql = "SELECT userID, userFullname, userEmail, userName, mobileNo FROM user_table WHERE userID = ?";
if($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION["id"]); // Bind the user ID parameter
    if($stmt->execute()) {
        $stmt->store_result();
        if($stmt->num_rows == 1) {
            $stmt->bind_result($id, $fullName, $email, $name, $mobileNo);
            $stmt->fetch();
        } else {
            // Handle the case where no user is found with the given ID
            session_destroy(); 
            header("Location: ../Landingpage.php"); 
            exit();
        }
    } else {
        // Handle query execution errors
        die("Error executing the query: " . $stmt->error);
    }
    $stmt->close();
}

$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMK Apparel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/userLoggedIn.css">
</head>
<body>
    <header>
        <div class="top-container">

        </div>
        <nav class="navbar navbar-expand-lg mb-4">
            <div class="container-fluid">
                    <a class="navbar-brand me-auto" href="userLoggedIn.php"><img class="logoHolder"src="../images/Logo-small.png" alt="Logo"></a>
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
                            <a class="nav-link mx-lg-2" href="userProfile.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo htmlspecialchars($name); ?>"><i class="bi bi-person"></i></a>
                            <a class="nav-link mx-lg-2" href="Login.php"><i></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-5">
                <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="../images/Slide1.png" class="d-block mx-auto" alt="Slide 1" style="height: 60%; max-width: 100%; object-fit: contain;">
                        </div>
                        <div class="carousel-item">
                            <img src="../images/Slide2.png" class="d-block mx-auto" alt="Slide 2" style="height: 60%; max-width: 100%; object-fit: contain;">
                        </div>
                        <div class="carousel-item">
                            <img src="../images/Slide3.png" class="d-block mx-auto" alt="Slide 3" style="height: 60%; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid store">
        <div class="row justify-content-center">
            <div class="col-md-10 mt-5 mb-5">
                <img src="../images/Store.png" alt="Store" style="height: 100%; width: 100%;">
            </div>
        </div>
    </div>
</main>
<footer class="container-fluid py-4"> 
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5>Company Info</h5>
        <ul class="list-unstyled">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">Shipping & Returns</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5>Customer Service</h5>
        <ul class="list-unstyled">
          <li><a href="#">Help Center / FAQs</a></li>
          <li><a href="#">Track My Order</a></li>
          <li><a href="mailto:support@yourstore.com">support@yourstore.com</a></li>
          <li><a href="tel:+1234567890">(123) 456-7890</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5>Stay Connected</h5>
        <ul class="list-inline mt-2">
          <li class="list-inline-item"><a href="#"><i class="mdi mdi-facebook"></i></a></li>
          <li class="list-inline-item"><a href="#"><i class="mdi mdi-instagram"></i></a></li>
          </ul>
      </div>
    </div>
    <hr>
    <div class="row text-center">
      <div class="col-12">
        <p class="copyright-text">&copy; 2024 KMK Apparel Store</p>
      </div>
    </div>
  </div>
</footer>


</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize the carousel
        var myCarousel = new bootstrap.Carousel(document.querySelector('#carouselExampleSlidesOnly'), {
            interval: 5000, // Set the interval for auto-sliding, if needed
            wrap: true // Enable looping of slides
        });

        // Set the first slide as active
        myCarousel.to(0);
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
    });

</script>

</body>
</html>