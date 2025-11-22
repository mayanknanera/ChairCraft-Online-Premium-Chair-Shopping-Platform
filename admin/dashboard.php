<?php

include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';
include 'config/db_con.php';

// Initialize counts
$newOrdersCount = 0;
$categoriesCount = 0;
$registeredUsersCount = 0;
$productsCount = 0;

// Query to get the count of pending orders
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $newOrdersCount = $row['count'];
}

// Query to get the count of categories
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM categories");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $categoriesCount = $row['count'];
}

// Query to get the count of registered users
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM users");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $registeredUsersCount = $row['count'];
}

// Query to get the count of products
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM products");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $productsCount = $row['count'];
}
?>


 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php if (isset($_SESSION['message'])) : ?>
  <div class="alert alert-success">
    <?php
      echo $_SESSION['message'];
        unset($_SESSION['message']);
    ?>
  </div>
<?php endif; ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
<h3><?php echo $newOrdersCount; ?></h3>

                <p>New Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="orders.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
<h3><?php echo $categoriesCount; ?><sup style="font-size: 20px"></sup></h3>

                <p>Categories</p>
              </div>
              <div class="icon">
                <i class="fas fa-tags"></i>
              </div>
              <a href="categories.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
<h3><?php echo $registeredUsersCount; ?></h3>

                <p>Registered Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="registered_users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
<h3><?php echo $productsCount; ?></h3>

                <p>Products</p>
              </div>
              <div class="icon">
                <i class="fas fa-box-open"></i>
              </div>
              <a href="manage_products.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        
        <!-- /.card -->
        </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

        <!-- /.card -->
        </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
<?php
include 'includes/script.php';
?>