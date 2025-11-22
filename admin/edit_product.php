<?php
session_start();
require_once 'config/db_con.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: manage_products.php');
    exit();
}

// Fetch product
$stmt = $con->prepare('SELECT * FROM products WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    header('Location: manage_products.php');
    exit();
}

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $image = $product['image'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['image']['name']);
        $target_dir = '../pic/';
        $target_file = $target_dir . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $img_name;
        }
    }

    $stmt2 = $con->prepare('UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?');
    $stmt2->bind_param('ssdisi', $name, $description, $price, $stock, $image, $id);
    if ($stmt2->execute()) {
        $_SESSION['message'] = 'Product updated!';
    } else {
        $_SESSION['message'] = 'Error updating product.';
    }
    header('Location: manage_products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../styles/main-style.css">
</head>
<body>
    <h2>Edit Product</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required></label><br><br>
        <label>Description: <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea></label><br><br>
        <label>Price: <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required></label><br><br>
        <label>Stock: <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required></label><br><br>
        <label>Image: <input type="file" name="image" accept="image/*"></label>
        <?php if ($product['image']): ?><br><img src="../pic/<?php echo $product['image']; ?>" width="80"><?php endif; ?><br><br>
        <button type="submit">Update Product</button>
    </form>
    <a href="manage_products.php">Back to Products</a>
</body>
</html>
