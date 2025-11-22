<?php

include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php'; 
include 'config/db_con.php';
?>
<!-- Content Wrapper. Contains page content --> 
<div class="content-wrapper">
        <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Registered Users</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
     <div class="container-fluid px-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Registered Users</h3>
                        <a href="dashboard.php" class="btn btn-danger float-right">Back</a>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="user_control.php" method="POST">
                                    <div class="modal-body">
                                        <?php
                                        if (isset($_GET['id'])) {
                                            // Fetch user data from database
                                            $user_id = $_GET['id'];
                                            $query = "SELECT * FROM users WHERE id='".$user_id."' LIMIT 1";
                                            $result = mysqli_query($con, $query);

                                            if (mysqli_num_rows($result) > 0) {
                                                $user = mysqli_fetch_assoc($result);
                                                
                                            } else {
                                                echo "<div class='alert alert-danger'>User not found</div>";
                                                exit();
                                            }
                                        }
                                        ?>
                                        <div class="mb-3">
                                            <input type="hidden" name="user_id" value="<?php echo isset($user) ? $user['id'] : ''; ?>">
                
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?php echo isset($user) ? $user['name'] : ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo isset($user) ? $user['email'] : ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone number" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="<?php echo isset($user) ? $user['phone'] : ''; ?>">
                                        </div>
                                            <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" value="<?php echo isset($user) ? $user['password'] : ''; ?>" name="password" placeholder="Enter password">
                                        </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-control" name="role_as" required>
                                                <option value="0">User</option>
                                                <option value="1">Admin</option>
                                            </select>
                                        </div>
                                    
                                    </div>
                                    <div class="modal-footer pe-3">
                                        <button type="submit" class="btn btn-primary" name="update_user">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>