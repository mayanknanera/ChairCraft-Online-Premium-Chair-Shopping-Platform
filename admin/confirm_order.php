<?php
include 'authentication.php';
include 'config/db_con.php';

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    
    // Update order status to 'confirmed'
    $stmt = $con->prepare("UPDATE orders SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Order #$order_id has been confirmed successfully!";
    } else {
        $_SESSION['error'] = "Error confirming order: " . $con->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "No order ID specified!";
}

header("Location: orders.php");
exit();
?>
