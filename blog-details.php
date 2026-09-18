<?php
require_once 'config/connect.php'; 

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if(empty($slug)){ header("Location: blogs.php"); exit; }

$blog_query = mysqli_query($conn, "SELECT * FROM blogs WHERE slug = '$slug' AND status = 1");
if(mysqli_num_rows($blog_query) == 0){ header("Location: 404.php"); exit; }
$blog = mysqli_fetch_assoc($blog_query);

// Image Path
$db_img = $blog['image'];
$main_img = !empty($db_img) ? 'admin/assets/img/uploads/blogs/' . $db_img : 'assets/images/default-blog.jpg';
$page_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$meta_title = !empty($blog['meta_title']) ? $blog['meta_title'] : $blog['title'] . ' - VT Export';
$meta_desc = !empty($blog['meta_desc']) ? $blog['meta_desc'] : strip_tags(substr($blog['description'], 0, 160));
$meta_key = !empty($blog['meta_key']) ? $blog['meta_key'] : 'Export, Spices, ' . $blog['title'];
$pageTitle = $blog['title'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($meta_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_key); ?>">
    
    <!-- JSON-LD Schema (Dynamic Article Schema) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "<?php echo htmlspecialchars($blog['title']); ?>",
      "image": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/' . $main_img; ?>",
      "author": {
        "@type": "Person",
        "name": "<?php echo htmlspecialchars($blog['author'] ?? 'Admin'); ?>"
      },
      "publisher": {
        "@type": "Organization",
        "name": "VT Export",
        "logo": {
          "@type": "ImageObject",
          "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png'; ?>"
        }
      },
      "datePublished": "<?php echo date('c', strtotime($blog['created_at'])); ?>",
      "description": "<?php echo htmlspecialchars($meta_desc); ?>"
    }
    </script>
</head>
<body>

<?php 

include 'includes/header.php'; 
include 'includes/breadcrumb.php'; 
?>

<section class="blog-detail-section">
    <div class="container">
        <div class="row g-5">
            
            <!-- LEFT CONTENT -->
            <div class="col-lg-8">
                <img src="<?php echo $main_img; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="blog-main-img">
                
                <div class="blog-meta-info">
                    <span><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                    <span><i class="far fa-user"></i> By <?php echo htmlspecialchars($blog['author'] ?? 'Admin'); ?></span>
                    <span><i class="far fa-folder-open"></i> News & Insights</span>
                </div>
                
                <!-- H1 nahi use kiya SEO conflict rokne ke liye, H2 rakha hai aur styling badha di hai -->
                <h2 class="blog-main-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
                
                <div class="blog-body-content">
                    <?php echo htmlspecialchars_decode($blog['description']); // Show HTML content properly[cite: 2] ?>
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

            <!-- RIGHT SIDEBAR (Matching the Screenshot Layout)[cite: 14] -->
            <div class="col-lg-4">
                
                <!-- Search Widget -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Search</h3>
                    <form action="blogs.php" method="GET" class="search-flex">
                        <input type="text" name="search" placeholder="Search insights..." required>
                        <button type="submit" style="background: #741b38;"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Categories Widget -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Categories</h3>
                    <ul class="cat-list-widget">
                        <li><span>Export Trends</span> <span>(12)</span></li>
                        <li><span>Farming Practices</span> <span>(08)</span></li>
                        <li><span>Health Benefits</span> <span>(15)</span></li>
                        <li><span>Quality & Testing</span> <span>(05)</span></li>
                        <li><span>Company News</span> <span>(03)</span></li>
                    </ul>
                </div>

                <!-- Recent Posts Widget[cite: 2] -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Recent Posts</h3>
                    <?php 
                    $recent_query = mysqli_query($conn, "SELECT title, slug, image, created_at FROM blogs WHERE status = 1 AND blog_id != '{$blog['blog_id']}' ORDER BY blog_id DESC LIMIT 3");
                    while($rec = mysqli_fetch_assoc($recent_query)):
                        $r_img = !empty($rec['image']) ? 'admin/assets/img/uploads/blogs/' . $rec['image'] : 'assets/images/default-blog.jpg';
                    ?>
                    <div class="recent-post-item">
                        <img src="<?php echo $r_img; ?>" alt="Recent" class="recent-post-img">
                        <div>
                            <a href="blog-details.php?slug=<?php echo $rec['slug']; ?>" class="recent-post-title"><?php echo htmlspecialchars($rec['title']); ?></a>
                            <span class="recent-post-date"><?php echo date('M d, Y', strtotime($rec['created_at'])); ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Call to Action Box (Modified Green to Navy Blue for premium consistency) -->
                <div class="cta-widget">
                    <i class="fas fa-box-open"></i>
                    <h3>Looking for Bulk Supply?</h3>
                    <p>Get a free quotation for your international export requirements today.</p>
                    <a href="contact.php" class="cta-btn">Request Quote</a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php 
include 'includes/inquiry_section.php';
include 'includes/footer.php'; 
?>
</body>
</html>