<!DOCTYPE html>
<html>
<head>
  <title>ChairCraft - Login</title>
  <style>
    .logo_for_lr{
      width: 50%;
      margin-left:25%;
    }
  </style>
</head>
<body>

<?php 
  include 'includes/header.php'; 
  include 'user_control.php';
  if (isset($_SESSION['auth'])) {
      $_SESSION['message'] = "You are already logged in";
      header("Location: dashboard.php");
      exit();
  }
?>


<?php if (isset($_SESSION['message'])) : ?>
  <div class="alert alert-success">
    <?php
      echo $_SESSION['message'];
        unset($_SESSION['message']);
    ?>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
        <?= $_SESSION['error']; 
        unset($_SESSION['error']); ?>
      </div>
<?php endif; ?>


<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
  <div class="position-relative w-100" style="max-width: 500px;">
 <a href="../index.php">
        <img class="logo_for_lr" alt="chaicraft logo" src="../pic/logo.png"  />
      </a>
    <div class="card shadow border-0 rounded-4">
      <div class="card-body p-4">
        <h3 class="mb-3 border-bottom pb-2"><strong>Login</strong></h3>
        <form action="" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label fw-bold">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary" name="login_user">Login</button>
          </div>
          <p class="text-center mt-3 mb-0">Don't have an account? <a href="sign_up.php">Sign up here</a></p>
        </form>
      </div>
    </div>
  </div>
</div>