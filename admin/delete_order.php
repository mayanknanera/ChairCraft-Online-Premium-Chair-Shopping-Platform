<?php
session_start();
include 'authentication.php';
include 'config/db_con.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['auth_user']) || $_SESSION['auth_user']['role_as'] != '1') {
    $_SESSION['error'] = "Access denied. Admin privileges required.";
    header("Location: ../admin/login.php");
    exit();
}

// Check if order ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Validate order ID
    if ($order_id <= 0) {
        $_SESSION['error'] = "Invalid order ID.";
        header("Location: orders.php");
        exit();
    }

    try {
        $con->begin_transaction();

        // First delete order items to maintain referential integrity
        $delete_items_stmt = $con->prepare("DELETE FROM order_items WHERE order_id = ?");
        $delete_items_stmt->bind_param("i", $order_id);
        $delete_items_result = $delete_items_stmt->execute();
        $delete_items_stmt->close();

        if ($delete_items_result) {
            // Then delete the order
            $delete_order_stmt = $con->prepare("DELETE FROM orders WHERE id = ?");
            $delete_order_stmt->bind_param("i", $order_id);
            $delete_order_result = $delete_order_stmt->execute();
            $delete_order_stmt->close();

            if ($delete_order_result) {
                $con->commit();
                $_SESSION['message'] = "Order #$order_id has been deleted successfully.";
            } else {
                throw new Exception("Error deleting order: " . $con->error);
            }
        } else {
            throw new Exception("Error deleting order items: " . $con->error);
        }
    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['error'] = $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid order ID.";
}

// Redirect back to orders page
header("Location: orders.php");
exit();
?>
