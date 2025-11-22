<?php
session_start();
require_once 'admin/config/db_con.php';

if (!isset($_SESSION['auth_user'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from cart']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'])) {
    $cart_id = (int)$_POST['cart_id'];
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['auth_user']['id'];

    try {
        $con->begin_transaction();

        // Verify cart item belongs to user
        $verify_stmt = $con->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
        $verify_stmt->bind_param("ii", $cart_id, $user_id);
        $verify_stmt->execute();
        
        if ($verify_stmt->get_result()->num_rows === 0) {
            throw new Exception("Invalid cart item");
        }

        // Delete the cart item
        $delete_stmt = $con->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $delete_stmt->bind_param("ii", $cart_id, $user_id);
        $delete_stmt->execute();

        // Return the quantity to product stock
        $update_stock = $con->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        $update_stock->bind_param("ii", $quantity, $product_id);
        $update_stock->execute();

        $con->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
