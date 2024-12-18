<?php
session_start();

// Check Login
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

// Database Connection
include_once("../database/config.php");

// Fetch User Data (Only if not editing)
$sql = "SELECT adminName, adminEmail FROM admin_table WHERE adminID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $stmt->bind_result($fullName, $email);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Error preparing the query: " .  $conn->error);
    }
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
                <h3 class="fw-bold fs-4 mb-3">Profile</h3>
                </div>
                <div class="Logout" style="text-align:right;">
                    <a href="?logout=1" class="nav-link">
                        <i class="lni lni-exit"><span>Logout</span></i>
                    </a>
                </div>
            </nav>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md mx-auto">
                          <div class="card text-center">
                            <div class="card-body">
                              <div class="text-center">
                                <img src="../images/Logo.png" class="rounded" style="width: 200px; height: 200px;"/>
                              </div>
                            <h5><?php echo htmlspecialchars($fullName) ?></h5>
                            <p class="card-text" style="text-align: left; font-size: small; font-style: italic;">Email Address<br><strong><span style="color: black; font-size: 14px; font-style: normal;"><?php echo htmlspecialchars($email) ?></span></strong></p>
                            <p class="card-text" style="text-align: left; font-size: small; font-style: italic;">Address<br><strong><span style="color: black; font-size: 14px; font-style: normal;">Malaybalay City, Bukidnon</span></strong></p>
                            <div>
                          </div>
                       </div>
                      </div>
                  </div>
          </div>              
      </div>
</body>

</html>
