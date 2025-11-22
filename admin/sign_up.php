<!DOCTYPE html>
<html>
<head>
  <title>ChairCraft - Sign Up</title>
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
?>

<?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
<?php endif; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
  <div class="position-relative w-100" style="max-width: 500px;">
 <a href="../index.php">
        <img class="logo_for_lr" alt="chaicraft logo" src="../pic/logo.png"  />
      </a>
    <div class="card shadow border-0 rounded-4">
      <div class="card-body p-4">
        <h3 class="mb-3 border-bottom pb-2"><strong>Sign Up</strong></h3>
        <form action="" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required value="<?php if(isset($_POST['name'])) echo htmlspecialchars($_POST['name']); ?>">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>">
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label fw-bold">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required value="<?php if(isset($_POST['phone'])) echo htmlspecialchars($_POST['phone']); ?>">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label fw-bold">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label fw-bold">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary" name="sign_up">Sign Up</button>
          </div>
          <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login here</a></p>
        </form>
      </div>
    </div>
  </div>
</div>

