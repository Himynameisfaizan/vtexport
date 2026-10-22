<?php
require_once 'config/connect.php';

// --- 1. HANDLE SIDEBAR QUOTE FORM SUBMISSION ---
$quote_msg = "";
$quote_msg_type = "";
if (isset($_POST['submit_sidebar_quote'])) {
    $q_name    = mysqli_real_escape_string($conn, trim($_POST['q_name']));
    $q_email   = mysqli_real_escape_string($conn, trim($_POST['q_email']));
    $q_phone   = mysqli_real_escape_string($conn, trim($_POST['q_phone']));
    $q_subject = mysqli_real_escape_string($conn, trim($_POST['q_subject']));
    $q_message = mysqli_real_escape_string($conn, trim($_POST['q_message']));

    if (!empty($q_name) && !empty($q_email) && !empty($q_phone)) {
        $ins_quote = "INSERT INTO inquiries (name, email, phone, subject, message, status) 
                      VALUES ('$q_name', '$q_email', '$q_phone', '$q_subject', '$q_message', 0)";
        if (mysqli_query($conn, $ins_quote)) {
            $quote_msg = "Thank you! Your quote request has been submitted.";
            $quote_msg_type = "success";
        } else {
            $quote_msg = "Something went wrong. Please try again.";
            $quote_msg_type = "danger";
        }
    } else {
        $quote_msg = "Please fill in all required fields.";
        $quote_msg_type = "warning";
    }
}

// --- 2. GET URL PARAMETERS (SLUG, SUB-SLUG, SEARCH, PAGE) ---
$slug     = isset($_GET['slug']) ? mysqli_real_escape_string($conn, trim($_GET['slug'])) : '';
$sub_slug = isset($_GET['sub']) ? mysqli_real_escape_string($conn, trim($_GET['sub'])) : '';
$search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$page     = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit    = 9; // Products per page
$offset   = ($page - 1) * $limit;

$category     = null;
$sub_category = null;
$cate_id      = 0;
$sub_cate_id  = 0;

// If sub-category slug is provided
if (!empty($sub_slug)) {
    $sub_q = mysqli_query($conn, "SELECT * FROM sub_categories WHERE slug_url = '$sub_slug' AND status = 1 LIMIT 1");
    if (mysqli_num_rows($sub_q) > 0) {
        $sub_category = mysqli_fetch_assoc($sub_q);
        $sub_cate_id  = $sub_category['cate_id'];
        $cate_id      = $sub_category['parent_id'];

        // Fetch parent category info
        $cat_q = mysqli_query($conn, "SELECT * FROM categories WHERE cate_id = '$cate_id' LIMIT 1");
        if (mysqli_num_rows($cat_q) > 0) {
            $category = mysqli_fetch_assoc($cat_q);
            $slug     = $category['slug_url'];
        }
    }
}

// If category slug is provided (and category not already loaded via sub-category)
if (!empty($slug) && !$category) {
    $cat_info_query = mysqli_query($conn, "SELECT * FROM categories WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
    if (mysqli_num_rows($cat_info_query) > 0) {
        $category = mysqli_fetch_assoc($cat_info_query);
        $cate_id  = $category['cate_id'];
    }
}

// --- 3. DYNAMIC SEO & BREADCRUMB SETUP ---
if ($sub_category) {
    $pageTitle     = $sub_category['categories'];
    $meta_title    = !empty($sub_category['meta_title']) ? $sub_category['meta_title'] : $sub_category['categories'] . " - VT Export";
    $meta_desc     = !empty($sub_category['meta_desc']) ? $sub_category['meta_desc'] : '';
    $meta_key      = !empty($sub_category['meta_key']) ? $sub_category['meta_key'] : '';
    $custom_schema = !empty($sub_category['schema_markup']) ? $sub_category['schema_markup'] : '';
} elseif ($category) {
    $pageTitle     = $category['categories'];
    $meta_title    = !empty($category['meta_title']) ? $category['meta_title'] : $category['categories'] . " - VT Export";
    $meta_desc     = !empty($category['meta_desc']) ? $category['meta_desc'] : '';
    $meta_key      = !empty($category['meta_key']) ? $category['meta_key'] : '';
    $custom_schema = !empty($category['schema_markup']) ? $category['schema_markup'] : '';
} else {
    $pageTitle  = !empty($search) ? "Search: " . htmlspecialchars($search) : "Product Categories";
    $meta_title = "Explore Product Categories - VT Export";
}

include 'includes/header.php';
include 'includes/breadcrumb.php';

// Contact phone for product cards
$contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
$contact       = mysqli_fetch_assoc($contact_query);
$phone_number  = !empty($contact['phone']) ? $contact['phone'] : '+91-0000000000';

// --- 4. BUILD PRODUCT FILTER QUERY & PAGINATION ---
$where_clauses = ["status = 1"];
if ($cate_id > 0) {
    $where_clauses[] = "pro_cate = '$cate_id'";
}
if ($sub_cate_id > 0) {
    $where_clauses[] = "pro_sub_cate = '$sub_cate_id'";
}
if (!empty($search)) {
    $where_clauses[] = "(pro_name LIKE '%$search%' OR short_desc LIKE '%$search%' OR brand_name LIKE '%$search%')";
}
$where_sql = implode(" AND ", $where_clauses);

// Count total matching products for Pagination
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE $where_sql");
$count_row   = mysqli_fetch_assoc($count_query);
$total_products = (int)$count_row['total'];
$total_pages    = ceil($total_products / $limit);

// Fetch paginated products
$prod_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products WHERE $where_sql ORDER BY id DESC LIMIT $offset, $limit");

// Helper function to build pagination URLs preserving filters
function buildCategoryUrl($page_num, $slug, $sub_slug, $search)
{
    $params = [];
    if (!empty($slug)) $params['slug'] = $slug;
    if (!empty($sub_slug)) $params['sub'] = $sub_slug;
    if (!empty($search)) $params['search'] = $search;
    $params['page'] = $page_num;
    return 'category.php?' . http_build_query($params);
}
?>

<style>
    :root {
        --primary-blue: #0a2540;
        --accent-gold: #d4af37;
        --badge-color: #741b38;
        --bg-light: #f8f9fc;
    }

    .category-page-section {
        padding: 70px 0;
        background-color: var(--bg-light);
    }

    /* Sub-category Filter Pills Bar */
    .subcat-filter-box {
        background: #ffffff;
        padding: 20px 25px;
        border-radius: 10px;
        border: 1px solid #eaeaea;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .subcat-pill {
        display: inline-flex;
        align-items: center;
        padding: 9px 20px;
        border-radius: 30px;
        border: 1px solid #dce1e7;
        background: #f8f9fc;
        color: var(--primary-blue);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .subcat-pill:hover,
    .subcat-pill.active {
        background: var(--primary-blue);
        color: #ffffff;
        border-color: var(--primary-blue);
        box-shadow: 0 4px 12px rgba(10, 37, 64, 0.2);
    }

    .subcat-pill.active i {
        color: var(--accent-gold);
    }

    /* Sidebar Widgets */
    .sidebar-widget {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 6px 20px rgba(10, 37, 64, 0.04);
    }

    .widget-title {
        color: var(--primary-blue);
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f3f5;
        position: relative;
    }

    .widget-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: var(--accent-gold);
    }

    /* Sidebar Search */
    .sidebar-search-form {
        display: flex;
    }

    .sidebar-search-form input {
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 8px 0 0 8px;
        padding: 11px 15px;
        font-size: 0.95rem;
    }

    .sidebar-search-form input:focus {
        box-shadow: none;
        border-color: var(--primary-blue);
    }

    .sidebar-search-form button {
        background: var(--primary-blue);
        color: #fff;
        border: none;
        padding: 0 20px;
        border-radius: 0 8px 8px 0;
        transition: 0.3s;
    }

    .sidebar-search-form button:hover {
        background: var(--accent-gold);
        color: var(--primary-blue);
    }

    /* Sidebar Category & Sub-category Tree */
    .cat-tree-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .cat-tree-item {
        border-bottom: 1px solid #f1f3f5;
    }

    .cat-tree-item:last-child {
        border-bottom: none;
    }

    .cat-tree-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 10px;
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.96rem;
        border-radius: 6px;
        transition: 0.2s;
    }

    .cat-tree-link:hover,
    .cat-tree-link.active {
        background: rgba(10, 37, 64, 0.05);
        color: var(--accent-gold);
    }

    .subcat-tree-list {
        list-style: none;
        padding: 5px 0 10px 20px;
        margin: 0;
    }

    .subcat-tree-list li a {
        display: block;
        padding: 7px 12px;
        color: #555;
        font-size: 0.9rem;
        text-decoration: none;
        border-left: 2px solid #e2e8f0;
        transition: 0.2s;
    }

    .subcat-tree-list li a:hover,
    .subcat-tree-list li a.active {
        color: var(--primary-blue);
        font-weight: 700;
        border-left-color: var(--accent-gold);
        background: #f8f9fc;
    }

    /* Request a Quote Sidebar Box */
    .quote-widget {
        background: var(--primary-blue);
        color: #ffffff;
        border-top: 4px solid var(--accent-gold);
    }

    .quote-widget .widget-title {
        color: #ffffff;
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    .quote-widget .form-control {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #ffffff;
        font-size: 0.9rem;
        padding: 10px 14px;
    }

    .quote-widget .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .quote-widget .form-control:focus {
        background: rgba(255, 255, 255, 0.14);
        border-color: var(--accent-gold);
        box-shadow: none;
        color: #fff;
    }

    .btn-quote-submit {
        background: var(--accent-gold);
        color: var(--primary-blue);
        font-weight: 700;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }

    .btn-quote-submit:hover {
        background: #ffffff;
        color: var(--primary-blue);
    }

    /* Custom Pagination */
    .custom-pagination .page-link {
        color: var(--primary-blue);
        border: 1px solid #dee2e6;
        margin: 0 4px;
        border-radius: 6px;
        font-weight: 600;
        padding: 8px 16px;
        transition: 0.3s;
    }

    .custom-pagination .page-item.active .page-link,
    .custom-pagination .page-link:hover {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
        color: var(--accent-gold);
    }
</style>

<section class="category-page-section">
    <div class="container">

        <div class="row g-4">

            <!-- ================= LEFT COLUMN: SUB-CATEGORIES & PRODUCTS ================= -->
            <div class="col-lg-8">

                <!-- Section Heading -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.85rem;">
                            <?php echo $sub_category ? htmlspecialchars($category['categories'] ?? 'Sub-Category') : 'Our Catalog'; ?>
                        </span>
                        <h2 class="fw-bold mb-0" style="color: #0a2540; font-size: 2rem;">
                            <?php
                            if ($sub_category) {
                                echo htmlspecialchars($sub_category['categories']);
                            } elseif ($category) {
                                echo htmlspecialchars($category['categories']);
                            } else {
                                echo "All Categories & Products";
                            }
                            ?>
                        </h2>
                    </div>
                    <span class="badge bg-white text-dark border px-3 py-2 mt-2 mt-sm-0" style="font-size: 0.9rem;">
                        Showing <strong><?php echo $total_products; ?></strong> Product(s)
                    </span>
                </div>

                <!-- 🔥 SUB-CATEGORIES BAR (Shows when a Parent Category is selected) 🔥 -->
                <?php if ($cate_id > 0):
                    $subcats_q = mysqli_query($conn, "SELECT * FROM sub_categories WHERE parent_id = '$cate_id' AND status = 1 ORDER BY id ASC");
                    if (mysqli_num_rows($subcats_q) > 0):
                ?>
                        <div class="subcat-filter-box">
                            <div class="small fw-bold text-uppercase mb-2" style="color: #6c757d; letter-spacing: 1px;">
                                <i class="fas fa-layer-group me-1" style="color: #d4af37;"></i> Filter by Sub-Category:
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="category.php?slug=<?php echo urlencode($slug); ?>" class="subcat-pill <?php echo empty($sub_slug) ? 'active' : ''; ?>">
                                    <i class="fas fa-th-large me-2"></i> All <?php echo htmlspecialchars($category['categories']); ?>
                                </a>
                                <?php while ($sc = mysqli_fetch_assoc($subcats_q)): ?>
                                    <a href="category.php?slug=<?php echo urlencode($slug); ?>&sub=<?php echo urlencode($sc['slug_url']); ?>"
                                        class="subcat-pill <?php echo ($sub_slug == $sc['slug_url']) ? 'active' : ''; ?>">
                                        <i class="fas fa-angle-right me-2"></i> <?php echo htmlspecialchars($sc['categories']); ?>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        </div>
                <?php endif;
                endif; ?>

                <!-- Active Search Filter Alert -->
                <?php if (!empty($search)): ?>
                    <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4">
                        <span>Search results for: <strong>"<?php echo htmlspecialchars($search); ?>"</strong></span>
                        <a href="category.php<?php echo !empty($slug) ? '?slug=' . urlencode($slug) : ''; ?>" class="btn btn-sm btn-outline-danger">Clear Search</a>
                    </div>
                <?php endif; ?>

                <!-- Products Grid -->
                <div class="row g-4">
                    <?php
                    if (mysqli_num_rows($prod_query) > 0) {
                        while ($prod = mysqli_fetch_assoc($prod_query)) {
                            $db_img = $prod['pro_img'];
                            $p_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
                            if (!empty($db_img) && !file_exists($p_img)) {
                                $p_img = 'admin/' . $db_img;
                            }
                    ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="prod-card h-100">
                                    <div class="prod-img-wrapper">
                                        <span class="export-badge">Export Grade</span>
                                        <a href="product-details.php?slug=<?php echo urlencode($prod['slug_url']); ?>">
                                            <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($prod['pro_name']); ?>">
                                        </a>
                                    </div>

                                    <div class="prod-body">
                                        <h3 class="prod-title">
                                            <a href="product-details.php?slug=<?php echo urlencode($prod['slug_url']); ?>" style="text-decoration: none; color: inherit;">
                                                <?php echo htmlspecialchars($prod['pro_name']); ?>
                                            </a>
                                        </h3>

                                        <a href="product-details.php?slug=<?php echo urlencode($prod['slug_url']); ?>" class="prod-link">
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
                        echo '<div class="col-12 text-center py-5 bg-white rounded border">
                                <i class="fas fa-box-open mb-3" style="font-size: 3rem; color: #ddd;"></i>
                                <h4 style="color: #0a2540;">No Products Found</h4>
                                <p class="text-muted">We are updating our inventory for this selection. Please check other categories or contact us directly.</p>
                                <a href="category.php" class="btn btn-inquire mt-2 d-inline-flex px-4" style="width: auto;">View All Products</a>
                              </div>';
                    }
                    ?>
                </div>

                <!-- 🔥 PAGINATION SECTION 🔥 -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Category Pagination" class="mt-5">
                        <ul class="pagination justify-content-center custom-pagination">
                            <!-- Previous Page -->
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($page > 1) ? buildCategoryUrl($page - 1, $slug, $sub_slug, $search) : '#'; ?>">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo buildCategoryUrl($i, $slug, $sub_slug, $search); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Page -->
                            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($page < $total_pages) ? buildCategoryUrl($page + 1, $slug, $sub_slug, $search) : '#'; ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>

            <!-- ================= RIGHT COLUMN: SIDEBAR (SEARCH, CATEGORIES, QUOTE) ================= -->
            <div class="col-lg-4">

                <!-- 1. WORKING SEARCH BAR WIDGET -->
                <div class="sidebar-widget">
                    <h4 class="widget-title">Search Products</h4>
                    <form action="category.php" method="GET" class="sidebar-search-form">
                        <?php if (!empty($slug)): ?>
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug); ?>">
                        <?php endif; ?>
                        <?php if (!empty($sub_slug)): ?>
                            <input type="hidden" name="sub" value="<?php echo htmlspecialchars($sub_slug); ?>">
                        <?php endif; ?>
                        <input type="text" name="search" class="form-control" placeholder="Search product name..." value="<?php echo htmlspecialchars($search); ?>" required>
                        <button type="submit" title="Search"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- 2. CATEGORIES & SUB-CATEGORIES WIDGET -->
                <div class="sidebar-widget">
                    <h4 class="widget-title">Product Categories</h4>
                    <ul class="cat-tree-list">
                        <li class="cat-tree-item">
                            <a href="category.php" class="cat-tree-link <?php echo (empty($slug) && empty($sub_slug)) ? 'active' : ''; ?>">
                                <span><i class="fas fa-border-all me-2" style="color: #d4af37;"></i> All Products</span>
                            </a>
                        </li>
                        <?php
                        $side_cats = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id ASC");
                        while ($s_cat = mysqli_fetch_assoc($side_cats)):
                            $s_cid = $s_cat['cate_id'];
                            $is_active_cat = ($cate_id == $s_cid);
                            $side_subs = mysqli_query($conn, "SELECT * FROM sub_categories WHERE parent_id = '$s_cid' AND status = 1 ORDER BY id ASC");
                            $sub_count = mysqli_num_rows($side_subs);
                        ?>
                            <li class="cat-tree-item">
                                <a href="category.php?slug=<?php echo urlencode($s_cat['slug_url']); ?>" class="cat-tree-link <?php echo $is_active_cat ? 'active' : ''; ?>">
                                    <span><i class="fas fa-folder-open me-2" style="color: #d4af37;"></i> <?php echo htmlspecialchars($s_cat['categories']); ?></span>
                                    <?php if ($sub_count > 0): ?>
                                        <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                                    <?php endif; ?>
                                </a>

                                <?php if ($sub_count > 0): ?>
                                    <ul class="subcat-tree-list">
                                        <?php while ($s_sub = mysqli_fetch_assoc($side_subs)): ?>
                                            <li>
                                                <a href="category.php?slug=<?php echo urlencode($s_cat['slug_url']); ?>&sub=<?php echo urlencode($s_sub['slug_url']); ?>"
                                                    class="<?php echo ($sub_cate_id == $s_sub['cate_id']) ? 'active' : ''; ?>">
                                                    <?php echo htmlspecialchars($s_sub['categories']); ?>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- 3. REQUEST A QUOTE WIDGET -->
                <div class="sidebar-widget quote-widget">
                    <h4 class="widget-title"><i class="fas fa-file-invoice me-2" style="color: #d4af37;"></i>Request a Quote</h4>
                    <p class="small mb-3" style="color: rgba(255,255,255,0.8);">Looking for bulk B2B pricing or custom specifications? Send us your requirement directly.</p>

                    <?php if (!empty($quote_msg)): ?>
                        <div class="alert alert-<?php echo $quote_msg_type; ?> py-2 small"><?php echo $quote_msg; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <input type="text" name="q_name" class="form-control" placeholder="Your Full Name *" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="q_email" class="form-control" placeholder="Business Email *" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="q_phone" class="form-control" placeholder="Phone / WhatsApp Number *" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="q_subject" class="form-control" value="<?php echo $sub_category ? 'Inquiry: ' . htmlspecialchars($sub_category['categories']) : ($category ? 'Inquiry: ' . htmlspecialchars($category['categories']) : ''); ?>" placeholder="Product / Category Name *" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="q_message" class="form-control" rows="3" placeholder="Specify quantity, size, or custom drawing details..." required></textarea>
                        </div>
                        <button type="submit" name="submit_sidebar_quote" class="btn-quote-submit">
                            Send Inquiry <i class="fas fa-paper-plane ms-1"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>