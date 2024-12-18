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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    // Include database connection
    include_once "../database/config.php";

    $conn->query("SET foreign_key_checks = 0");

    // Get productID from the form data
    $product_id = $_POST['product_id'];



    // Delete from product_table
    $sql_delete_product = "DELETE FROM product_table WHERE productID = ?";
    $stmt_delete_product = $conn->prepare($sql_delete_product);

    if ($stmt_delete_product) {
        // Bind the parameter
        $stmt_delete_product->bind_param("i", $product_id);

        // Execute the statement
        if ($stmt_delete_product->execute()) {
            // Delete from category_table
            $sql_delete_category = "DELETE FROM category_table WHERE productID = ?";
            $stmt_delete_category = $conn->prepare($sql_delete_category);
            $stmt_delete_category->bind_param("i", $product_id);
            $stmt_delete_category->execute();

            // Delete from inventory_table (assuming the inventory table has a foreign key relationship with product_table)
            $sql_delete_inventory = "DELETE FROM inventory_table WHERE productID = ?";
            $stmt_delete_inventory = $conn->prepare($sql_delete_inventory);
            $stmt_delete_inventory->bind_param("i", $product_id);
            $stmt_delete_inventory->execute();

            $_SESSION['success_message'] = "Product and related records deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete product.";
        }

        // Close the statements
        $stmt_delete_product->close();
        if(isset($stmt_delete_category)) $stmt_delete_category->close();
        if(isset($stmt_delete_inventory)) $stmt_delete_inventory->close();
    } else {
        // Handle statement preparation error
        $_SESSION['error_message'] = "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();

    // Redirect to the original page
    header("Location: items.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KMK</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/items.css">
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
                <h3 class="fw-bold fs-4 mb-3">Items</h3>
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
                            <div class="col-12">
                            <div class="input-group mb-3 mt-3">
                                <span class="input-group-text" id="basic-addon1"><i class = "mdi mdi-search-web"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search Items...">
                            </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-primary me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Items</button>
                                     <button class="btn btn-primary" type="button" disabled>Cancel</button>
                                </div>
                                <?php
                                    if (isset($_SESSION['success_message'])){
                                        echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";

                                        unset($_SESSION['success_message']);
                                    }

                                    if (isset($_SESSION['error_message'])){
                                        echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";

                                        unset($_SESSION['error_message']);
                                    }
                                    
                                    ?>
                            </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="highlight">
                                            <th scope="col">Image</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Brand</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                        <?php
                                            include '../database/config.php';
                                            $sql = "SELECT * FROM product_table";
                                            $result = mysqli_query($conn, $sql);

                                            if(mysqli_num_rows($result) > 0) {
                                                while($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td> <image style='width: 10rem; height: 10rem; object-fit: fill;' src='../productImages/". $row['productImage']. "' class='card-img-top' alt='". $row['productName']."' </td>";
                                                    echo "<td>" . $row['productName'] . "</td>";
                                                    echo "<td>" . $row['productBrand'] . "</td>";
                                                    echo "<td>" . $row['productPrice'] . "</td>";
                                                    echo "<td>
                                                    <form action='items.php' method='post'>
                                                        <input type='hidden' name='product_id' value='" . $row['productID'] . "'>
                                                        <button type='submit' name='delete_product' class='btn btn-danger'>Delete</button>
                                                    </form>
                                                </td>";
                                                    echo "</tr>";
                                                }
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Items</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="insert_items.php" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Category</span>
                <select id="productCategory" name="productCategory" aria-label="productCategory">
                    <option value="option0">(Category)</option>
                    <option value="Apparel">Apparel</option>
                    <option value="Footwear">Footwear</option>
                    <option value="Equipment">Equipment</option>
                </select>
                
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Name</span>
                <input type="text" class="form-control" id="productName" name="productName" aria-label="productName" aria-describedby="basic-addon1" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Description</span>
                <textarea class="form-control" id="description" rows="3" name="productDescription" aria-label="productDescription" aria-describedby="basic-addon1" required ></textarea>
            </div>
            <div class="input-group row mb-3">
                <div class="col">
                        <span class="input-group-text" id="basic-addon1">Brand</span>
                        <input type="text" class="form-control" id="brand" name="Brand" aria-label="productBrand" aria-describedby="basic-addon1" required>
                </div>      
                <div class="col">
                        <span class="input-group-text" id="basic-addon1">Size (comma separated e.g 6,7 or Small,Medium)</span>
                        <input type="text" class="form-control" id="size" name="Size" aria-label="productSize" aria-describedby="basic-addon1" required>
                </div>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Quantity (comma separated)</span>
                <input type="text" class="form-control" id="quantity" name="Quantity" aria-label="productQuantity" aria-describedby="basic">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Price</span>
                <input type="number" class="form-control" id="price" name="Price" aria-label="productPrice" aria-describedby="basic-addon1" required>
            </div>
            <div class="input-group mb-5">
                <span class="input-group-text" id="basic-addon1">Image</span>
                <input type="file" class="form-control" id="inputGroupFile01" name="Image" aria-label="productImage" aria-describedby="basic-addon1" required>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Item</button>
      </div>
      </form>
    </div>
  </div>
</div>


<script>
    $("#searchInput").on("keyup", function(){
        var value = $(this).val().toLowerCase();
        $("#userTableBody tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>