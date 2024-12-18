<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    include '../database/config.php';

    $name = $_POST['username'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email']; 
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $mobileNo = $_POST['mobileNo'];
    $userAddress = $_POST['userAddress'];

    if (empty($name) || empty($fullName) || empty($email) || empty($password) || empty($confirmPassword) || empty($mobileNo) || empty($userAddress)) {
        $_SESSION['error_message'] = "Please fill all fields.";
        header("Location: Signup.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header("Location: Signup.php");
        exit();
    }

    $name = mysqli_real_escape_string($conn, $name);
    $fullName = mysqli_real_escape_string($conn, $fullName);
    $email = mysqli_real_escape_string($conn, $email);
    $userAddress = mysqli_real_escape_string($conn, $userAddress);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    

    $sql = "SELECT * FROM user_table WHERE userEmail = '$email' OR userFullname = '$fullName'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $_SESSION['error_message'] = "Email or Fullname already exist! Please use a different credentials.";
        header("Location: signup.php");
        exit();
    }else{

        $sql = "INSERT INTO user_table (userFullname, userName, userEmail, userPassword, mobileNo, userAddress)
                VALUES ('$fullName', '$name', '$email', '$hashed_password', '$mobileNo', '$userAddress')";

        if($conn->query($sql) === TRUE){
            $_SESSION['success_message'] = 'Account has been created!';
            header("Location: Signup.php");
            exit();
        }
        else{
            $_SESSION['error_message'] = 'There is an error updating the data';
            header("Location: Signup.php");
            exit();
        }

    }
}
else{
    header("Location: Signup.php");
    exit();
}