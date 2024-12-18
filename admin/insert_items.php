<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../database/config.php'; 

    // Sanitize input (Remember to sanitize ALL input)
    $name = mysqli_real_escape_string($conn, $_POST["productName"]);
    $description = mysqli_real_escape_string($conn, $_POST["productDescription"]);
    $brand = mysqli_real_escape_string($conn, $_POST["Brand"]);
    $price = intval($_POST["Price"]); // Ensure price is an integer
    $category = mysqli_real_escape_string($conn, $_POST["productCategory"]);

    //image
    $image = $_FILES['Image']['name'];
    $image_tmp = $_FILES['Image']['tmp_name'];
    $image_folder = "../productImages/";
    move_uploaded_file($image_tmp, $image_folder.$image);

    // Get comma-separated sizes and quantities from input
    $sizesInput = mysqli_real_escape_string($conn, $_POST['Size']);
    $quantitiesInput = mysqli_real_escape_string($conn, $_POST['Quantity']);

    // Split comma-separated values into arrays
    $sizesArray = explode(",", $sizesInput);
    $quantitiesArray = explode(",", $quantitiesInput);

    // Ensure both arrays have the same length
    if (count($sizesArray) !== count($quantitiesArray)) {
        $_SESSION['error_message'] = 'Number of sizes and quantities must match';
        header("Location: items.php");
        exit();
    }

    // Insert product into product_table
    $insertProductQuery = "INSERT INTO product_table (productName, productDescription, productBrand, productPrice, productImage) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertProductQuery);
    $stmt->bind_param("sssis", $name, $description, $brand, $price, $image);

    if ($stmt->execute()) {
        $productID = $stmt->insert_id;

        // Process inventory (sizes and quantities)
        for ($i = 0; $i < count($sizesArray); $i++) {
            $size = trim($sizesArray[$i]);
            $quantity = intval(trim($quantitiesArray[$i])); // Ensure quantity is an integer

            // Insert inventory into inventory_table
            $insertInventoryQuery = "INSERT INTO inventory_table (productID, productSize, productQuantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertInventoryQuery);
            $stmt->bind_param("isi", $productID, $size, $quantity);
            $stmt->execute();
        }

        // Insert category into category_table
        $insertCategoryQuery = "INSERT INTO category_table (productID, productCategory) VALUES (?, ?)";
        $stmt = $conn->prepare($insertCategoryQuery);
        $stmt->bind_param("is", $productID, $category);
        $stmt->execute();

        $_SESSION['success_message'] = 'Product has been added successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to add product: ' . $stmt->error;
    }
}

// (Delete product code - no changes needed)

header("Location: items.php");
exit();
