<?php
session_start();
require_once 'config/db_con.php';

// Fetch categories
$categories = [];
$result = $con->query("SELECT id, name FROM categories ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = basename($_FILES['image']['name']);
        $target_dir = '../pic/';
        $target_file = $target_dir . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $img_name;
        }
    }

    $stmt = $con->prepare('INSERT INTO products (name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssdisi', $name, $description, $price, $stock, $image, $category_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Product added successfully!';
    } else {
        $_SESSION['message'] = 'Error adding product.';
    }
    header('Location: add_product.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../styles/main-style.css">
</head>
<body>
    <h2>Add New Product</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> </div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Name: <input type="text" name="name" required></label><br><br>
        <label>Description: <textarea name="description" required></textarea></label><br><br>
        <label>Price: <input type="number" name="price" step="0.01" required></label><br><br>
        <label>Stock: <input type="number" name="stock" required></label><br><br>
        <label>Image: <input type="file" name="image" accept="image/*"></label><br><br>
        <button type="submit">Add Product</button>
    </form>
    <a href="manage_products.php">Manage Products</a>
</body>
</html>
