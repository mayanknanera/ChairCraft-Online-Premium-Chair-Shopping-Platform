<?php
include 'authentication.php';
?>
<!-- navbar.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <div class="ms-auto d-flex align-items-left pe-2">
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
      <?php
          if (isset($_SESSION['auth'])) {
              echo "Welcome, " . $_SESSION['auth_user']['name'];
          } else {
              echo "Guest";
          }
      ?>
     </button>

      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
        <form action="user_control.php" method="POST">
          <button type="submit" class="dropdown-item" name="btn_logout">Logout</button>
        </form>
    </div>
  </div>
</nav>
