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

// --- 2. GET URL PARAMETERS ---
$slug     = isset($_GET['slug']) ? mysqli_real_escape_string($conn, trim($_GET['slug'])) : '';
$sub_slug = isset($_GET['sub']) ? mysqli_real_escape_string($conn, trim($_GET['sub'])) : '';
$search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$page     = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit    = 9;
$offset   = ($page - 1) * $limit;

$category     = null;
$sub_category = null;
$cate_id      = 0;
$sub_cate_id  = 0;

if (!empty($sub_slug)) {
    $sub_q = mysqli_query($conn, "SELECT * FROM sub_categories WHERE slug_url = '$sub_slug' AND status = 1 LIMIT 1");
    if (mysqli_num_rows($sub_q) > 0) {
        $sub_category = mysqli_fetch_assoc($sub_q);
        $sub_cate_id  = $sub_category['cate_id'];
        $cate_id      = $sub_category['parent_id'];

        $cat_q = mysqli_query($conn, "SELECT * FROM categories WHERE cate_id = '$cate_id' LIMIT 1");
        if (mysqli_num_rows($cat_q) > 0) {
            $category = mysqli_fetch_assoc($cat_q);
            $slug     = $category['slug_url'];
        }
    }
}

if (!empty($slug) && !$category) {
    $cat_info_query = mysqli_query($conn, "SELECT * FROM categories WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
    if (mysqli_num_rows($cat_info_query) > 0) {
        $category = mysqli_fetch_assoc($cat_info_query);
        $cate_id  = $category['cate_id'];
    }
}

// --- 3. DYNAMIC SEO SETUP ---
if ($sub_category) {
    $pageTitle     = $sub_category['categories'];
    $meta_title    = !empty($sub_category['meta_title']) ? $sub_category['meta_title'] : $sub_category['categories'] . " - VT Export";
} elseif ($category) {
    $pageTitle     = $category['categories'];
    $meta_title    = !empty($category['meta_title']) ? $category['meta_title'] : $category['categories'] . " - VT Export";
} else {
    $pageTitle  = !empty($search) ? "Search: " . htmlspecialchars($search) : "Product Categories";
    $meta_title = "Explore Product Categories - VT Export";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($meta_title); ?></title>

    <!-- 🔥 FIX: BASE TAG FOR CATEGORY URL REWRITES 🔥 -->
    <base href="http://localhost/projects/vtexport/">
</head>

<body>

    <?php
include 'includes/header.php';
include 'includes/breadcrumb.php';

$contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
$contact       = mysqli_fetch_assoc($contact_query);
$phone_number  = !empty($contact['phone']) ? $contact['phone'] : '+91-0000000000';

// --- 4. BUILD PRODUCT FILTER QUERY ---
$where_clauses = ["status = 1"];
if ($cate_id > 0) { $where_clauses[] = "pro_cate = '$cate_id'"; }
if ($sub_cate_id > 0) { $where_clauses[] = "pro_sub_cate = '$sub_cate_id'"; }
if (!empty($search)) { $where_clauses[] = "(pro_name LIKE '%$search%' OR short_desc LIKE '%$search%' OR brand_name LIKE '%$search%')"; }
$where_sql = implode(" AND ", $where_clauses);

$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE $where_sql");
$total_products = (int)mysqli_fetch_assoc($count_query)['total'];
$total_pages    = ceil($total_products / $limit);

$prod_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products WHERE $where_sql ORDER BY id DESC LIMIT $offset, $limit");

// Pagination Helper URL Rewrite format ke liye
function buildCategoryUrl($page_num, $slug, $sub_slug, $search) {
    if (!empty($search)) {
        return "category.php?search=" . urlencode($search) . "&page=" . $page_num;
    }
    if (!empty($sub_slug) && !empty($slug)) {
        return "category/" . urlencode($slug) . "/" . urlencode($sub_slug) . "?page=" . $page_num;
    }
    if (!empty($slug)) {
        return "category/" . urlencode($slug) . "?page=" . $page_num;
    }
    return "?page=" . $page_num;
}
?>

    <style>
    .category-page-section {
        padding: 70px 0;
        background: #f8f9fc;
    }

    .subcat-pill {
        display: inline-block;
        padding: 8px 18px;
        border: 1px solid #ddd;
        background: #fff;
        color: #0a2540;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        margin-right: 8px;
        margin-bottom: 8px;
        transition: 0.3s;
    }

    .subcat-pill:hover,
    .subcat-pill.active {
        background: #0a2540;
        color: #fff;
        border-color: #0a2540;
    }

    .subcat-pill i {
        color: #d4af37;
        margin-right: 5px;
    }

    .sidebar-widget {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .widget-title {
        color: #0a2540;
        font-weight: 700;
        border-bottom: 2px solid #f1f3f5;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .cat-tree-link {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        color: #0a2540;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px solid #f1f3f5;
        transition: 0.3s;
    }

    .cat-tree-link:hover,
    .cat-tree-link.active {
        color: #d4af37;
        padding-left: 10px;
    }

    .subcat-tree-list {
        list-style: none;
        padding-left: 15px;
        margin-top: 10px;
    }

    .subcat-tree-list a {
        display: block;
        color: #555;
        text-decoration: none;
        padding: 5px 0;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .subcat-tree-list a:hover,
    .subcat-tree-list a.active {
        color: #d4af37;
        font-weight: 600;
    }
    </style>

    <section class="category-page-section">
        <div class="container">
            <div class="row g-5">
                <!-- LEFT COLUMN -->
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <div>
                            <span class="text-uppercase" style="color: #d4af37; font-weight: 600; font-size: 0.8rem;">
                                <?php echo $sub_category ? htmlspecialchars($category['categories'] ?? 'Sub-Category') : 'Our Catalog'; ?>
                            </span>
                            <h2 class="fw-bold m-0" style="color: #0a2540;">
                                <?php echo $sub_category ? htmlspecialchars($sub_category['categories']) : ($category ? htmlspecialchars($category['categories']) : "All Products"); ?>
                            </h2>
                        </div>
                        <span class="badge bg-white text-dark border p-2">Showing <?php echo $total_products; ?>
                            Products</span>
                    </div>

                    <!-- Sub-Category Filter -->
                    <?php if ($cate_id > 0): 
                    $subcats_q = mysqli_query($conn, "SELECT * FROM sub_categories WHERE parent_id = '$cate_id' AND status = 1 ORDER BY id ASC");
                    if (mysqli_num_rows($subcats_q) > 0):
                ?>
                    <div class="bg-white p-3 rounded border mb-4">
                        <div class="fw-bold small text-muted mb-2 text-uppercase">Filter by Sub-Category:</div>
                        <a href="category/<?php echo urlencode($slug); ?>"
                            class="subcat-pill <?php echo empty($sub_slug) ? 'active' : ''; ?>"><i
                                class="fas fa-th-large"></i> All
                            <?php echo htmlspecialchars($category['categories']); ?></a>
                        <?php while ($sc = mysqli_fetch_assoc($subcats_q)): ?>
                        <!-- 🔥 Fixed URL Rewrite Link for Subcategories 🔥 -->
                        <a href="category/<?php echo urlencode($slug); ?>/<?php echo urlencode($sc['slug_url']); ?>"
                            class="subcat-pill <?php echo ($sub_slug == $sc['slug_url']) ? 'active' : ''; ?>">
                            <i class="fas fa-angle-right"></i> <?php echo htmlspecialchars($sc['categories']); ?>
                        </a>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; endif; ?>

                    <!-- Products Grid -->
                    <div class="row g-4">
                        <?php if (mysqli_num_rows($prod_query) > 0) {
                        while ($prod = mysqli_fetch_assoc($prod_query)) {
                            $p_img = !empty($prod['pro_img']) ? 'admin/assets/img/uploads/' . $prod['pro_img'] : 'assets/images/default-product.jpg';
                    ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="bg-white border rounded h-100 text-center"
                                style="box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                                <div style="height: 200px; padding: 15px; position: relative;">
                                    <span
                                        style="position:absolute; top:10px; left:10px; background:#741b38; color:#fff; padding:3px 10px; font-size:0.7rem; border-radius:20px;">Export
                                        Grade</span>
                                    <a href="product/<?php echo urlencode($prod['slug_url']); ?>">
                                        <img src="<?php echo $p_img; ?>"
                                            alt="<?php echo htmlspecialchars($prod['pro_name']); ?>"
                                            style="width: 100%; height: 100%; object-fit: contain;">
                                    </a>
                                </div>
                                <div class="p-3 border-top text-start">
                                    <a href="product/<?php echo urlencode($prod['slug_url']); ?>"
                                        style="text-decoration: none; color: #0a2540;">
                                        <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($prod['pro_name']); ?></h6>
                                    </a>
                                    <a href="product/<?php echo urlencode($prod['slug_url']); ?>"
                                        class="small text-muted text-decoration-none d-block mb-3">View Details <i
                                            class="fas fa-chevron-right ms-1 text-warning"></i></a>
                                    <a href="contact.php?product=<?php echo urlencode($prod['pro_name']); ?>"
                                        class="btn btn-sm w-100" style="background:#0a2540; color:#fff;">Inquire Now</a>
                                </div>
                            </div>
                        </div>
                        <?php } } else { echo "<div class='col-12'><p class='text-muted p-4 bg-white border rounded text-center'>No products found.</p></div>"; } ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="<?php echo buildCategoryUrl($i, $slug, $sub_slug, $search); ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>

                <!-- RIGHT COLUMN: SIDEBAR -->
                <div class="col-lg-4">
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Product Categories</h4>
                        <ul class="list-unstyled mb-0">
                            <li><a href="products.php" class="cat-tree-link"><span><i
                                            class="fas fa-boxes me-2 text-warning"></i> All Products</span></a></li>

                            <?php
                        $side_cats = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id ASC");
                        while ($s_cat = mysqli_fetch_assoc($side_cats)):
                            $s_cid = $s_cat['cate_id'];
                            $side_subs = mysqli_query($conn, "SELECT * FROM sub_categories WHERE parent_id = '$s_cid' AND status = 1 ORDER BY id ASC");
                        ?>
                            <li>
                                <!-- URL Rewrite Link -->
                                <a href="category/<?php echo urlencode($s_cat['slug_url']); ?>"
                                    class="cat-tree-link <?php echo ($cate_id == $s_cid) ? 'active' : ''; ?>">
                                    <span><i class="fas fa-folder me-2 text-warning"></i>
                                        <?php echo htmlspecialchars($s_cat['categories']); ?></span>
                                </a>

                                <?php if (mysqli_num_rows($side_subs) > 0): ?>
                                <ul class="subcat-tree-list">
                                    <?php while ($s_sub = mysqli_fetch_assoc($side_subs)): ?>
                                    <li>
                                        <!-- URL Rewrite Link for Subcategories -->
                                        <a href="category/<?php echo urlencode($s_cat['slug_url']); ?>/<?php echo urlencode($s_sub['slug_url']); ?>"
                                            class="<?php echo ($sub_cate_id == $s_sub['cate_id']) ? 'active' : ''; ?>">
                                            - <?php echo htmlspecialchars($s_sub['categories']); ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>

</html>