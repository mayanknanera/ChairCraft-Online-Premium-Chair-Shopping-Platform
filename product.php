<?php
ob_start();
session_start();
require_once 'admin/config/db_con.php';

// If not logged in, redirect to login page with return URL
if (!isset($_SESSION['auth_user'])) {
    $_SESSION['message'] = 'Please login to add items to cart';
    if (isset($_GET['chair'])) {
        header('Location: admin/login.php?redirect=' . urlencode('product.php?chair=' . $_GET['chair']));
        exit();
    }
}

// Get selected category from URL
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch all categories
$categories = [];
$category_result = $con->query("SELECT * FROM categories ORDER BY name");
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row;
}

// Get search term from URL
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build products query based on category filter and search
$query_params = [];
$query_types = '';
$where_conditions = [];

if ($selected_category > 0) {
    $where_conditions[] = "p.category_id = ?";
    $query_params[] = $selected_category;
    $query_types .= 'i';
}

if (!empty($search_term)) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $search_like = "%" . $search_term . "%";
    $query_params[] = $search_like;
    $query_params[] = $search_like;
    $query_types .= 'ss';
}

$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id";

if (!empty($where_conditions)) {
    $query .= " WHERE " . implode(" AND ", $where_conditions);
}

$query .= " ORDER BY p.id DESC";

if (!empty($query_params)) {
    $stmt = $con->prepare($query);
    $stmt->bind_param($query_types, ...$query_params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $con->query($query);
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Products</title>
     <link rel="stylesheet" href="styles/product-style.css" />
    
    
</head>
<body>
  <?php require 'nav.php';  ?>
      <div class="container-product">

     <div class="main-container">
        <!-- Category Dropdown and Search Filter -->
        <div class="category-filter">
            <div class="filter-container">
                <div class="category-dropdown">
                    <select class="category-select" onchange="updateFilters()">
                        <option value="0" <?php echo $selected_category == 0 ? 'selected' : ''; ?>>All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo $selected_category == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="search-bar">
                    <form method="GET" action="product.php" class="search-form">
                        <input type="hidden" name="category" id="category-input" value="<?php echo $selected_category; ?>">
                        <input type="text" 
                               name="search" 
                               placeholder="Search products..." 
                               value="<?php echo htmlspecialchars($search_term); ?>"
                               class="search-input">
                        <button type="submit" class="search-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <?php if (!empty($search_term)): ?>
                <div class="search-results-info">
                    <p>Search results for: <strong>"<?php echo htmlspecialchars($search_term); ?>"</strong></p>
                    <a href="product.php<?php echo $selected_category > 0 ? '?category=' . $selected_category : ''; ?>" class="clear-search">
                        Clear search
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Products Section -->
        <section class="product-list-section">
            <h2 style="text-align:center; font-size:40px; color:#e67e22; margin-bottom:40px;">
                <?php 
                $title_parts = [];
                
                if (!empty($search_term)) {
                    $title_parts[] = 'Search Results';
                }
                
                if ($selected_category == 0) {
                    if (empty($search_term)) {
                        $title_parts[] = 'Our Products';
                    }
                } else {
                    $category_name = '';
                    foreach ($categories as $cat) {
                        if ($cat['id'] == $selected_category) {
                            $category_name = $cat['name'];
                            break;
                        }
                    }
                    $title_parts[] = 'Products in ' . htmlspecialchars($category_name);
                }
                
                echo implode(' - ', $title_parts);
                ?>
            </h2>
            
            <div class="product-grid">
                <?php if (count($products) === 0): ?>
                    <div class="no-products">
                        <h3>No Products Found</h3>
                        <p><?php echo $selected_category == 0 ? 'No products are currently available.' : 'No products found in this category.'; ?></p>
                        <?php if ($selected_category > 0): ?>
                            <a href="product.php" class="chair-price add-cart-btn">View All Products</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $prod): ?>
                        <div class="product-card">
                            <div class="image-container">
                                <img src="<?php echo $prod['image'] ? 'pic/' . htmlspecialchars($prod['image']) : 'pic/hero.jpg'; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="product-image">
                                <?php if (!empty($prod['category_name'])): ?>
                                    <div class="category"><?php echo htmlspecialchars($prod['category_name']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="details">
                                <div class="product-title"><?php echo htmlspecialchars($prod['name']); ?></div>
                                <div class="price">₹<?php echo number_format($prod['price'], 2); ?></div>
                                <div class="description"><?php echo htmlspecialchars($prod['description']); ?></div>
                            </div>
                            <?php if ($prod['stock'] <= 0): ?>
                                    <a href="#" class="btn" style="opacity: 0.5; pointer-events: none;">Out of Stock</a>
                                <?php else: ?>
                                    <form method="POST" action="cart.php" style="display: inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($prod['name']); ?>">
                                        <input type="hidden" name="price" value="<?php echo $prod['price']; ?>">
                                        <button type="submit" name="addtocart" class="btn">Add to Cart</button>
                                    </form>
                                <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
     </div>

     
    </div>
    
    
    <?php ob_end_flush(); ?>
    <?php require 'footer.php';?>
   
    </body>
<script>
function updateFilters() {
    const categorySelect = document.querySelector('.category-select');
    const categoryInput = document.getElementById('category-input');
    const searchForm = document.querySelector('.search-form');
    
    // Update the hidden category input
    categoryInput.value = categorySelect.value;
    
    // Submit the form to apply both filters
    searchForm.submit();
}

// Add event listener for Enter key in search input
document.querySelector('.search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        updateFilters();
    }
});
</script>
</html>
