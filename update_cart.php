<?php
session_start();
require_once 'admin/config/db_con.php';

if (!isset($_SESSION['auth_user'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to update cart']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = (int)$_POST['cart_id'];
    $new_quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['auth_user']['id'];

    // Validate inputs
    if ($cart_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart ID']);
        exit();
    }

    if ($new_quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
        exit();
    }

    if ($new_quantity > 99) {
        echo json_encode(['success' => false, 'message' => 'Maximum quantity allowed is 99']);
        exit();
    }

    try {
        $con->begin_transaction();

        // Verify cart item belongs to user and get product info with row locking
        $verify_stmt = $con->prepare("
            SELECT c.product_id, c.quantity, p.stock, p.name
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.id = ? AND c.user_id = ?
            FOR UPDATE
        ");
        $verify_stmt->bind_param("ii", $cart_id, $user_id);
        $verify_stmt->execute();
        $result = $verify_stmt->get_result();
        $cart_item = $result->fetch_assoc();
        $verify_stmt->close();

        if (!$cart_item) {
            throw new Exception("Cart item not found");
        }

        $product_id = $cart_item['product_id'];
        $current_qty = $cart_item['quantity'];
        $available_stock = $cart_item['stock'];
        $product_name = $cart_item['name'];

        // Calculate stock difference
        $stock_difference = $new_quantity - $current_qty;

        // Check if we have enough stock for the increase
        if ($stock_difference > 0 && $stock_difference > $available_stock) {
            throw new Exception("Only $available_stock units of \"$product_name\" are available in stock");
        }

        // Update cart quantity
        $update_cart = $con->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $update_cart->bind_param("iii", $new_quantity, $cart_id, $user_id);
        $update_cart->execute();
        $update_cart->close();

        // Update product stock
        $new_stock = $available_stock - $stock_difference;
        if ($new_stock < 0) {
            throw new Exception("Insufficient stock available for \"$product_name\"");
        }

        $update_stock = $con->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $update_stock->bind_param("ii", $new_stock, $product_id);
        $update_stock->execute();
        $update_stock->close();

        $con->commit();
        echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);

    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
}
