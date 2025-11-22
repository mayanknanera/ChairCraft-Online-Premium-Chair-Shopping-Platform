<?php
include 'authentication.php';
include 'config/db_con.php';

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    
    // Update order status to 'cancelled'
    $stmt = $con->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Order #$order_id has been cancelled successfully!";
    } else {
        $_SESSION['error'] = "Error cancelling order: " . $con->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "No order ID specified!";
}

header("Location: orders.php");
exit();
?>
