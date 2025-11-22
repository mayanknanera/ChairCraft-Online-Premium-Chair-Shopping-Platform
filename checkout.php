    <?php
session_start();
require_once 'admin/config/db_con.php';

// Check if user is logged in
if (!isset($_SESSION['auth_user'])) {
    $_SESSION['message'] = 'Please login to proceed to checkout';
    header('Location: admin/login.php');
    exit();
}

$user_id = $_SESSION['auth_user']['id'];

// Fetch cart items
$cart_query = "SELECT c.*, p.name, p.price, p.image 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $con->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

// Calculate total
$total = 0;
$cart_data = [];
while ($item = $cart_items->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $cart_data[] = $item;
}

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
        $address = trim($_POST['address']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $zip_code = trim($_POST['zip_code']);
        $payment_method = 'cash_on_delivery'; // Fixed as cash on delivery

        $errors = [];
        $display_address = '';
        $display_city = '';
        $display_state = '';
        $display_zip = '';

        // Validate address
        if (empty($address)) {
            $errors[] = 'Shipping address is required';
        } elseif (strlen($address) < 10) {
            $errors[] = 'Shipping address must be at least 10 characters long';
        } else {
            $display_address = $address;
        }

        // Validate city
        if (empty($city)) {
            $errors[] = 'City is required';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $city) || strlen($city) < 3) {
            $errors[] = 'City must contain only letters and spaces, and be at least 3 characters long';
        } else {
            $display_city = $city;
        }

        // Validate state
        if (empty($state)) {
            $errors[] = 'State is required';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $state) || strlen($state) < 5) {
            $errors[] = 'State must contain only letters and spaces, and be at least 5 characters long';
        } else {
            $display_state = $state;
        }

        // Validate zip code
        if (empty($zip_code)) {
            $errors[] = 'ZIP code is required';
        } elseif (!preg_match('/^\d{6}$/', $zip_code)) {
            $errors[] = 'ZIP code must be exactly 6 digits';
        } else {
            $display_zip = $zip_code;
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['display_address'] = $display_address;
            $_SESSION['display_city'] = $display_city;
            $_SESSION['display_state'] = $display_state;
            $_SESSION['display_zip'] = $display_zip;
            header('Location: checkout.php');
            exit();
        }

    try {
        $con->begin_transaction();

        // Check if orders table exists
        $table_check = $con->query("SHOW TABLES LIKE 'orders'");
        if ($table_check->num_rows == 0) {
            throw new Exception("Orders table does not exist. Please run the database setup.");
        }

        // Insert order
        $order_stmt = $con->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method, shipping_address) 
                                    VALUES (?, ?, 'pending', ?, ?)");
        $order_stmt->bind_param("idss", $user_id, $total, $payment_method, $address);
        $order_stmt->execute();
        $order_id = $con->insert_id;

        // Insert order items
        foreach ($cart_data as $item) {
            $order_item_stmt = $con->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                                             VALUES (?, ?, ?, ?, ?)");
            $order_item_stmt->bind_param("iisid", $order_id, $item['product_id'], $item['name'], $item['quantity'], $item['price']);
            $order_item_stmt->execute();
        }

        // Clear cart
        $clear_cart_stmt = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_cart_stmt->bind_param("i", $user_id);
        $clear_cart_stmt->execute();

        $con->commit();

        $_SESSION['order_success'] = 'Order placed successfully! Your order ID is #' . $order_id;
        header('Location: my-profile.php');
        exit();

    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['error'] = 'Error placing order: ' . $e->getMessage();
        header('Location: checkout.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ChairCraft</title>
    <link rel="stylesheet" href="styles/checkout.css">
</head>
<body>
    <div class="main-checkout-bg">
        <?php require 'nav.php'; ?>
    <div class="checkout-container">
        <div class="checkout-form">
            <h2>Shipping Information</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="checkout.php">
                <div class="form-group">
                    <label class="required-label">Shipping address *</label>
                    <textarea id="address" name="address" rows="3" required
                              placeholder="Enter your complete address"
                              minlength="10"><?php echo htmlspecialchars($_SESSION['display_address'] ?? ''); unset($_SESSION['display_address']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City *</label>
                    <input type="text" id="city" name="city" required
                           placeholder="Enter your city" value="<?php echo htmlspecialchars($_SESSION['display_city'] ?? ''); unset($_SESSION['display_city']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="state">State *</label>
                    <input type="text" id="state" name="state" required
                           placeholder="Enter your state" value="<?php echo htmlspecialchars($_SESSION['display_state'] ?? ''); unset($_SESSION['display_state']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="zip_code">ZIP Code *</label>
                    <input type="text" id="zip_code" name="zip_code" required
                           placeholder="Enter your ZIP code" value="<?php echo htmlspecialchars($_SESSION['display_zip'] ?? ''); unset($_SESSION['display_zip']); ?>">
                </div>

                <div class="payment-method">
                    <label>Payment Method</label>
                    <p><strong>Cash on Delivery</strong> (Only available payment method)</p>
                </div>

                <button type="submit" name="place_order" class="submit-btn">Place Order</button>
            </form>
        </div>

        <div class="order-summary">
            <h3>Order Summary</h3>
            <?php if (count($cart_data) > 0): ?>
                <?php foreach ($cart_data as $item): ?>
                    <div class="order-item">
                        <div class="item-details">
                            <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="item-quantity">Quantity: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="item-price">
                            ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="total-section">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong>Total:</strong>
                        <span class="total-amount">₹<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
    <?php require 'footer.php'; ?>
</body>
</html>
