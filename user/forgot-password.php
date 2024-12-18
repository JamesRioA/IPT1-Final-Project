<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMK - Forgot Password</title>
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
          <form action="Login.php" method="post">
          <div class="row">
            <div class="col">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="text-center">
              <a href="Login.php"><button class="btn custom-btn" type="submit">Submit</button></a>
              <p>Remember your password? <a href="Login.php">Login</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>