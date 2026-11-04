<?php
include('config/connect.php');

// Safely capture slug from URL
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, trim($_GET['slug'])) : '';

if (empty($slug)) {
    header("Location: 404.php");
    exit;
}

// Fetch Custom Page Details
$pageQuery = mysqli_query($conn, "SELECT * FROM custom_pages WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
if (!$pageQuery || mysqli_num_rows($pageQuery) == 0) {
    header("HTTP/1.0 404 Not Found"); 
    echo "<script>window.location.href='404.php';</script>";
    exit;
}
$pageData = mysqli_fetch_assoc($pageQuery);

// SEO Variable Setup
$pageTitle = !empty($pageData['meta_title']) ? $pageData['meta_title'] : $pageData['page_title'];
$meta_desc = !empty($pageData['meta_desc']) ? $pageData['meta_desc'] : strip_tags(substr($pageData['content'], 0, 160));
$meta_key = $pageData['meta_key'] ?? '';
$breadcrumb_name = $pageData['breadcrumb_title'] ?? $pageData['page_title']; 

// Smart Schema Logic
$raw_schema = trim($pageData['schema_markup'] ?? '');
$final_schema = "";
if (!empty($raw_schema)) {
    if (stripos($raw_schema, '<script') === false) {
        $final_schema = "<script type=\"application/ld+json\">\n" . $raw_schema . "\n</script>";
    } else {
        $final_schema = $raw_schema;
    }
}

// Phone Number fetch for Call-to-action
$contactQuery = mysqli_query($conn, "SELECT phone FROM contacts LIMIT 1");
$contactInfo = mysqli_fetch_assoc($contactQuery);
$sitePhone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-0000000000';

include 'includes/header.php';
// Custom Breadcrumb Header using pageTitle
$pageTitle = $pageData['page_title'] ?? 'VT Export Details';
include 'includes/breadcrumb.php';
?>

<style>
/* ================= PREMIUM THEME (VT EXPORT) ================= */
:root {
    --primary-blue: #0a2540;
    --accent-gold: #d4af37;
    --text-muted: #555555;
    --bg-light: #f8f9fc;
}

.custom-page-section {
    background-color: var(--bg-light);
    padding: 80px 0;
}

/* Left Column Content Styling */
.page-content-wrapper {
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(10, 37, 64, 0.05);
    border: 1px solid #eaeaea;
}

.page-main-title {
    color: var(--primary-blue);
    font-size: clamp(1.8rem, 3vw, 2.5rem);
    font-weight: 800;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 15px;
}

.page-main-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--accent-gold);
}

.page-content-body {
    line-height: 1.8;
    color: var(--text-muted);
    font-size: 1.05rem;
}

.page-content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 25px 0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.page-content-body h2,
.page-content-body h3 {
    color: var(--primary-blue);
    font-weight: 700;
    margin-top: 35px;
    margin-bottom: 15px;
}

.page-content-body ul {
    padding-left: 20px;
    margin-bottom: 20px;
}

.page-content-body ul li {
    margin-bottom: 10px;
    position: relative;
}

/* Social Share Bar */
.social-share {
    margin-top: 50px;
    padding-top: 25px;
    border-top: 1px solid #eaeaea;
}

.social-share span {
    color: var(--primary-blue);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
}

.social-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 1px solid #ddd;
    color: var(--primary-blue);
    transition: all 0.3s ease;
    text-decoration: none;
    margin-right: 10px;
}

.social-btn:hover {
    background: var(--accent-gold);
    color: #fff;
    border-color: var(--accent-gold);
    transform: translateY(-3px);
}

/* Right Sidebar Styling */
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

/* Search Widget */
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

/* Category List Widget */
.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    border-bottom: 1px solid #f1f3f5;
}

.category-list li:last-child {
    border-bottom: none;
}

.category-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 10px;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: 0.2s;
}

.category-link:hover,
.category-link.active-cat {
    color: var(--accent-gold);
    background: #f8f9fc;
    padding-left: 15px;
    border-radius: 6px;
}

/* Recent Posts Widget */
.recent-post-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f3f5;
}

.recent-post-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.recent-post-item img {
    width: 65px;
    height: 65px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 15px;
}

.recent-post-info h4 {
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 5px;
    line-height: 1.4;
}

.recent-post-info h4 a {
    color: var(--primary-blue);
    text-decoration: none;
    transition: 0.3s;
}

.recent-post-info h4 a:hover {
    color: var(--accent-gold);
}

.recent-post-info span {
    font-size: 0.8rem;
    color: #888;
}

/* Quote Banner Widget */
.quote-banner {
    background: linear-gradient(135deg, var(--primary-blue) 0%, #153c66 100%);
    color: #ffffff;
    text-align: center;
    padding: 35px 25px;
    border-radius: 10px;
    border-top: 4px solid var(--accent-gold);
}

.quote-banner i {
    font-size: 2.5rem;
    color: var(--accent-gold);
    margin-bottom: 15px;
}

.quote-banner h4 {
    font-weight: 800;
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.quote-banner p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 20px;
}

.btn-banner-quote {
    display: inline-block;
    background: var(--accent-gold);
    color: var(--primary-blue);
    width: 100%;
    padding: 12px;
    border-radius: 30px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    letter-spacing: 0.5px;
    transition: 0.3s;
    margin-bottom: 15px;
}

.btn-banner-quote:hover {
    background: #fff;
    color: var(--primary-blue);
}
</style>

<!-- Main Page Content -->
<section class="custom-page-section">
    <div class="container">
        <div class="row g-5">

            <!-- LEFT COLUMN: Content Area -->
            <div class="col-lg-8">
                <div class="page-content-wrapper">
                    <h2 class="page-main-title"><?= htmlspecialchars($pageData['page_title']); ?></h2>

                    <div class="page-content-body">
                        <!-- htmlspecialchars_decode allows CKEditor HTML to render properly -->
                        <?= htmlspecialchars_decode($pageData['content']); ?>
                    </div>

                    <!-- Social Share Links -->
                    <div class="social-share d-flex align-items-center">
                        <span class="me-3">Share This Page:</span>
                        <?php $current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url) ?>"
                            target="_blank" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($current_url) ?>" target="_blank"
                            class="social-btn"><i class="fa-brands fa-twitter"></i></a>
                        <a href="https://wa.me/?text=<?= urlencode($current_url) ?>" target="_blank"
                            class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($current_url) ?>"
                            target="_blank" class="social-btn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">

                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Search Content</h5>
                        <form action="products.php" method="GET" class="sidebar-search-form">
                            <input type="text" name="search" class="form-control" placeholder="Search..." required>
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <!-- Dynamic Product Categories Widget -->
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Product Lines</h5>
                        <ul class="category-list">
                            <?php
                            $sideCatQuery = mysqli_query($conn, "SELECT categories, slug_url FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 6");
                            while($sideCat = mysqli_fetch_assoc($sideCatQuery)):
                            ?>
                            <li>
                                <a href="category/<?= urlencode($sideCat['slug_url']) ?>" class="category-link">
                                    <span><i class="fa-solid fa-folder-open me-2" style="color:#d4af37;"></i>
                                        <?= htmlspecialchars($sideCat['categories']) ?></span>
                                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem; color:#ccc;"></i>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <!-- Recent Blogs Widget -->
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Latest Updates</h5>
                        <?php
                        $recentQuery = mysqli_query($conn, "SELECT title, slug, image, created_at FROM blogs WHERE status = 1 ORDER BY created_at DESC LIMIT 3");
                        if (mysqli_num_rows($recentQuery) > 0) {
                            while ($recentBlog = mysqli_fetch_assoc($recentQuery)):
                                $r_date = date('M d, Y', strtotime($recentBlog['created_at']));
                                $r_img = !empty($recentBlog['image']) ? 'admin/assets/img/uploads/blogs/' . $recentBlog['image'] : 'assets/images/default-blog.jpg';
                        ?>
                        <div class="recent-post-item">
                            <img src="<?= $r_img ?>" alt="<?= htmlspecialchars($recentBlog['title']); ?>"
                                onerror="this.src='assets/images/default-blog.jpg';">
                            <div class="recent-post-info">
                                <h4><a
                                        href="blog/<?= urlencode($recentBlog['slug']); ?>"><?= htmlspecialchars($recentBlog['title']); ?></a>
                                </h4>
                                <span><i class="far fa-calendar-alt me-1"></i> <?= $r_date; ?></span>
                            </div>
                        </div>
                        <?php
                            endwhile;
                        } else {
                            echo "<p class='text-muted small'>No recent updates available.</p>";
                        }
                        ?>
                    </div>

                    <!-- Request Quote Banner -->
                    <div class="quote-banner">
                        <i class="fa-solid fa-headset"></i>
                        <h4>Need Consultation?</h4>
                        <p>Reach out to our B2B experts today for customized export solutions and bulk pricing.</p>
                        <a href="contact.php" class="btn-banner-quote">Request a Quote</a>
                        <a href="tel:<?= htmlspecialchars($sitePhone) ?>"
                            style="color: var(--accent-gold); text-decoration: none; font-weight: 600;">
                            <i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($sitePhone) ?>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>