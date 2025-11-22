<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php'; 
include 'config/db_con.php';

// Fetch all orders
$orders_query = "SELECT o.*, u.name as customer_name 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC";
$orders_result = $con->query($orders_query);
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
              <li class="breadcrumb-item active">Orders</li>
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
                        <h3 class="card-title">Orders</h3>
                    </div>
                    <!-- /.card- -->
                    <div class="card-body p-2">
                        <table id="example1" class="table table-bordered table-striped w-100" style="max-width:100%; padding:8px;">
                            <thead>
                                <tr>
                                    <th style="width: 120px;">Order ID</th>
                                    <th style="width: 120px;">Customer</th>
                                    <th style="width: 120px;">Date Ordered</th>	 
                                    <th style="width: 100px;">Price</th>
                                    <th style="width: 150px;">Payment Method</th>	
                                    <th style="width: 120px;">Status</th>
                                    <th style="width: 180px;">Action</th>
                                </tr>	
                            </thead>
                            <tbody>
                                <?php while ($order = $orders_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                        <td>
                                            <?php
                                            if ($order['status'] == 'user_cancelled') {
                                                echo 'Cancelled';
                                            } else {
                                                echo ucfirst($order['status']);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($order['status'] == 'pending'): ?>
                                                <button class="btn btn-warning btn-sm" onclick="confirmOrder(<?php echo $order['id']; ?>)">Confirm</button>
                                                <button class="btn btn-danger btn-sm" onclick="cancelOrder(<?php echo $order['id']; ?>)">Cancel</button>
                                            <?php elseif ($order['status'] == 'confirmed'): ?>
                                                <button class="btn btn-success btn-sm" disabled>Confirmed</button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteOrder(<?php echo $order['id']; ?>)">Delete</button>
                                            <?php elseif ($order['status'] == 'cancelled' || $order['status'] == 'user_cancelled'): ?>
                                                <button class="btn btn-danger btn-sm" disabled>Cancelled</button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteOrder(<?php echo $order['id']; ?>)">Delete</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function confirmOrder(orderId) {
    if (confirm('Are you sure you want to confirm this order?')) {
        // Redirect to confirm order action
        window.location.href = 'confirm_order.php?id=' + orderId;
    }
}

function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Redirect to cancel order action
        window.location.href = 'cancel_order.php?id=' + orderId;
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        // Redirect to delete order action
        window.location.href = 'delete_order.php?id=' + orderId;
    }
}
</script>

<?php
include 'includes/script.php';
?>
