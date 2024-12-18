<?php
session_start();

// Check Login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../Landingpage.php");
    exit();
}


if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: ../Landingpage.php");
    exit();
}

// Database Connection
include_once("../database/config.php");

// Fetch User Data (Only if not editing)
$sql = "SELECT userFullname, userEmail, mobileNo, userAddress FROM user_table WHERE userID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->bind_result($fullName, $email, $mobileNo, $userAddress);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Error preparing the query: " .  $conn->error);
    }

// Update Profile (Only if edit form was submitted)
if (isset($_POST['save_profile'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $mobileNo = $_POST['mobileNo'];
    $userAddress = $_POST['userAddress'];

    // Sanitize Inputs (IMPORTANT FOR SECURITY!)
    $fullName = htmlspecialchars($fullName);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $updateSql = "UPDATE user_table SET userFullname=?, userEmail=?, mobileNo=?, userAddress=? WHERE userID=?";
    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param("ssssi", $fullName, $email, $mobileNo, $userAddress, $_SESSION["id"]);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully!";  // Store message in session
            header("Location: userProfile.php");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Account - KMK</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/userProfile.css">
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
                            <a class="nav-link mx-lg-2" href="userProfile.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Login"><i class="bi bi-person"></i></a>
                            <a class="nav-link mx-lg-2" href=""><i></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>   

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3">
            <div class="sidebar-sticky">
                <h4 class="mt-5">Hello <?php echo htmlspecialchars($fullName); ?></h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active mt-5" href="userAccount.php">
                            <h4>Manage My Account</h4>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userProfile.php">
                            My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Address Book
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <h4>My Orders</h4>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            My Cancellations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="E">
                            My Returns
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <h4>My Wishlist</h4>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer">
                <a href="?logout=1" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
        <div class="container mt-5">
    <h2 class="mb-4">User Account</h2>
    <hr>
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <!-- User Information Section -->
    <section class="mb-4">
        <h3>User Information</h3>
        <form action="" method ="POST">
        <div class="row">
            <div class="col-md-4">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $fullName; ?>" <?php echo isset($_POST['edit_profile']) ? '' : 'readonly'; ?>> 
            </div>
            <div class="col-md-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" <?php echo isset($_POST['edit_profile']) ? '' : 'readonly'; ?>>
            </div>
            <div class="col-md-4">
                <label for="number">Mobile Number</label>
                <input type="text" class="form-control" id="fullName" name="mobileNo" value="<?php echo $mobileNo; ?>" <?php echo isset($_POST['edit_profile']) ? '' : 'readonly'; ?>>
            </div>
            <div class="col-md-4">
                <label for="number">Address</label>
                <input type="text" class="form-control" id="fullName" name="userAddress" value="<?php echo $userAddress; ?>" <?php echo isset($_POST['edit_profile']) ? '' : 'readonly'; ?>>
            </div>
        </div>
    </section>
    <section class="mt-5">
            <?php if (!isset($_POST['edit_profile'])): ?>
                <button type="submit" class="btn btn-primary btn-lg" name="edit_profile">Edit Profile</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary btn-lg" name="save_profile">Save Changes</button>
            <?php endif; ?>
        </form>
        <button type="button" class="btn btn-secondary btn-lg">Set Password</button>
    </section>
</div>

</body>
</html>
