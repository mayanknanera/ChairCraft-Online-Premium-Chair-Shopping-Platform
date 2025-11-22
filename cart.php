<?php
session_start();
require_once 'admin/config/db_con.php';

// Check if user is logged in
if (!isset($_SESSION['auth_user'])) {
    $_SESSION['message'] = 'Please login to add items to cart';
    header('Location: admin/login.php');
    exit();
}

$user_id = $_SESSION['auth_user']['id'];

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addtocart'])) {
    $product_id = $_POST['product_id'];

    // Get product details
    $product_query = "SELECT id, name, stock, price FROM products WHERE id = $product_id";
    $product_result = mysqli_query($con, $product_query);
    $product = mysqli_fetch_assoc($product_result);

    if ($product) {
        if ($product['stock'] <= 0) {
            $_SESSION['error'] = "Product is out of stock";
            header('Location: product.php');
            exit();
        }

        // Check if product already in cart
        $cart_query = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $cart_result = mysqli_query($con, $cart_query);
        $cart_item = mysqli_fetch_assoc($cart_result);

        if ($cart_item) {
            // Update quantity if already in cart
            $new_quantity = $cart_item['quantity'] + 1;
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = " . $cart_item['id'];
            mysqli_query($con, $update_query);
        } else {
            // Add new item to cart
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
            mysqli_query($con, $insert_query);
        }

        // Update product stock
        $new_stock = $product['stock'] - 1;
        $update_stock_query = "UPDATE products SET stock = $new_stock WHERE id = $product_id";
        mysqli_query($con, $update_stock_query);

        $_SESSION['message'] = "Product added to cart successfully!";
        header('Location: cart.php');
        exit();
    } else {
        $_SESSION['error'] = "Product not found";
        header('Location: product.php');
        exit();
    }
}

// Fetch cart items
$cart_query = "SELECT c.*, p.name, p.price, p.image, p.stock 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $con->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - ChairCraft</title>
    <!-- <link rel="stylesheet" href="styles/pofile.css"> -->
    <link rel="stylesheet" href="styles/cart-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
<body>
    <?php require 'nav.php'; ?>

    <div class="body-color ">
    <div class="cart-container ">
        <h2 class="cart-header">Your Shopping Cart</h2>

        <!-- <?php if (isset($_SESSION['message'])): ?>
            <div class="message success" style="font-size: 1.3rem;">
                <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?> -->

        <?php if ($cart_items->num_rows > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $cart_items->fetch_assoc()):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <img src="pic/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                     class="product-image">
                            </td>
                            <td class="product-name"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="product-price">₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <div class="quantity-controls">
                                    <button class="quantity-btn decrease" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">-</button>
                                    <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>"
                                           min="1" max="<?php echo $item['stock'] + $item['quantity']; ?>"
                                           data-id="<?php echo $item['id']; ?>"
                                           data-price="<?php echo $item['price']; ?>">
                                    <button class="quantity-btn increase" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">+</button>
                                </div>
                            </td>
                            <td class="subtotal">₹<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <button class="remove-btn" data-id="<?php echo $item['id']; ?>" 
                                        data-product-id="<?php echo $item['product_id']; ?>"
                                        data-quantity="<?php echo $item['quantity']; ?>">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total-section">
                <h3 class="total">Total: ₹<?php echo number_format($total, 2); ?></h3>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                </form>
            </div>
        <?php else: ?>
            <p style="font-size:16px; margin-top:8px;">Your cart is empty. <a href="product.php">Continue shopping</a></p>
        <?php endif; ?>
    </div>
    </div>

    <?php require 'footer.php'; ?>

    <script>
    $(document).ready(function() {
        function updateQuantity(cartId, newQuantity, price) {
            $.ajax({
                url: 'update_cart.php',
                method: 'POST',
                data: {
                    cart_id: cartId,
                    quantity: newQuantity
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Update subtotal
                        const subtotal = newQuantity * price;
                        const row = $(`button[data-id="${cartId}"]`).closest('tr');
                        row.find('.subtotal').text('₹' + subtotal.toFixed(2));

                        // Update total
                        let total = 0;
                        $('.subtotal').each(function() {
                            total += parseFloat($(this).text().replace('₹', ''));
                        });
                        $('.total-section h3').text('Total: ₹' + total.toFixed(2));
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Error updating cart');
                }
            });
        }

        // Increase quantity
        $('.increase').click(function() {
            const input = $(this).siblings('.quantity-input');
            const currentQty = parseInt(input.val());
            const maxQty = parseInt(input.attr('max'));
            if (currentQty >= maxQty) {
                alert("Product not available in requested quantity");
            } else {
                input.val(currentQty + 1);
                updateQuantity($(this).data('id'), currentQty + 1, $(this).data('price'));
            }
        });

        // Decrease quantity
        $('.decrease').click(function() {
            const input = $(this).siblings('.quantity-input');
            const currentQty = parseInt(input.val());
            if (currentQty > 1) {
                input.val(currentQty - 1);
                updateQuantity($(this).data('id'), currentQty - 1, $(this).data('price'));
            }
        });

        // Manual input
        $('.quantity-input').change(function() {
            let newQty = parseInt($(this).val());
            const maxQty = parseInt($(this).attr('max'));
            if (newQty < 1) {
                $(this).val(1);
                updateQuantity($(this).data('id'), 1, $(this).data('price'));
            } else if (newQty > maxQty) {
                alert("Requested quantity not available in stock");
                $(this).val(maxQty);
            } else {
                updateQuantity($(this).data('id'), newQty, $(this).data('price'));
            }
        });

        // Remove item from cart
        $('.remove-btn').click(function() {
            const cartId = $(this).data('id');
            const row = $(this).closest('tr');
            
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                $.ajax({
                    url: 'remove_from_cart.php',
                    method: 'POST',
                    data: {
                        cart_id: cartId,
                        product_id: $(this).data('product-id'),
                        quantity: $(this).data('quantity')
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.success) {
                            // Remove the row
                            row.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Update total
                                let total = 0;
                                $('.subtotal').each(function() {
                                    total += parseFloat($(this).text().replace('₹', ''));
                                });
                                
                                if ($('.cart-table tbody tr').length === 0) {
                                    // If no items left, show empty cart message
                                    $('.cart-table').replaceWith('<p>Your cart is empty. <a href="product.php">Continue shopping</a></p>');
                                    $('.total-section').remove();
                                } else {
                                    // Update total display
                                    $('.total-section h3').text('Total: ₹' + total.toFixed(2));
                                }
                            });
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function() {
                        alert('Error removing item from cart');
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
