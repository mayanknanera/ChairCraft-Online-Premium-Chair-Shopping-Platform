<?php
ob_start(); // Start output buffering
include 'authentication.php';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php'; 

include 'config/db_con.php';

// Fetch categories for dropdown
$categories = [];
$cat_result = $con->query("SELECT * FROM categories ORDER BY name");
while ($cat_row = $cat_result->fetch_assoc()) {
    $categories[] = $cat_row;
}

// Handle product operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
        
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../pic/";
            $image = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
        
        if (!empty($name)) {
            $stmt = $con->prepare("INSERT INTO products (name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $image, $category_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Product added successfully!";
            } else {
                $_SESSION['error'] = "Error adding product: " . $con->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Product name is required!";
        }
        header("Location: manage_products.php");
        exit();
    }
    
    if (isset($_POST['edit_product'])) {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $current_image = $_POST['current_image'];
        
        // Handle image upload
        $image = $current_image;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../pic/";
            $image = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            
            // Delete old image if it exists and is different
            if (!empty($current_image) && $current_image != $image && file_exists($target_dir . $current_image)) {
                unlink($target_dir . $current_image);
            }
        }
        
        if (!empty($name)) {
            $stmt = $con->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ?, category_id = ? WHERE id = ?");
            $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $image, $category_id, $id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Product updated successfully!";
            } else {
                $_SESSION['error'] = "Error updating product: " . $con->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Product name is required!";
        }
        header("Location: manage_products.php");
        exit();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get product image to delete it
    $stmt = $con->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();
    
    // Delete product
    $stmt = $con->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Delete image file if it exists
        if (!empty($image) && file_exists("../pic/" . $image)) {
            unlink("../pic/" . $image);
        }
        $_SESSION['message'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting product: " . $con->error;
    }
    $stmt->close();
    header("Location: manage_products.php");
    exit();
}

// Fetch all products with category name
$products = [];
$result = $con->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.name");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>

            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Products</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label for="productCategory" class="form-label">Category</label>
                <select class="form-select" id="productCategory" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="productPrice" name="price" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="productStock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="productStock" name="stock" required>
              </div>
              <div class="mb-3">
                <label for="productImage" class="form-label">Image</label>
                <input type="file" class="form-control" id="productImage" name="image">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="editProductId" name="id">
            <input type="hidden" id="currentImage" name="current_image">
            <div class="modal-body">
              <div class="mb-3">
                <label for="editProductName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="editProductName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="editProductDescription" class="form-label">Description</label>
                <textarea class="form-control" id="editProductDescription" name="description" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label for="editProductCategory" class="form-label">Category</label>
                <select class="form-select" id="editProductCategory" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="editProductPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="editProductPrice" name="price" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="editProductStock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="editProductStock" name="stock" required>
              </div>
              <div class="mb-3">
                <label for="editProductImage" class="form-label">Image</label>
                <input type="file" class="form-control" id="editProductImage" name="image">
                <div id="imagePreview" class="mt-2"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary" name="edit_product">Update Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="container-fluid px-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Products</h3>
                        <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            Add New Product
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-2">
                        <table id="example1" class="table table-bordered table-striped w-100" style="max-width:100%; padding:8px;">
                           <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th width="15%" align="center">Actions</th>
                                </tr>    
                           </thead> 
                            <tbody>
                                <?php if (count($products) > 0): ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                            <td><?php echo $product['stock']; ?></td>
                                            <td>
                                                <?php if (!empty($product['image'])): ?>
                                                    <img src="../pic/<?php echo htmlspecialchars($product['image']); ?>" width="60" class="product-img-thumb">
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" 
                                                        onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', '<?php echo addslashes($product['description']); ?>', <?php echo $product['price']; ?>, <?php echo $product['stock']; ?>, '<?php echo addslashes($product['image']); ?>', <?php echo $product['category_id'] ?? 'null'; ?>)">
                                                    Edit
                                                </button>
                                                <a href="manage_products.php?delete=<?php echo $product['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No products found. Add your first product!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editProduct(id, name, description, price, stock, image, category_id) {
    document.getElementById('editProductId').value = id;
    document.getElementById('editProductName').value = name;
    document.getElementById('editProductDescription').value = description;
    document.getElementById('editProductPrice').value = price;
    document.getElementById('editProductStock').value = stock;
    document.getElementById('currentImage').value = image;
    document.getElementById('editProductCategory').value = category_id;
    document.getElementById('imagePreview').innerHTML = image ? `<img src='../pic/${image}' width='80'>` : '';
    
    var editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    editModal.show();
}
</script>

<?php
include 'includes/script.php';
ob_end_flush();
?>
