<?php
require_once 'config/connect.php';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if (empty($slug)) {
    header("Location: blogs");
    exit;
}

$blog_query = mysqli_query($conn, "SELECT * FROM blogs WHERE slug = '$slug' AND status = 1");
if (mysqli_num_rows($blog_query) == 0) {
    header("Location: 404.php");
    exit;
}
$blog = mysqli_fetch_assoc($blog_query);

// Image Path
$db_img = $blog['image'];
$main_img = !empty($db_img) ? 'admin/assets/img/uploads/blogs/' . $db_img : 'assets/images/default-blog.jpg';
$page_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$meta_title = !empty($blog['meta_title']) ? $blog['meta_title'] : $blog['title'] . ' - VT Export';
$meta_desc = !empty($blog['meta_desc']) ? $blog['meta_desc'] : strip_tags(substr($blog['description'], 0, 160));
$meta_key = !empty($blog['meta_key']) ? $blog['meta_key'] : 'Export, Spices, ' . $blog['title'];
$pageTitle = $blog['title'];

// Build Article Schema for header.php
$article_schema = [
    "@context" => "https://schema.org",
    "@type" => "Article",
    "headline" => $blog['title'],
    "image" => "https://" . $_SERVER['HTTP_HOST'] . "/" . $main_img,
    "author" => [
        "@type" => "Person",
        "name" => $blog['author'] ?? 'Admin'
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => "VT Export"
    ],
    "datePublished" => date('c', strtotime($blog['created_at'])),
    "description" => $meta_desc
];
$custom_schema = json_encode($article_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

// Directly include header.php (No duplicate <head> or localhost <base> here!)
include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<style>
:root {
    --primary-blue: #0a2540;
    --accent-gold: #d4af37;
    --text-muted: #555555;
    --bg-light: #f8f9fc;
}
.blog-detail-section {
    padding: 80px 0;
    background: var(--bg-light);
}
.blog-main-img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}
.blog-meta-info {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 15px;
}
.blog-meta-info span {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-muted);
}
.blog-meta-info span i {
    color: var(--accent-gold);
    margin-right: 5px;
}
.blog-main-title {
    color: var(--primary-blue);
    font-size: clamp(2rem, 3.5vw, 2.5rem);
    font-weight: 800;
    margin-bottom: 25px;
    line-height: 1.3;
}
.blog-body-content {
    font-size: 1.05rem;
    color: #444;
    line-height: 1.8;
    margin-bottom: 50px;
}
.blog-body-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 20px 0;
}
.blog-body-content h3, .blog-body-content h4 {
    color: var(--primary-blue);
    font-weight: 700;
    margin-top: 30px;
}
.share-area {
    background: #fff;
    border: 1px solid #eaeaea;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
}
.share-area strong {
    color: var(--primary-blue);
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 1px;
}
.social-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
}
.sc-fb { background: #3b5998; }
.sc-tw { background: #1da1f2; }
.sc-in { background: #0077b5; }
.sc-wa { background: #25d366; }
.social-circle:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    color: #fff;
}
.sidebar-widget {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    border: 1px solid #eaeaea;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
}
.sidebar-title {
    color: var(--primary-blue);
    font-size: 1.25rem;
    font-weight: 800;
    border-bottom: 2px solid #f1f3f5;
    padding-bottom: 12px;
    margin-bottom: 25px;
    position: relative;
}
.sidebar-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 50px;
    height: 2px;
    background: var(--accent-gold);
}
.search-flex { display: flex; }
.search-flex input {
    width: 100%;
    border: 2px solid #eaeaea;
    padding: 10px 15px;
    border-radius: 6px 0 0 6px;
    outline: none;
}
.search-flex button {
    background: var(--primary-blue);
    border: none;
    padding: 0 20px;
    color: #fff;
    border-radius: 0 6px 6px 0;
    transition: 0.3s;
}
.search-flex button:hover {
    background: var(--accent-gold);
    color: var(--primary-blue);
}
.cat-list-widget { list-style: none; padding: 0; margin: 0; }
.cat-list-widget li { border-bottom: 1px solid #f1f3f5; }
.cat-list-widget li:last-child { border-bottom: none; }
.cat-list-widget a {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}
.cat-list-widget a:hover {
    color: var(--accent-gold);
    padding-left: 10px;
}
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
.recent-post-img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 15px;
}
.recent-post-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--primary-blue);
    text-decoration: none;
    display: block;
    margin-bottom: 5px;
    line-height: 1.3;
}
.recent-post-title:hover { color: var(--accent-gold); }
.recent-post-date { font-size: 0.8rem; color: #888; }
.cta-widget {
    background: linear-gradient(135deg, var(--primary-blue) 0%, #153c66 100%);
    color: #fff;
    text-align: center;
    padding: 40px 25px;
    border-radius: 10px;
    border-top: 4px solid var(--accent-gold);
}
.cta-widget i {
    font-size: 3rem;
    color: var(--accent-gold);
    margin-bottom: 20px;
}
.cta-widget h3 {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 10px;
}
.cta-widget p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 25px;
    line-height: 1.6;
}
.cta-btn {
    display: inline-block;
    background: var(--accent-gold);
    color: var(--primary-blue);
    padding: 12px 30px;
    border-radius: 30px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    transition: 0.3s;
    width: 100%;
}
.cta-btn:hover {
    background: #fff;
    color: var(--primary-blue);
}
</style>

<section class="blog-detail-section">
    <div class="container">
        <div class="row g-5">

            <!-- LEFT CONTENT -->
            <div class="col-lg-8">
                <img src="<?php echo $main_img; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-main-img">

                <div class="blog-meta-info">
                    <span><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                    <span><i class="far fa-user"></i> By <?php echo htmlspecialchars($blog['author'] ?? 'Admin'); ?></span>
                </div>

                <h2 class="blog-main-title"><?php echo htmlspecialchars($blog['title']); ?></h2>

                <div class="blog-body-content">
                    <?php echo htmlspecialchars_decode($blog['description']); ?>
                </div>

                <!-- Share Article Area -->
                <div class="share-area">
                    <strong>Share this article:</strong>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($page_url); ?>" target="_blank" class="social-circle sc-fb"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($page_url); ?>&text=<?php echo urlencode($blog['title']); ?>" target="_blank" class="social-circle sc-tw"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($page_url); ?>" target="_blank" class="social-circle sc-in"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($blog['title'] . ' ' . $page_url); ?>" target="_blank" class="social-circle sc-wa"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-lg-4">

                <!-- Search Widget -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Search</h3>
                    <form action="blogs" method="GET" class="search-flex">
                        <input type="text" name="search" placeholder="Search insights..." required>
                        <button type="submit" title="Search"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Dynamic Categories Linked to Products -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Product Categories</h3>
                    <ul class="cat-list-widget">
                        <?php
                        $cat_query = mysqli_query($conn, "SELECT cate_id, categories, slug_url FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 6");
                        if (mysqli_num_rows($cat_query) > 0) {
                            while ($cat = mysqli_fetch_assoc($cat_query)) {
                                $c_id = $cat['cate_id'];
                                $count_query = mysqli_query($conn, "SELECT COUNT(id) as total FROM products WHERE pro_cate = '$c_id' AND status = 1");
                                $p_count = mysqli_fetch_assoc($count_query)['total'];
                        ?>
                        <li>
                            <a href="category/<?php echo urlencode($cat['slug_url']); ?>">
                                <span><?php echo htmlspecialchars($cat['categories']); ?></span>
                                <span>(<?php echo $p_count; ?>)</span>
                            </a>
                        </li>
                        <?php
                            }
                        } else {
                            echo "<li><span class='text-muted'>No categories found.</span></li>";
                        }
                        ?>
                    </ul>
                </div>

                <!-- Recent Posts Widget -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Recent Posts</h3>
                    <?php
                    $recent_query = mysqli_query($conn, "SELECT title, slug, image, created_at FROM blogs WHERE status = 1 AND blog_id != '{$blog['blog_id']}' ORDER BY blog_id DESC LIMIT 3");
                    if (mysqli_num_rows($recent_query) > 0) {
                        while ($rec = mysqli_fetch_assoc($recent_query)):
                            $r_img = !empty($rec['image']) ? 'admin/assets/img/uploads/blogs/' . $rec['image'] : 'assets/images/default-blog.jpg';
                    ?>
                    <div class="recent-post-item">
                        <img src="<?php echo $r_img; ?>" alt="Recent" class="recent-post-img" onerror="this.src='assets/images/default-blog.jpg';">
                        <div>
                            <a href="blog/<?php echo $rec['slug']; ?>" class="recent-post-title"><?php echo htmlspecialchars($rec['title']); ?></a>
                            <span class="recent-post-date"><?php echo date('M d, Y', strtotime($rec['created_at'])); ?></span>
                        </div>
                    </div>
                    <?php
                        endwhile;
                    } else {
                        echo "<p class='text-muted small mb-0'>No other posts available.</p>";
                    }
                    ?>
                </div>

                <!-- Call to Action Box -->
                <div class="cta-widget">
                    <i class="fas fa-box-open"></i>
                    <h3>Looking for Bulk Supply?</h3>
                    <p>Get a free quotation for your international export requirements today.</p>
                    <a href="contact" class="cta-btn">Request Quote</a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php
include 'includes/inquiry_section.php';
include 'includes/footer.php';
?>