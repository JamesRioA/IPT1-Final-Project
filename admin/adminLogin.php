<?php
 // Start the session
session_start();

    // Check if the form is submitted via POST request
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Include your database connection file here
        include_once("../database/config.php");

            // Check if email and password are set and not empty
            if(isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])){
                
                // Prepare a select statement
                $sql = "SELECT  adminID, adminName, adminEmail, adminPassword FROM admin_table WHERE adminEmail = ?";

                if($stmt = $conn->prepare($sql)){

                    $stmt->bind_param("s", $param_email);

                    //set parameters
                    $param_email = $_POST['email'];

                    if($stmt->execute()){
                        $stmt->store_result();

                        if($stmt->num_rows > 0){
                            
                            $stmt->bind_result($id, $fullName, $email, $hashed_password);

                            if($stmt->fetch()){
                                if(password_verify($_POST['password'], $hashed_password)){
                                    
                                    $_SESSION["loggedinAdmin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["email"] = $email;
                                    $_SESSION["fullName"] = $fullName;

                                    header("Location: dashboard.php");

                                }else{
                                    $_SESSION['error_message'] = "Incorrect email or password. Please try again.";
                                }
                            }
                        } else {
                            $_SESSION['error_message'] = "Incorrect email or password. Please try again.";
                        }
                    
                    } else {
                        // Display an error message if execution failed
                        $_SESSION['error_message'] = "Oops! Something went wrong. Please try again later.";
                    }

                    $stmt->close();
                }
            }

        $conn->close();

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - KMK</title>
    <link rel="stylesheet" href="..\bootstrap\css\bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
<div class="container content-container">
         <div class="row">
            <div class="col-sm-6 order-sm-1 col-lg-6">
                <div class="logoHolder d-none d-sm-block">
                    <div class="row my-custom-row justify-content-center align-items-center">
                        <img src="../images/Logo.png" alt="Logo">
                    </div>
                </div>
            </div>
        <div class="card col-sm-6 order-sm-2 col-lg-4 mx-auto">
            <div class="card-header d-sm-none">
                <div class="logoSmall">
                    <img src="../images/Logo-small.png" alt="">
                </div>
            </div>
            <div class="card-body"> 
                <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error_message'] ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <form method="post">
                    <div class="form-floating mt-lg-5 mb-2 mt-sm-2 ">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Email" name="email" required>
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
                        <label for="floatingInput">Password</label> 
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn custom-btn" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</body>
</html>