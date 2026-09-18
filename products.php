<?php
$pageTitle = "Premium Products";
require_once 'config/connect.php';
include 'includes/header.php';
include 'includes/breadcrumb.php';

// Fetch Phone Number for the Call Icon
$contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
$phone_number = !empty($contact['phone']) ? $contact['phone'] : '+91-0000000000';

// --- FILTER & PAGINATION LOGIC ---

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$cat_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Pagination settings
$limit = 9;
$offset = ($page - 1) * $limit;

// Build Dynamic WHERE Clause
$where_sql = "WHERE status = 1";
if (!empty($search)) {
    $where_sql .= " AND pro_name LIKE '%$search%'";
}
if (!empty($cat_filter)) {
    $where_sql .= " AND pro_cate = '$cat_filter'";
}

// Get Total Records for Pagination
$count_query = mysqli_query($conn, "SELECT COUNT(id) as total FROM products $where_sql");
$count_row = mysqli_fetch_assoc($count_query);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);

$prod_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products $where_sql ORDER BY id DESC LIMIT $offset, $limit");

$categories_query = mysqli_query($conn, "SELECT cate_id, categories FROM categories WHERE status = 1 ORDER BY categories ASC");
?>

<section class="products-page-section">
    <div class="container-ng">
        <div class="row g-5">

            <!-- LEFT SIDEBAR -->
            <div class="col-lg-3">
                <div class="sidebar-wrapper">

                    <!-- Search Box -->
                    <h3 class="sidebar-title">Search</h3>
                    <form action="products.php" method="GET" class="search-form">
                        <!-- Preserve category filter if exists -->
                        <?php if (!empty($cat_filter)): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($cat_filter); ?>">
                        <?php endif; ?>

                        <input type="text" name="search" class="search-input" placeholder="Find a product..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                    </form>

                    <!-- Category Filter -->
                    <h3 class="sidebar-title">Categories</h3>
                    <ul class="cat-list">
                        <li>
                            <a href="products.php" class="cat-link <?php echo empty($cat_filter) ? 'active' : ''; ?>">
                                <i class="fas fa-check-circle"></i> All Products
                            </a>
                        </li>
                        <?php
                        if (mysqli_num_rows($categories_query) > 0) {
                            while ($cat = mysqli_fetch_assoc($categories_query)) {
                                $is_active = ($cat_filter == $cat['cate_id']) ? 'active' : '';
                        ?>
                                <li>
                                    <a href="products.php?category=<?php echo urlencode($cat['cate_id']); ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="cat-link <?php echo $is_active; ?>">
                                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($cat['categories']); ?>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- RIGHT PRODUCT GRID -->
            <div class="col-lg-9">

                <!-- Results Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="m-0 text-muted">Showing <strong><?php echo mysqli_num_rows($prod_query); ?></strong> results <?php echo !empty($search) ? 'for "' . htmlspecialchars($search) . '"' : ''; ?></p>
                </div>

                <div class="row g-4">
                    <?php
                    if (mysqli_num_rows($prod_query) > 0) {
                        while ($prod = mysqli_fetch_assoc($prod_query)) {
                            // Image Path Logic
                            $db_img = $prod['pro_img'];
                            $p_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
                            if (!empty($db_img) && !file_exists($p_img)) {
                                $p_img = 'admin/' . $db_img;
                            }
                    ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="prod-card">
                                    <div class="prod-img-wrapper">
                                        <span class="export-badge">Export Grade</span>
                                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" >
                                        <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($prod['pro_name']); ?>">
                                        </a>
                                    </div>

                                    <div class="prod-body">
                                        <h3 class="prod-title">
                                            <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" style="text-decoration: none;">
                                                <?php echo htmlspecialchars($prod['pro_name']); ?>
                                        </h3>
                                        </a>

                                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" class="prod-link">
                                            View Details <i class="fas fa-chevron-right"></i>
                                        </a>

                                        <div class="prod-actions">
                                            <a href="tel:<?php echo $phone_number; ?>" class="btn-call" title="Call Us">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                            <a href="contact.php?product=<?php echo urlencode($prod['pro_name']); ?>" class="btn-inquire">
                                                Inquire Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center py-5">
                                <i class="fas fa-box-open mb-3" style="font-size: 3rem; color: #ddd;"></i>
                                <h4 style="color: #0a2540;">No Products Found</h4>
                                <p class="text-muted">Try adjusting your search or category filter.</p>
                                <a href="products.php" class="btn btn-inquire mt-3 d-inline-flex px-4" style="width: auto;">Clear Filters</a>
                              </div>';
                    }
                    ?>
                </div>

                <!-- Pagination Logic -->
                <?php if ($total_pages > 1): ?>
                    <ul class="premium-pagination">
                        <!-- Previous Button -->
                        <?php if ($page > 1): ?>
                            <li>
                                <a href="?page=<?php echo ($page - 1); ?><?php echo !empty($cat_filter) ? '&category=' . $cat_filter : ''; ?><?php echo !empty($search) ? '&search=' . $search : ''; ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a href="?page=<?php echo $i; ?><?php echo !empty($cat_filter) ? '&category=' . $cat_filter : ''; ?><?php echo !empty($search) ? '&search=' . $search : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Button -->
                        <?php if ($page < $total_pages): ?>
                            <li>
                                <a href="?page=<?php echo ($page + 1); ?><?php echo !empty($cat_filter) ? '&category=' . $cat_filter : ''; ?><?php echo !empty($search) ? '&search=' . $search : ''; ?>">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>