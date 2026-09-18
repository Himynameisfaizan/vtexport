<?php
require_once 'config/connect.php'; 

// 1. Fetch Category by Slug
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if(empty($slug)){
    header("Location: products.php");
    exit;
}

$cat_info_query = mysqli_query($conn, "SELECT * FROM categories WHERE slug_url = '$slug' AND status = 1");
if(mysqli_num_rows($cat_info_query) == 0){
    header("Location: products.php");
    exit;
}
$category = mysqli_fetch_assoc($cat_info_query);
$cate_id = $category['cate_id'];

// Breadcrumb & SEO Titles
$pageTitle = $category['categories'];
$meta_title = !empty($category['meta_title']) ? $category['meta_title'] : $category['categories'] . " - VT Export";

include 'includes/header.php';
include 'includes/breadcrumb.php';

$contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
$phone_number = !empty($contact['phone']) ? $contact['phone'] : '+91-0000000000';

$prod_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products WHERE pro_cate = '$cate_id' AND status=1 ORDER BY id DESC");
?>

<section class="category-page-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Category</span>
            <h2 class="fw-bold" style="color: #0a2540; font-size: 2.5rem;"><?php echo htmlspecialchars($category['categories']); ?></h2>
            <div style="width: 50px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>

        <div class="row g-4">
            <?php 
            if(mysqli_num_rows($prod_query) > 0) {
                while($prod = mysqli_fetch_assoc($prod_query)) {
                    $db_img = $prod['pro_img'];
                    $p_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
                    if(!empty($db_img) && !file_exists($p_img)) { $p_img = 'admin/' . $db_img; }
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="prod-card">
                    <div class="prod-img-wrapper">
                        <span class="export-badge">Export Grade</span>
                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" >
                        <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($prod['pro_name']); ?>">
</a>
                    </div>
                    
                    <div class="prod-body">
                        <h3 class="prod-title">
                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" style="text-decoration: none;" >    
                        <?php echo htmlspecialchars($prod['pro_name']); ?>
                        </a>
                    </h3>
                        
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
                        <h4 style="color: #0a2540;">No Products in this Category</h4>
                        <p class="text-muted">We are updating our inventory. Please check back later.</p>
                        <a href="products.php" class="btn btn-inquire mt-3 d-inline-flex px-4" style="width: auto;">View All Products</a>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php 
include 'includes/footer.php'; 
?>