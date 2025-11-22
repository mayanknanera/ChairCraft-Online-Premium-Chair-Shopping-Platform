<?php
session_start();
require_once 'config/db_con.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function respond($success, $data = [], $msg = '') {
    echo json_encode(['success' => $success, 'data' => $data, 'msg' => $msg]);
    exit();
}

if ($action === 'list') {
    $result = $con->query('SELECT * FROM products ORDER BY id DESC');
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    respond(true, $products);
}

if ($action === 'add') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = uniqid('prod_') . '_' . basename($_FILES['image']['name']);
        $target_dir = '../pic/';
        $target_file = $target_dir . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $img_name;
        }
    }
    $stmt = $con->prepare('INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('ssdis', $name, $description, $price, $stock, $image);
    if ($stmt->execute()) {
        respond(true, [], 'Product added successfully!');
    } else {
        respond(false, [], 'Error adding product.');
    }
}

if ($action === 'edit') {
    $id = intval($_POST['id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $image = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_name = uniqid('prod_') . '_' . basename($_FILES['image']['name']);
        $target_dir = '../pic/';
        $target_file = $target_dir . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $img_name;
        }
    }
    $stmt = $con->prepare('UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?');
    $stmt->bind_param('ssdisi', $name, $description, $price, $stock, $image, $id);
    if ($stmt->execute()) {
        respond(true, [], 'Product updated!');
    } else {
        respond(false, [], 'Error updating product.');
    }
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    $stmt = $con->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        respond(true, [], 'Product deleted!');
    } else {
        respond(false, [], 'Error deleting product.');
    }
}

respond(false, [], 'Invalid action.');
