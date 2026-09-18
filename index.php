<?php 
include ('includes/header.php');

include 'config/connect.php'; 

$banner_query = mysqli_query($conn, "SELECT * FROM banners ORDER BY display_order ASC, id DESC");
$banner_count = mysqli_num_rows($banner_query);
?>

<!-- Hero Slider Section -->
<section class="hero-slider">
    <div id="premiumHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        
        <!-- Dynamic Indicators -->
        <div class="carousel-indicators">
            <?php for($i = 0; $i < $banner_count; $i++): ?>
                <button type="button" data-bs-target="#premiumHeroCarousel" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0) ? 'active' : ''; ?>" aria-current="<?php echo ($i == 0) ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
            <?php endfor; ?>
        </div>

        <!-- Dynamic Carousel Items -->
        <div class="carousel-inner">
            <?php 
            $isActive = true;
            if($banner_count > 0):
                // Reset pointer aur loop start
                mysqli_data_seek($banner_query, 0); 
                while($banner = mysqli_fetch_assoc($banner_query)): 
                    // Making part of title Gold randomly or specifically if you format it. For now, simple output.
            ?>
            <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>">
                <!-- Ensure correct image path based on your admin panel uploads -->
                <img src="admin/<?php echo $banner['banner_path']; ?>" alt="<?php echo !empty($banner['meta_title']) ? $banner['meta_title'] : $banner['title']; ?>">
                
                <div class="carousel-overlay"></div>
                
                <div class="custom-caption">
                    <div class="container">
                        <!-- Dynamic Title (HTML allowed if saved from editor) -->
                        <h1><?php echo htmlspecialchars($banner['title']); ?></h1>
                        <!-- Dynamic Description -->
                        <p><?php echo htmlspecialchars($banner['description']); ?></p>
                        
                        <div class="hero-btns">
                            <?php if(!empty($banner['link_url'])): ?>
                                <a href="<?php echo $banner['link_url']; ?>" class="btn-hero-primary">Discover More</a>
                            <?php else: ?>
                                <a href="products.php" class="btn-hero-primary">Explore Products</a>
                            <?php endif; ?>
                            <a href="contact.php" class="btn-hero-outline">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                $isActive = false;
                endwhile; 
            else:
            ?>
            <!-- Fallback Static Slide in case DB has no banners -->
            <div class="carousel-item active">
                <img src="assets/images/default-hero.jpg" alt="VT Export Default">
                <div class="carousel-overlay"></div>
                <div class="custom-caption">
                    <div class="container">
                        <h1>Premium <span class="text-gold">Quality</span> Casting Solutions</h1>
                        <p>Delivering heavy-duty, precision-engineered industrial components built to withstand extreme environments.</p>
                        <div class="hero-btns">
                            <a href="products.php" class="btn-hero-primary">Our Products</a>
                            <a href="quote.php" class="btn-hero-outline">Get Quote</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#premiumHeroCarousel" data-bs-slide="prev" style="width: 5%; opacity: 0;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#premiumHeroCarousel" data-bs-slide="next" style="width: 5%; opacity: 0;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- About Section -->
<?php
$about_query = mysqli_query($conn, "SELECT title, content, image_url FROM about_sections ORDER BY section_order ASC LIMIT 1");
$about_data = mysqli_fetch_assoc($about_query);
$db_image = $about_data['image_url'];
$about_img = !empty($db_image) ? 'admin/' . $db_image : 'assets/images/default-about.jpg';
?>

<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Left Side: Premium Image Layout -->
            <div class="col-lg-6">
                <div class="about-img-wrapper">
                    <img src="<?php echo $about_img; ?>" alt="<?php echo htmlspecialchars($about_data['title'] ?? 'About VT Export'); ?>" class="about-main-img img-fluid">
                    
                    <!-- Dynamic Experience Badge (Optional: Can make this dynamic via DB too) -->
                    <div class="experience-badge d-none d-md-block">
                        <h3>15+</h3>
                        <p>Years of<br>Excellence</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Dynamic Content -->
            <div class="col-lg-6">
                <div class="about-content">
                    <span class="sub-heading">About Us</span>
                    
                    <!-- Dynamic Title -->
                    <h2 class="about-title">
                        <?php echo !empty($about_data['title']) ? htmlspecialchars($about_data['title']) : 'Global Leaders in Premium Quality Export'; ?>
                    </h2>
                    
                    <!-- Dynamic Content -->
                    <div class="about-desc">
                        <?php 
                        if(!empty($about_data['content'])) {
                            // Agar admin ne new lines di hain, toh usko check karke elegant format karte hain
                            // Text se line breaks ko HTML <br> me convert kar rahe hain
                            echo nl2br(htmlspecialchars($about_data['content']));
                        } else {
                            echo "We specialize in processing and exporting premium quality products globally. Our commitment is to deliver farm-fresh, unadulterated, and richly flavored food products to international markets while maintaining the highest levels of purity.";
                        }
                        ?>
                    </div>

                    <!-- Static Features List for Premium SEO/Trust building -->
                    <ul class="about-features">
                        <li><i class="fas fa-check-circle"></i> Ethically sourced directly from the finest farms.</li>
                        <li><i class="fas fa-check-circle"></i> Strict compliance with global safety standards.</li>
                        <li><i class="fas fa-check-circle"></i> Uncompromised purity and natural quality.</li>
                    </ul>

                    <a href="about.php" class="btn-about">Read More <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Services Section -->
<?php
$services_query = mysqli_query($conn, "SELECT service_name, short_desc, img_path FROM services ORDER BY id ASC");
?>

<section class="services-section">
    <div class="container">
        <div class="section-title">
            <span class="sub-heading-center">What We Do</span>
            <h2>Our Premium Services</h2>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php 
            if(mysqli_num_rows($services_query) > 0) {
                while($service = mysqli_fetch_assoc($services_query)) {
                    
                    $db_img = $service['img_path'];
                    if(!empty($db_img)) {
                        $img_src = 'admin/assets/img/uploads/' . $db_img; 
                        if(!file_exists($img_src)) {
                            $img_src = 'admin/' . $db_img;
                        }
                    } else {
                        $img_src = 'assets/images/default-icon.png';
                    }
            ?>
            
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon-wrapper">
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                    </div>
                    <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
                    <p><?php echo htmlspecialchars($service['short_desc']); ?></p>
                    
                    <a href="services.php" class="btn-service-link">
                        Explore <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <?php 
                }
            } else {
                echo "<div class='col-12 text-center'><p>No services found.</p></div>";
            }
            ?>
        </div>
    </div>
</section>

<!-- Who We Are Section -->
<?php
$who_query = mysqli_query($conn, "SELECT title, content, image_url FROM about_us ORDER BY id DESC LIMIT 1");
$who_data = mysqli_fetch_assoc($who_query);

$db_who_img = $who_data['image_url'] ?? '';
$who_img = !empty($db_who_img) ? 'admin/' . $db_who_img : 'assets/images/default-who.jpg';
?>

<section class="who-section">
    <div class="container who-content-wrapper">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="who-title">Who <span>We Are</span></h2>
                <div style="width: 60px; height: 3px; background: #d4af37; margin-bottom: 25px;"></div>
                <div class="who-desc">
                    <?php echo !empty($who_data['content']) ? nl2br($who_data['content']) : 'We are a globally recognized export house dedicated to bringing the finest quality products to the world market. Our foundation is built on trust, quality, and unmatched client satisfaction.'; ?>
                </div>
                <a href="about.php" class="btn-hero-primary" style="background: #d4af37; color: #0a2540; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 600;">Discover Our Journey</a>
            </div>
            <div class="col-lg-6">
                <div class="who-img-box">
                    <img src="<?php echo $who_img; ?>" alt="Who We Are">
                    <div class="who-floating-box d-none d-md-block">
                        Global Reach <br> Premium Standards
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Categories Section -->
<?php
$cat_query = mysqli_query($conn, "SELECT categories, slug_url, image FROM categories WHERE status=1 ORDER BY id ASC LIMIT 6");
?>

<section class="category-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="sub-heading-center">Our Offerings</span>
            <h2 class="text-primary-blue fw-bold">Explore Categories</h2>
        </div>
        <div class="row g-4">
            <?php while($cat = mysqli_fetch_assoc($cat_query)): 
                $img_path = !empty($cat['image']) ? 'admin/uploads/category/' . $cat['image'] : 'assets/images/default-cat.jpg';
            ?>
            <div class="col-lg-4 col-md-6">
                <a href="category.php?slug=<?php echo $cat['slug_url']; ?>" class="cat-card">
                    <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($cat['categories']); ?>" class="cat-img">
                    <div class="cat-overlay">
                        <h3 class="cat-title"><?php echo htmlspecialchars($cat['categories']); ?></h3>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Product section -->
<?php
$prod_query = mysqli_query($conn, "SELECT pro_name, pro_img, slug_url FROM products WHERE status=1 ORDER BY id DESC LIMIT 8");

$contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
$phone_number = !empty($contact['phone']) ? $contact['phone'] : '+91-0000000000';
?>

<section class="product-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold" style="color: var(--primary-blue);">Our Premium Products</h2>
            <div style="width: 50px; height: 3px; background: var(--accent-gold); margin: 15px auto;"></div>
        </div>
        
        <div class="row g-4">
            <?php 
            if(mysqli_num_rows($prod_query) > 0) {
                while($prod = mysqli_fetch_assoc($prod_query)) {
                    // Image Path Logic
                    $db_img = $prod['pro_img'];
                    $p_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
                    // Fallback local check
                    if(!empty($db_img) && !file_exists($p_img)) { $p_img = 'admin/' . $db_img; }
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="prod-card">
                    <!-- Image & Badge -->
                    <div class="prod-img-wrapper">
                        <span class="export-badge">Export Grade</span>
                        <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($prod['pro_name']); ?>">
                    </div>
                    
                    <!-- Content -->
                    <div class="prod-body">
                        <h3 class="prod-title"><?php echo htmlspecialchars($prod['pro_name']); ?></h3>
                        
                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" class="prod-link">
                            View Details <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <!-- Actions -->
                        <div class="prod-actions">
                            <a href="tel:<?php echo $phone_number; ?>" class="btn-call" title="Call Us">
                                <i class="fas fa-phone"></i>
                            </a>
                            <!-- Passing product name in URL to pre-fill inquiry form -->
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
                echo '<div class="col-12 text-center"><p>No products available at the moment.</p></div>';
            }
            ?>
        </div>
    </div>
</section>


<!-- Testimonials Section -->
<?php
$test_query = mysqli_query($conn, "SELECT name, designation, message FROM testimonials WHERE status=1 ORDER BY test_id DESC LIMIT 3");
?>

<section class="testimonial-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="text-white fw-bold">What Our Clients Say</h2>
            <div style="width: 60px; height: 3px; background: var(--accent-gold); margin: 15px auto;"></div>
        </div>
        <div class="row g-4">
            <?php while($test = mysqli_fetch_assoc($test_query)): ?>
            <div class="col-md-4">
                <div class="testi-card">
                    <i class="fas fa-quote-left quote-icon"></i>
                    <p class="testi-msg">"<?php echo htmlspecialchars($test['message']); ?>"</p>
                    <h4 class="testi-name"><?php echo htmlspecialchars($test['name']); ?></h4>
                    <span class="testi-desig"><?php echo htmlspecialchars($test['designation']); ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<?php
$gal_query = mysqli_query($conn, "SELECT image_path, image_name FROM gallery ORDER BY ID DESC LIMIT 6");
?>

<section class="gallery-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="sub-heading-center" style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Our Infrastructure</span>
            <h2 class="fw-bold" style="color: #0a2540;">Visual Gallery</h2>
            <div style="width: 50px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>
        
        <div class="row">
            <?php 
            if(mysqli_num_rows($gal_query) > 0) {
                while($gal = mysqli_fetch_assoc($gal_query)): 
                    $g_img = !empty($gal['image_path']) ? 'admin/' . $gal['image_path'] : 'assets/images/default-gallery.jpg';
            ?>
            <div class="col-lg-4 col-md-6">
                <a href="<?php echo $g_img; ?>" class="gallery-item" title="<?php echo htmlspecialchars($gal['image_name']); ?>">
                    <img src="<?php echo $g_img; ?>" alt="Gallery Image">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                        <span class="gallery-title">View Image</span>
                    </div>
                </a>
            </div>
            <?php endwhile; } else { echo "<p class='text-center'>Gallery images coming soon.</p>"; } ?>
        </div>
        <div class="text-center mt-4">
            <a href="gallery.php" class="btn-hero-outline" style="border: 2px solid #0a2540; color: #0a2540; padding: 10px 30px; border-radius: 30px; text-decoration: none; font-weight: 600;">View Full Gallery</a>
        </div>
    </div>
</section>

<?php
$blog_query = mysqli_query($conn, "SELECT title, slug, image, description, created_at FROM blogs WHERE status=1 ORDER BY blog_id DESC LIMIT 3");
?>

<section class="blog-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="sub-heading-center" style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Latest Updates</span>
            <h2 class="fw-bold" style="color: #0a2540;">News & Articles</h2>
            <div style="width: 50px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php 
            if(mysqli_num_rows($blog_query) > 0) {
                while($blog = mysqli_fetch_assoc($blog_query)): 
                    $b_img = !empty($blog['image']) ? 'admin/assets/img/uploads/blogs/' . $blog['image'] : 'assets/images/default-blog.jpg';
                    $date = strtotime($blog['created_at']);
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="blog-card">
                    <div class="blog-img-wrap">
                        <img src="<?php echo $b_img; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                        <div class="blog-date-badge">
                            <span><?php echo date('d', $date); ?></span>
                            <small><?php echo date('M, Y', $date); ?></small>
                        </div>
                    </div>
                    <div class="blog-content">
                        <h3 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <div class="blog-desc">
                            <?php 
                                // HTML tags remove karke text ko short karte hain
                                $excerpt = strip_tags($blog['description']);
                                echo strlen($excerpt) > 100 ? substr($excerpt, 0, 100) . '...' : $excerpt; 
                            ?>
                        </div>
                        <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="btn-read-more">Read Full Article <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php endwhile; } else { echo "<p class='text-center'>No blog posts available.</p>"; } ?>
        </div>
    </div>
</section>

<!-- Brands Section -->
<?php
$brand_query = mysqli_query($conn, "SELECT brand_name, logo_path FROM brands ORDER BY id DESC");
$brands = [];
if(mysqli_num_rows($brand_query) > 0) {
    while($row = mysqli_fetch_assoc($brand_query)) {
        $brands[] = $row;
    }
}
?>

<section class="brands-section">
    <div class="container">
        <div class="brands-header">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Global Trust</span>
            <h2>Our Trusted Partners</h2>
            <div style="width: 50px; height: 3px; background: #d4af37; margin: 15px auto 20px;"></div>
            <p>We are proud to collaborate with industry-leading brands and organizations globally, delivering uncompromised quality and excellence.</p>
        </div>
    </div>

    <div class="brand-slider-container">
        <div class="brand-track">
            <?php 
            if(!empty($brands)) {
                // Loop twice to create the seamless infinite scroll illusion
                for($i = 0; $i < 2; $i++) {
                    foreach($brands as $brand) {
                        // Image path logic matching previous sections
                        $b_img = !empty($brand['logo_path']) ? 'admin/' . $brand['logo_path'] : 'assets/images/default-brand.png';
                        echo '<div class="brand-item">';
                        echo '<img src="'.$b_img.'" alt="'.htmlspecialchars($brand['brand_name']).'" title="'.htmlspecialchars($brand['brand_name']).'">';
                        echo '</div>';
                    }
                }
            } else {
                echo '<p class="text-center w-100">Partner logos will appear here.</p>';
            }
            ?>
        </div>
    </div>
</section>


<!-- Inquiry Section -->
<?php
$contact_query = mysqli_query($conn, "SELECT map, address, phone, email FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);

$map_url = !empty($contact['map']) ? $contact['map'] : 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83923192868!2d77.06889754725779!3d28.52758200617607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin';
?>

<section class="inquiry-section">
    <div class="container">
        <div class="inquiry-header">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Get In Touch</span>
            <h2>Request a Callback or Quote</h2>
            <div style="width: 50px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>

        <div class="row g-4 align-items-stretch">
            
            <!-- Left Side: Map Location -->
            <div class="col-lg-6">
                <div class="map-box">
                    <!-- Rendering map URL from DB directly into iframe[cite: 2] -->
                    <iframe src="<?php echo $map_url; ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Right Side: Premium Inquiry Form -->
            <div class="col-lg-6">
                <div class="inquiry-form-wrapper">
                    <h3>Send Your Inquiry</h3>
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 30px;">Fill out the form below, and our export experts will get back to you promptly.</p>
                    
                    <!-- Ensure the action attribute points to your inquiry handling PHP file -->
                    <form action="submit_inquiry.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control-custom" placeholder="Full Name *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control-custom" placeholder="Email Address *" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="tel" name="phone" class="form-control-custom" placeholder="Phone / WhatsApp *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="subject" class="form-control-custom" placeholder="Subject / Product Name *" required>
                            </div>
                        </div>
                        <textarea name="message" class="form-control-custom" placeholder="Tell us about your requirements... *" required></textarea>
                        
                        <button type="submit" name="submit_inquiry" class="btn-submit">Submit Inquiry <i class="fas fa-paper-plane ms-2"></i></button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include ('includes/footer.php') ?>