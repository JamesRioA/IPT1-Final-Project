<?php
session_start();
// Include your database connection file here
include_once __DIR__ . "/../database/config.php"; // Adjust the path according to your file structure

// Set admin credentials
$adminEmail = "kmkadmin@example.com";
$adminName = "KMK Apparel Admin";
$adminPassword = "admin123"; // Set the plaintext password here

// Hash the password
$hashed_password = password_hash($adminPassword, PASSWORD_DEFAULT);

// Check if the email already exists in the database
$sql_check_email = "SELECT * FROM admin_table WHERE adminEmail = ?";
$stmt_check_email = $conn->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $adminEmail);
$stmt_check_email->execute();
$result_check_email = $stmt_check_email->get_result();

if ($result_check_email->num_rows > 0) {
    // Email already exists, set error message and redirect
    $_SESSION['error_message'] = "Email already exists. Please use a different email.";
    $stmt_check_email->close();
    header("Location: adminLogin.php");
    exit();
} else {
    // Email does not exist, insert new admin into database with hashed password
    $sql_insert_admin = "INSERT INTO admin_table (adminName, adminEmail, adminPassword) VALUES (?, ?, ?)";
    $stmt_insert_admin = $conn->prepare($sql_insert_admin);
    $stmt_insert_admin->bind_param("sss", $adminName, $adminEmail, $hashed_password);

    if ($stmt_insert_admin->execute()) {
        // New admin created successfully, set success message and redirect
        $_SESSION['success_message'] = 'Account has been created';
        $stmt_insert_admin->close();
        header("Location: adminLogin.php");
        exit();
    } else {
        // Error occurred during insertion, set error message and redirect
        $_SESSION['error_message'] = 'There is an error creating the account';
        $stmt_insert_admin->close();
        header("Location: adminLogin.php");
        exit();
    }
}
