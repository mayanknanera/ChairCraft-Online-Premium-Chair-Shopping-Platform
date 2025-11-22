<?php
session_start();
require_once 'admin/config/db_con.php';

if (!isset($_SESSION['auth_user'])) {
    $_SESSION['message'] = 'Please login to view your profile';
    header('Location: admin/login.php');
    exit();
}

$user_id = $_SESSION['auth_user']['id'];

$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

$orders_query = "SELECT o.*, oi.product_name, oi.quantity, oi.price, u.name as customer_name, u.email as customer_email, u.phone as customer_phone
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN users u ON o.user_id = u.id
                WHERE o.user_id = ? AND o.status != 'user_cancelled'
                ORDER BY o.created_at DESC, oi.id ASC";
$stmt = $con->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/my-profile.css">

</head>
<body>
    <?php require 'nav.php'; ?>

    <div class="container">
        <div class="user-info card">
            <div class="card-header">
                <h2 class="mb-0">Profile</h2>
            </div>

            <div class="card-body">
                <div class="detail-item">
                    <strong>Name:</strong> 
                    <span><?php echo htmlspecialchars($user['name']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong> 
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="detail-item">
                    <strong>Phone:</strong> 
                    <span><?php echo htmlspecialchars($user['phone']); ?></span>
                </div>
                <button id="editProfileBtn">Edit Profile</button>
            </div>
        </div>

        <?php if (isset($_SESSION['order_success'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['order_success'];
                unset($_SESSION['order_success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['order_error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['order_error'];
                unset($_SESSION['order_error']);
                ?>
            </div>
        <?php endif; ?>

        <div id="editProfileForm" class="card">
            <div class="card-header">
                <h2>Edit Profile</h2>
            </div>
            <div class="card-body">
                <form action="admin/user_control.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

                    <div class="modal-footer">
                        <button type="submit" class="btn-primary" name="update_user">Edit</button>
                        <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <h2>My Orders</h2>

        <?php if ($orders_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $current_order_id = null;
                    while ($order = $orders_result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'] . ' +7 days')); ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td><?php echo $order['quantity']; ?></td>
                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></td>
                        <td>
                            <span class="badge badge-<?php
                                switch($order['status']) {
                                    case 'pending': echo 'warning'; break;
                                    case 'processing': echo 'info'; break;
                                    case 'completed': echo 'success'; break;
                                    case 'cancelled': echo 'danger'; break;
                                    case 'user_cancelled': echo 'danger'; break;
                                    default: echo 'secondary'; break;
                                }
                            ?>">
                                <?php
                                if ($order['status'] == 'user_cancelled') {
                                    echo 'Cancelled';
                                } else {
                                    echo ucfirst($order['status']);
                                }
                                ?>
                            </span>
                        </td>
                            <td>
                                <?php if ($order['status'] === 'pending'): ?>
                                    <a href="cancel_order.php?order_id=<?php echo $order['id']; ?>" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                    </tr>
                    <?php
                    $current_order_id = $order['id'];
                    endwhile;
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-orders alert alert-warning">
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="product.php" class="btn-success">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('editProfileBtn').addEventListener('click', function () {
            document.getElementById('editProfileForm').style.display = 'block';
        });

        document.getElementById('cancelEditBtn').addEventListener('click', function () {
            document.getElementById('editProfileForm').style.display = 'none';
        });
    </script>

    
</body>
</html>
