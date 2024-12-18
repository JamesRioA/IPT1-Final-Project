<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMK - SignUp Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="..\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" href="../styles/signup.css">
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">
          <div class="logoHolder">
            <img src="../images/Logo-small.png" alt="Logo">
          </div>
        </div>
        <div class="card-body">
          <div class="alert">
            <?php if(isset($_SESSION['success_message'])):?>
              <div class="alert alert-success" role="alert">
                  <?= $_SESSION['success_message'] ?> 
              </div>
              <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['error_message'] ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
          </div>
          
          <form action="signup_check.php" method="post">
          <div class="row">
            <div class="col">
            <div class="mb-3">
              <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control"  id="fullname" name="fullName" placeholder="Full Name" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control"  id="mobilenum" name="mobileNo" placeholder="Mobile No." required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control" id="userAddress" name="userAddress" placeholder="Address" required>
            </div>
            <div class="text-center">
              <button class="btn custom-btn" type="submit">Create Account</button>
              <p>Already have an account? <a href="Login.php">Login</a></p>
            </div>
          </form> 
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>