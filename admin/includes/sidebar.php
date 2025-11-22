  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="position:fixed; top:0; left:0; height:100vh; overflow-y:auto; z-index:1050;">
    <!-- Brand Logo -->
  <a href="dashboard.php" class="brand-link" style="text-decoration:none;">
      <img src="../pic/logo2.png" alt="This is logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
           
      <span style="font-weight:600; letter-spacing:1px;">ChairCraft</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php
            $current_page = basename($_SERVER['PHP_SELF']);
          ?>
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p style="font-weight:600; letter-spacing:1px;">Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="registered_users.php" class="nav-link <?php echo ($current_page == 'registered_users.php') ? 'active' : ''; ?>">
              <i class="fas fa-user-plus nav-icon"></i>
              <p style="font-weight:600; letter-spacing:1px;">Registered Users</p>
            </a>
          </li>

           <li class="nav-item">
            <a href="manage_products.php" class="nav-link <?php echo (in_array($current_page, ['manage_products.php','edit_product.php'])) ? 'active bg-primary' : ''; ?>" style="<?php echo (in_array($current_page, ['manage_products.php','edit_product.php'])) ? ';font-weight:600;letter-spacing:1px;' : ''; ?>">
              <i class="fas fa-box-open nav-icon"></i>
              <p style="font-weight:600; letter-spacing:1px;">Products</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="categories.php" class="nav-link <?php echo ($current_page == 'categories.php') ? 'active' : ''; ?>">
              <i class="fas fa-tags nav-icon"></i>
              <p style="font-weight:600; letter-spacing:1px;">Categories</p>
            </a>
          </li>

         <li class="nav-item">
            <a href="orders.php" class="nav-link <?php echo ($current_page == 'orders.php') ? 'active' : ''; ?>">
              <i class="fas fa-shopping-cart nav-icon"></i>
              <p style="font-weight:600; letter-spacing:1px;">Orders</p>
            </a>
          </li>    
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>