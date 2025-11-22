<?php
session_start();
require_once 'admin/config/db_con.php';

if (!isset($_SESSION['auth_user'])) {
    $_SESSION['message'] = 'Please login to cancel order';
    header('Location: admin/login.php');
    exit();
}

$user_id = $_SESSION['auth_user']['id'];

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    
    // Verify order belongs to user and is pending
    $stmt = $con->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        if ($order['status'] == 'pending') {
            // Update order status to 'user_cancelled'
            $update_stmt = $con->prepare("UPDATE orders SET status = 'user_cancelled' WHERE id = ?");
            $update_stmt->bind_param("i", $order_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['order_success'] = "Order #$order_id has been cancelled successfully!";
            } else {
                $_SESSION['order_error'] = "Error cancelling order: " . $con->error;
            }
            $update_stmt->close();
        } else {
            $_SESSION['order_error'] = "Order cannot be cancelled as it is not pending.";
        }
    } else {
        $_SESSION['order_error'] = "Order not found or does not belong to you.";
    }
    $stmt->close();
} else {
    $_SESSION['order_error'] = "No order ID specified!";
}

header("Location: my-profile.php");
exit();
?>
