<?php
$pageTitle = "Insights & News";
require_once 'config/connect.php'; 

// --- INQUIRY FORM LOGIC (Sidebar) ---
$inquiry_msg = "";
$inquiry_msg_type = "";

if (isset($_POST['submit_blog_inquiry'])) {
    $q_name    = mysqli_real_escape_string($conn, trim($_POST['q_name']));
    $q_email   = mysqli_real_escape_string($conn, trim($_POST['q_email']));
    $q_phone   = mysqli_real_escape_string($conn, trim($_POST['q_phone']));
    // Setting a fixed subject to identify it came from the Blog page
    $q_subject = "Inquiry from Blog Page"; 
    $q_message = mysqli_real_escape_string($conn, trim($_POST['q_message']));

    if (!empty($q_name) && !empty($q_email) && !empty($q_phone)) {
        $ins_quote = "INSERT INTO inquiries (name, email, phone, subject, message, status) 
                      VALUES ('$q_name', '$q_email', '$q_phone', '$q_subject', '$q_message', 0)";
        
        if (mysqli_query($conn, $ins_quote)) {
            $inquiry_msg = "Thank you! Your inquiry has been submitted.";
            $inquiry_msg_type = "success";
        } else {
            $inquiry_msg = "Something went wrong. Please try again.";
            $inquiry_msg_type = "danger";
        }
    } else {
        $inquiry_msg = "Please fill in all required fields.";
        $inquiry_msg_type = "warning";
    }
}
// ------------------------------------

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

// Fetch Blogs
$blog_query = mysqli_query($conn, "SELECT * FROM blogs $where_sql ORDER BY blog_id DESC LIMIT $offset, $limit");
?>

<style>
/* Styling for the sidebar Inquiry Form */
.inquiry-widget {
    background: #0a2540;
    /* Navy Blue Theme */
    border-top: 4px solid #d4af37;
    /* Gold Accent */
    padding: 30px 25px;
    border-radius: 10px;
    margin-top: 30px;
    /* Space between search and form */
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(10, 37, 64, 0.1);
}

.inquiry-widget .sidebar-title {
    color: #ffffff;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.inquiry-widget p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.inquiry-widget .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 10px 15px;
    font-size: 0.9rem;
}

.inquiry-widget .form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.inquiry-widget .form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #d4af37;
    box-shadow: none;
}

.btn-inquiry-submit {
    background: #d4af37;
    color: #0a2540;
    font-weight: 700;
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: 0.3s;
}

.btn-inquiry-submit:hover {
    background: #ffffff;
    color: #0a2540;
}
</style>

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
                                <a href="blog/<?php echo $blog['slug']; ?>">
                                    <img src="<?php echo $b_img; ?>"
                                        alt="<?php echo htmlspecialchars($blog['title']); ?>"
                                        onerror="this.src='assets/images/default-blog.jpg';">
                                </a>
                                <div class="blog-date-badge">
                                    <span><?php echo date('d', $date); ?></span>
                                    <small><?php echo date('M, Y', $date); ?></small>
                                </div>
                            </div>
                            <div class="blog-content">
                                <h3 class="blog-title">
                                    <a href="blog/<?php echo $blog['slug']; ?>" style="text-decoration: none;">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </a>
                                </h3>
                                <div class="blog-desc">
                                    <?php echo strip_tags(substr($blog['description'], 0, 100)) . '...'; ?>
                                </div>
                                <a href="blog/<?php echo $blog['slug']; ?>" class="btn-read-more">Read Full Article <i
                                        class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; } else { echo "<div class='col-12'><p class='text-muted'>No articles found.</p></div>"; } ?>
                </div>

                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <ul class="premium-pagination mt-5">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a
                            href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search='.$search : ''; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">

                <!-- 1. Search Widget -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">Search</h3>
                    <form action="blogs.php" method="GET">
                        <div class="d-flex">
                            <input type="text" name="search" class="search-input form-control me-2"
                                placeholder="Search insights..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="search-btn btn text-white px-3" style="background: #0a2540;"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <!-- 2. NEW: Inquiry / Request Quote Widget -->
                <div class="inquiry-widget">
                    <h3 class="sidebar-title"><i class="fas fa-envelope-open-text me-2" style="color: #d4af37;"></i>
                        Send an Inquiry</h3>
                    <p>Have questions about our products or export processes? Drop us a message.</p>

                    <!-- Alert message if form is submitted -->
                    <?php if (!empty($inquiry_msg)): ?>
                    <div class="alert alert-<?php echo $inquiry_msg_type; ?> py-2 small fw-bold">
                        <?php echo $inquiry_msg; ?>
                    </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <input type="text" name="q_name" class="form-control" placeholder="Full Name *" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="q_email" class="form-control" placeholder="Email Address *"
                                required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="q_phone" class="form-control" placeholder="Phone Number *"
                                required>
                        </div>
                        <div class="mb-4">
                            <textarea name="q_message" class="form-control" rows="3"
                                placeholder="How can we help you?"></textarea>
                        </div>
                        <button type="submit" name="submit_blog_inquiry" class="btn-inquiry-submit">
                            Submit Inquiry <i class="fas fa-paper-plane ms-2"></i>
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