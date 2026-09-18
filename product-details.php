<?php
require_once 'config/connect.php'; 

// 1. Fetch Product by Slug
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if(empty($slug)){
    header("Location: products.php");
    exit;
}

$prod_query = mysqli_query($conn, "SELECT * FROM products WHERE slug_url = '$slug' AND status = 1");
if(mysqli_num_rows($prod_query) == 0){
    header("Location: 404.php"); // Ya wapas products par bhej do
    exit;
}
$product = mysqli_fetch_assoc($prod_query);
$product_id = $product['id'];
$pro_cate = $product['pro_cate'];

// Image Path Logic
$db_img = $product['pro_img'];
$main_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
if(!empty($db_img) && !file_exists($main_img)) { $main_img = 'admin/assets/img/uploads/' . $db_img; }

// 2. Fetch Contact Data for WhatsApp & Map
$contact_query = mysqli_query($conn, "SELECT wp_number, phone, email, map FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
$wa_number = preg_replace('/[^0-9]/', '', $contact['wp_number'] ?? '0000000000'); // Remove spaces for link
$map_url = !empty($contact['map']) ? $contact['map'] : '';

// 3. Handle Review Submission (If POST)
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $r_name = mysqli_real_escape_string($conn, $_POST['reviewer_name']);
    $r_email = mysqli_real_escape_string($conn, $_POST['reviewer_email']);
    $r_rating = (int)$_POST['rating'];
    $r_msg = mysqli_real_escape_string($conn, $_POST['review_message']);
    
    $insert_review = mysqli_query($conn, "INSERT INTO product_reviews (product_id, rating, review_message, reviewer_name, reviewer_email) VALUES ('$product_id', '$r_rating', '$r_msg', '$r_name', '$r_email')");
    // Reload to prevent form resubmission
    header("Location: product-details.php?slug=$slug#reviews");
    exit;
}

$reviews_query = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = '$product_id' ORDER BY review_id DESC");
$total_reviews = mysqli_num_rows($reviews_query);
$avg_rating = 5;
if($total_reviews > 0) {
    $rating_sum_query = mysqli_query($conn, "SELECT SUM(rating) as total_rating FROM product_reviews WHERE product_id = '$product_id'");
    $rating_sum = mysqli_fetch_assoc($rating_sum_query)['total_rating'];
    $avg_rating = round($rating_sum / $total_reviews, 1);
}

$meta_title = !empty($product['meta_title']) ? $product['meta_title'] : $product['pro_name'] . ' - VT Export';
$meta_desc = !empty($product['meta_desc']) ? $product['meta_desc'] : strip_tags($product['short_desc']);
$meta_key = !empty($product['meta_key']) ? $product['meta_key'] : $product['pro_name'].', export, premium';
$pageTitle = $product['pro_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($meta_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_key); ?>">
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?php echo htmlspecialchars($product['pro_name']); ?>",
      "image": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/' . $main_img; ?>",
      "description": "<?php echo htmlspecialchars($meta_desc); ?>",
      "brand": {
        "@type": "Brand",
        "name": "VT Export"
      }
      <?php if($total_reviews > 0): ?>
      ,"aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?php echo $avg_rating; ?>",
        "reviewCount": "<?php echo $total_reviews; ?>"
      }
      <?php endif; ?>
    }
    </script>
    
</head>
<body>

<?php 
include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<!-- Main Product Detail -->
<section class="prod-details-section">
    <div class="container">
        <div class="row align-items-start">
            <!-- Left: Image -->
            <div class="col-lg-6">
                <div class="main-img-box">
                    <span class="badge-export">Export Grade</span>
                    <img src="<?php echo $main_img; ?>" alt="<?php echo htmlspecialchars($product['pro_name']); ?>">
                </div>
            </div>
            
            <!-- Right: Content -->
            <div class="col-lg-6">
                <div class="prod-info-wrapper">
                    <h2 class="prod-info-title"><?php echo htmlspecialchars($product['pro_name']); ?></h2>
                    <div class="title-line"></div>
                    
                    <div class="rating-wrap">
                        <div>
                            <?php 
                            for($i=1; $i<=5; $i++){
                                echo $i <= $avg_rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span>(<?php echo $total_reviews; ?> Reviews)</span>
                    </div>

                    <div class="short-desc">
                        <?php echo !empty($product['short_desc']) ? nl2br(htmlspecialchars($product['short_desc'])) : 'Premium export quality product, processed and packaged under strict international guidelines to ensure maximum freshness and authenticity.'; ?>
                    </div>

                    <div class="action-btns">
                        <!-- Redirect to contact with product name -->
                        <a href="contact.php?product=<?php echo urlencode($product['pro_name']); ?>" class="btn-quote">Request a Quote</a>
                        <!-- Dynamic WhatsApp Link[cite: 2] -->
                        <a href="https://wa.me/<?php echo $wa_number; ?>?text=Hello, I am interested in <?php echo urlencode($product['pro_name']); ?>." target="_blank" class="btn-whatsapp">
                            <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i> WhatsApp
                        </a>
                    </div>

                    <div class="highlights-box">
                        <ul>
                            <li><i class="fas fa-check"></i> 100% Authentic Quality</li>
                            <li><i class="fas fa-check"></i> Bulk Supply Available</li>
                            <li><i class="fas fa-check"></i> Global Shipping</li>
                            <li><i class="fas fa-check"></i> ISO Certified Process</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Tabs (Description & Reviews) -->
<section class="custom-tabs-section">
    <div class="container">
        <div class="nav-tabs-custom" id="productTabs" role="tablist">
            <button class="active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Product Overview</button>
            <button id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Client Reviews (<?php echo $total_reviews; ?>)</button>
        </div>
        
        <div class="tab-content" id="productTabsContent">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row">
                    <div class="col-lg-8" style="color: var(--text-muted); line-height: 1.8;">
                        <?php 
                        if(!empty($product['description'])) {
                            echo htmlspecialchars_decode($product['description']); // Assuming it contains HTML from admin editor[cite: 2]
                        } else {
                            echo '<p>This premium product is carefully sourced and processed to meet global standards. Our rigorous quality control ensures that every batch retains its natural aroma, flavor, and nutritional value. Suitable for various industrial and culinary applications.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="row g-5">
                    <!-- Display Reviews -->
                    <div class="col-lg-7">
                        <h4 style="color: var(--primary-blue); font-weight: 700; margin-bottom: 25px;">Latest Feedback</h4>
                        <?php 
                        if($total_reviews > 0): 
                            while($rev = mysqli_fetch_assoc($reviews_query)):
                        ?>
                        <div class="review-card">
                            <div class="stars">
                                <?php for($i=1; $i<=5; $i++){ echo $i <= $rev['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; } ?>
                            </div>
                            <h5><?php echo htmlspecialchars($rev['reviewer_name']); ?></h5>
                            <small class="text-muted d-block mb-2"><?php echo date('F d, Y', strtotime($rev['created_at'])); ?></small>
                            <p><?php echo nl2br(htmlspecialchars($rev['review_message'])); ?></p>
                        </div>
                        <?php 
                            endwhile; 
                        else: 
                            echo '<p>No reviews yet. Be the first to review this product!</p>';
                        endif; 
                        ?>
                    </div>
                    
                    <!-- Submit Review Form -->
                    <div class="col-lg-5">
                        <div class="review-form-box">
                            <h4 style="color: var(--primary-blue); font-weight: 700; margin-bottom: 20px;">Write a Review</h4>
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 600;">Rating</label>
                                    <select name="rating" class="form-control" style="border: 1px solid #ddd; padding: 12px; border-radius: 4px;" required>
                                        <option value="5">5 Stars - Excellent</option>
                                        <option value="4">4 Stars - Very Good</option>
                                        <option value="3">3 Stars - Good</option>
                                        <option value="2">2 Stars - Fair</option>
                                        <option value="1">1 Star - Poor</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="reviewer_name" class="form-control" placeholder="Your Name *" style="border: 1px solid #ddd; padding: 12px; border-radius: 4px;" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="reviewer_email" class="form-control" placeholder="Your Email *" style="border: 1px solid #ddd; padding: 12px; border-radius: 4px;" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="review_message" class="form-control" rows="4" placeholder="Your Feedback *" style="border: 1px solid #ddd; padding: 12px; border-radius: 4px; resize: vertical;" required></textarea>
                                </div>
                                <button type="submit" name="submit_review" class="btn-quote" style="width: 100%; padding: 12px;">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section[cite: 2] -->
<?php
$rel_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products WHERE pro_cate = '$pro_cate' AND id != '$product_id' AND status = 1 ORDER BY id DESC LIMIT 4");
if(mysqli_num_rows($rel_query) > 0):
?>
<section class="prod-details-section" style="background: #ffffff; border-top: 1px solid #eaeaea;">
    <div class="container">
        <div class="text-center mb-5">
            <h3 style="color: var(--primary-blue); font-weight: 800; font-size: 2.2rem;">Related Products</h3>
            <div class="title-line mx-auto" style="margin-top: 15px;"></div>
        </div>
        <div class="row g-4">
            <?php 
            while($rel = mysqli_fetch_assoc($rel_query)): 
                $r_img = !empty($rel['pro_img']) ? 'admin/assets/img/uploads/' . $rel['pro_img'] : 'assets/images/default-product.jpg';
                if(!empty($rel['pro_img']) && !file_exists($r_img)) { $r_img = 'admin/' . $rel['pro_img']; }
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <!-- Using the same card design CSS from products.php -->
                <div class="prod-card" style="border: 1px solid #eaeaea; border-radius: 8px; overflow: hidden; transition: 0.3s; background: #fff;">
                    <div style="position: relative; height: 200px; padding: 10px; background: #fff;">
                        <span class="badge-export" style="top: 10px; left: 10px; font-size: 0.7rem; padding: 4px 10px;">Export Grade</span>
                        <img src="<?php echo $r_img; ?>" alt="<?php echo htmlspecialchars($rel['pro_name']); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div style="padding: 20px; text-align: left; border-top: 1px solid #eaeaea;">
                        <a href="product-details.php?slug=<?php echo $rel['slug_url']; ?>" style="text-decoration: none;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--primary-blue); margin-bottom: 15px;"><?php echo htmlspecialchars($rel['pro_name']); ?></h4>
                        </a>
                        <a href="product-details.php?slug=<?php echo $rel['slug_url']; ?>" style="color: #555; text-decoration: none; font-size: 0.9rem; font-weight: 500;">View Details <i class="fas fa-chevron-right" style="font-size: 0.75rem; margin-left: 5px; color: var(--accent-gold);"></i></a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Include the exact same Inquiry & Map Section logic here from index page -->
<?php 
// Example structure: 
include 'includes/inquiry_section.php'; 
?>

<?php 
include 'includes/footer.php'; 
?>
</body>
</html>