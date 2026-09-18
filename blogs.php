<?php
$pageTitle = "Insights & News";
require_once 'config/connect.php'; 
include 'includes/header.php';
include 'includes/breadcrumb.php';

// Pagination & Search Logic
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6; 
$offset = ($page - 1) * $limit;

$where_sql = "WHERE status = 1";
if (!empty($search)) {
    $where_sql .= " AND title LIKE '%$search%'";
}

// Get Total Records
$count_query = mysqli_query($conn, "SELECT COUNT(blog_id) as total FROM blogs $where_sql");
$total_records = mysqli_fetch_assoc($count_query)['total'];
$total_pages = ceil($total_records / $limit);

// Fetch Blogs[cite: 2]
$blog_query = mysqli_query($conn, "SELECT * FROM blogs $where_sql ORDER BY blog_id DESC LIMIT $offset, $limit");
?>

<section class="blog-listing-section">
    <div class="container">
        <div class="row g-5">
            <!-- Blog Grid -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <?php 
                    if(mysqli_num_rows($blog_query) > 0) {
                        while($blog = mysqli_fetch_assoc($blog_query)): 
                            $b_img = !empty($blog['image']) ? 'admin/assets/img/uploads/blogs/' . $blog['image'] : 'assets/images/default-blog.jpg';
                            $date = strtotime($blog['created_at']);
                    ?>
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-img-wrap">
                                <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" >
                                <img src="<?php echo $b_img; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                </a>
                                <div class="blog-date-badge">
                                    <span><?php echo date('d', $date); ?></span>
                                    <small><?php echo date('M, Y', $date); ?></small>
                                </div>
                            </div>
                            <div class="blog-content">
                                <!-- H3 for cards to avoid SEO conflict -->
                                <h3 class="blog-title">
                                <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" style="text-decoration: none;">    
                                <?php echo htmlspecialchars($blog['title']); ?></h3>
                        </a>
                                <div class="blog-desc">
                                    <?php echo strip_tags(substr($blog['description'], 0, 100)) . '...'; ?>
                                </div>
                                <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="btn-read-more">Read Full Article <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; } else { echo "<p>No articles found.</p>"; } ?>
                </div>

                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <ul class="premium-pagination">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Search</h3>
                    <form action="blogs.php" method="GET">
                        <input type="text" name="search" class="search-input" placeholder="Search insights..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="search-btn"><i class="fas fa-search me-2"></i> Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
include 'includes/footer.php'; 
?>