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

            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

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
    <!-- /.content-header -->
    <div class="container-fluid px-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Registered Users</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-2">
                        <table id="example1" class="table table-bordered table-striped w-100" style="max-width:100%; padding:8px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Action</th>
                      
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                                $query = "SELECT * FROM users";
                                $result = mysqli_query($con, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['phone']}</td>
                                                <td>";
                                                    if ($row['role_as'] == "0") {
                                                        echo "User";
                                                    } elseif ($row['role_as'] == "1") {
                                                        echo "Admin";
                                                    } else {
                                                        echo "Invalid";
                                                    }
                                              echo "</td>
                                                <td>
                                                    <a href='edit_registered_user.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='delete_registered_user.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
                                                </td>
                                              </tr>";
                                    }
                                } 
                                else 
                                {
                                    echo "<tr><td colspan='5'>No users found</td></tr>";
                                }

                              ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include 'includes/script.php';
?>