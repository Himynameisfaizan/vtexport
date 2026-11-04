<?php
require_once 'config/connect.php'; 

// Fetch Product by Slug (Rewritten URL se aayega)
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if(empty($slug)){ header("Location: products.php"); exit; }

$prod_query = mysqli_query($conn, "SELECT * FROM products WHERE slug_url = '$slug' AND status = 1");
if(mysqli_num_rows($prod_query) == 0){ header("Location: 404.php"); exit; }
$product = mysqli_fetch_assoc($prod_query);
$product_id = $product['id'];
$pro_cate = $product['pro_cate'];

// Image Path Logic
$db_img = $product['pro_img'];
$main_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
if(!empty($db_img) && !file_exists($main_img)) { $main_img = 'admin/' . $main_img; } // Added admin prefix check

// Fetch Contact Data
$contact_query = mysqli_query($conn, "SELECT wp_number, phone, email, map FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
$wa_number = preg_replace('/[^0-9]/', '', $contact['wp_number'] ?? '0000000000');

// Handle Review Submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $r_name = mysqli_real_escape_string($conn, $_POST['reviewer_name']);
    $r_email = mysqli_real_escape_string($conn, $_POST['reviewer_email']);
    $r_rating = (int)$_POST['rating'];
    $r_msg = mysqli_real_escape_string($conn, $_POST['review_message']);
    
    mysqli_query($conn, "INSERT INTO product_reviews (product_id, rating, review_message, reviewer_name, reviewer_email) VALUES ('$product_id', '$r_rating', '$r_msg', '$r_name', '$r_email')");
    // URL Rewrite syntax use kiya redirection ke liye
    header("Location: product/$slug#reviews");
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
$pageTitle = !empty($product['breadcrumb_title']) ? $product['breadcrumb_title'] : $product['pro_name'];
$custom_schema = !empty($product['schema_markup']) ? $product['schema_markup'] : '';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
if (strpos($host, 'localhost') === false && strpos($host, '127.0.0.1') === false && strpos($host, 'www.') !== 0) {
    $host = 'www.' . $host;
}
$clean_uri = strtok($_SERVER['REQUEST_URI'], '?');
$custom_canonical = $protocol . $host . $clean_uri;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($meta_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_key); ?>">

    <link rel="canonical" href="<?php echo htmlspecialchars($custom_canonical); ?>">


    <!-- 🔥 FIX: BASE TAG FOR URL REWRITES 🔥 -->
    <base href="http://localhost/projects/vtexport/">

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
        <?php if($total_reviews > 0): ?>,
        "aggregateRating": {
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

    <style>
    /* Styling to ensure layout doesn't break */
    .prod-details-section {
        padding: 80px 0;
        background: #f8f9fc;
    }

    .badge-export {
        top: 15px;
        left: 15px;
        background: #741b38;
        color: #fff;
        padding: 5px 12px;
        font-size: 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        z-index: 2;
        position: absolute;
    }

    .title-line {
        width: 60px;
        height: 3px;
        background: #d4af37;
        margin: 15px 0;
    }

    .rating-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: #d4af37;
    }

    .rating-wrap span {
        color: #555;
        font-size: 0.9rem;
    }

    .action-btns {
        display: flex;
        gap: 15px;
        margin: 30px 0;
    }

    .btn-quote {
        background: #0a2540;
        color: #fff;
        padding: 12px 30px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-quote:hover {
        background: #d4af37;
        color: #0a2540;
    }

    .btn-whatsapp {
        background: #25d366;
        color: #fff;
        padding: 12px 30px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }

    .btn-whatsapp:hover {
        background: #128C7E;
        color: #fff;
    }

    .highlights-box ul {
        list-style: none;
        padding: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .highlights-box ul li {
        color: #444;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .highlights-box ul li i {
        color: #d4af37;
    }
    </style>

    <section class="prod-details-section">
        <div class="container">
            <div class="row align-items-start g-5">
                <!-- Left: Image & Gallery -->
                <div class="col-lg-6">
                    <div class="main-img-box position-relative"
                        style="background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 20px; text-align: center; height: 450px; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
                        <span class="badge-export">Export Grade</span>
                        <img id="featuredImage" src="<?php echo $main_img; ?>"
                            alt="<?php echo htmlspecialchars($product['pro_name']); ?>"
                            style="max-height: 100%; max-width: 100%; object-fit: contain; transition: 0.3s ease;">
                    </div>

                    <!-- Gallery Thumbnails -->
                    <?php
                $gal_query = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = '$product_id'");
                if (mysqli_num_rows($gal_query) > 0): 
                ?>
                    <div class="product-gallery-slider mt-3">
                        <div class="d-flex gap-3 overflow-auto pb-2 custom-gallery-scrollbar"
                            style="white-space: nowrap;">
                            <div class="gallery-thumbnail active"
                                onclick="changeFeaturedImage('<?php echo $main_img; ?>', this)"
                                style="cursor: pointer; width: 90px; height: 90px; flex-shrink: 0; border: 2px solid #d4af37; border-radius: 6px; padding: 5px; background: #fff;">
                                <img src="<?php echo $main_img; ?>"
                                    style="width: 100%; height: 100%; object-fit: contain;" alt="Main Image">
                            </div>
                            <?php while ($gal = mysqli_fetch_assoc($gal_query)) {
                            $thumb_img = 'admin/assets/img/uploads/' . $gal['image_path'];
                        ?>
                            <div class="gallery-thumbnail"
                                onclick="changeFeaturedImage('<?php echo $thumb_img; ?>', this)"
                                style="cursor: pointer; width: 90px; height: 90px; flex-shrink: 0; border: 1px solid #eaeaea; border-radius: 6px; padding: 5px; background: #fff; transition: 0.3s;">
                                <img src="<?php echo $thumb_img; ?>"
                                    style="width: 100%; height: 100%; object-fit: contain;" alt="Gallery Image">
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Right: Content -->
                <div class="col-lg-6">
                    <div class="prod-info-wrapper p-4 bg-white rounded border">
                        <h2 class="fw-bold" style="color: #0a2540; font-size: 2rem;">
                            <?php echo htmlspecialchars($product['pro_name']); ?></h2>
                        <div class="title-line"></div>
                        <div class="rating-wrap">
                            <div>
                                <?php for($i=1; $i<=5; $i++){ echo $i <= $avg_rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; } ?>
                            </div>
                            <span>(<?php echo $total_reviews; ?> Reviews)</span>
                        </div>

                        <div class="short-desc mb-4" style="color: #555; line-height: 1.7;">
                            <?php echo !empty($product['short_desc']) ? htmlspecialchars_decode($product['short_desc']) : 'Premium export quality product.'; ?>
                        </div>

                        <div class="action-btns">
                            <a href="contact.php?product=<?php echo urlencode($product['pro_name']); ?>"
                                class="btn-quote">Request a Quote</a>
                            <a href="https://wa.me/<?php echo $wa_number; ?>?text=Hello, I am interested in <?php echo urlencode($product['pro_name']); ?>."
                                target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>

                        <div class="highlights-box border-top pt-3">
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

    <!-- Tabs & Overview -->
    <section class="py-5" style="background: #ffffff;">
        <div class="container">
            <!-- Minimal Nav Tabs to fix CSS break -->
            <ul class="nav nav-tabs mb-4 border-bottom" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark border-0 border-bottom border-3 border-dark"
                        id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab"
                        style="background: none;">Product Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-muted border-0" id="reviews-tab" data-bs-toggle="tab"
                        data-bs-target="#reviews" type="button" role="tab" style="background: none;">Client Reviews
                        (<?php echo $total_reviews; ?>)</button>
                </li>
            </ul>

            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8" style="color: #555; line-height: 1.8;">
                            <?php echo !empty($product['description']) ? htmlspecialchars_decode($product['description']) : '<p>No description available.</p>'; ?>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="row g-5">
                        <div class="col-lg-7">
                            <h4 class="mb-4" style="color: #0a2540;">Latest Feedback</h4>
                            <?php if($total_reviews > 0): while($rev = mysqli_fetch_assoc($reviews_query)): ?>
                            <div class="p-3 mb-3 bg-light border rounded">
                                <div class="text-warning mb-2">
                                    <?php for($i=1; $i<=5; $i++){ echo $i <= $rev['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; } ?>
                                </div>
                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($rev['reviewer_name']); ?></h6>
                                <small
                                    class="text-muted d-block mb-2"><?php echo date('F d, Y', strtotime($rev['created_at'])); ?></small>
                                <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($rev['review_message'])); ?>
                                </p>
                            </div>
                            <?php endwhile; else: echo '<p class="text-muted">No reviews yet.</p>'; endif; ?>
                        </div>

                        <div class="col-lg-5">
                            <div class="p-4 border rounded bg-light">
                                <h4 class="mb-4" style="color: #0a2540;">Write a Review</h4>
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <select name="rating" class="form-select" required>
                                            <option value="5">5 Stars - Excellent</option>
                                            <option value="4">4 Stars - Very Good</option>
                                            <option value="3">3 Stars - Good</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="reviewer_name" class="form-control"
                                            placeholder="Your Name *" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" name="reviewer_email" class="form-control"
                                            placeholder="Your Email *" required>
                                    </div>
                                    <div class="mb-4">
                                        <textarea name="review_message" class="form-control" rows="4"
                                            placeholder="Your Feedback *" required></textarea>
                                    </div>
                                    <button type="submit" name="submit_review" class="btn btn-quote w-100">Submit
                                        Review</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products (Fixed URL Format) -->
    <?php
$rel_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products WHERE pro_cate = '$pro_cate' AND id != '$product_id' AND status = 1 ORDER BY id DESC LIMIT 4");
if(mysqli_num_rows($rel_query) > 0):
?>
    <section class="py-5 bg-light border-top">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="fw-bold" style="color: #0a2540;">Related Products</h3>
                <div class="title-line mx-auto"></div>
            </div>
            <div class="row g-4">
                <?php while($rel = mysqli_fetch_assoc($rel_query)): 
                $r_img = !empty($rel['pro_img']) ? 'admin/assets/img/uploads/' . $rel['pro_img'] : 'assets/images/default-product.jpg';
                if(!empty($rel['pro_img']) && !file_exists($r_img)) { $r_img = 'admin/' . $rel['pro_img']; }
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="bg-white border rounded h-100 text-center"
                        style="transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                        <div style="height: 200px; padding: 15px; position: relative;">
                            <span class="badge-export">Export Grade</span>
                            <a href="product/<?php echo $rel['slug_url']; ?>">
                                <img src="<?php echo $r_img; ?>" alt="<?php echo htmlspecialchars($rel['pro_name']); ?>"
                                    style="width: 100%; height: 100%; object-fit: contain;">
                            </a>
                        </div>
                        <div class="p-3 border-top text-start">
                            <a href="product/<?php echo $rel['slug_url']; ?>"
                                style="text-decoration: none; color: #0a2540;">
                                <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($rel['pro_name']); ?></h6>
                            </a>
                            <a href="product/<?php echo $rel['slug_url']; ?>"
                                class="small text-muted text-decoration-none">View Details <i
                                    class="fas fa-chevron-right ms-1 text-warning"></i></a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <script>
    function changeFeaturedImage(newImageSrc, element) {
        document.getElementById('featuredImage').src = newImageSrc;
        let thumbnails = document.querySelectorAll('.gallery-thumbnail');
        thumbnails.forEach(function(thumb) {
            thumb.style.border = "1px solid #eaeaea";
        });
        element.style.border = "2px solid #d4af37";
    }
    // Simple tab logic to ensure Bootstrap tabs work without breaking
    document.querySelectorAll('#productTabs button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('#productTabs button').forEach(b => {
                b.classList.remove('active', 'text-dark', 'border-bottom', 'border-3',
                    'border-dark');
                b.classList.add('text-muted');
            });
            button.classList.add('active', 'text-dark', 'border-bottom', 'border-3', 'border-dark');
            button.classList.remove('text-muted');
        });
    });
    </script>

    <?php 
include 'includes/inquiry_section.php'; 
include 'includes/footer.php'; 
?>
</body>

</html>