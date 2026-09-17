<?php
$pageTitle = "About Us";
require_once 'config/connect.php'; 
include 'includes/header.php';
include 'includes/breadcrumb.php';

$about_query = mysqli_query($conn, "SELECT title, content, image_url FROM about_sections ORDER BY section_order ASC LIMIT 1");
$about_data = mysqli_fetch_assoc($about_query);
$about_img = (!empty($about_data['image_url'])) ? 'admin/' . $about_data['image_url'] : 'assets/images/default-about.jpg';

$services_query = mysqli_query($conn, "SELECT service_name, long_desc, img_path FROM services ORDER BY id ASC");
?>

<!-- 1. Detailed About Section -->
<section class="premium-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-detail-wrapper pe-lg-4">
                    <img src="<?php echo $about_img; ?>" alt="About VT Export" class="about-detail-img">
                    <div class="about-experience-box d-none d-md-block">
                        <span>15+</span> Years of Global Export
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-text-content">
                    <span class="sub-title" style="color: #d4af37; font-weight: 600; letter-spacing: 2px;">Welcome to VT Export</span>
                    <h2 style="color: #0a2540; font-size: 2.5rem; font-weight: 800; margin-bottom: 20px;">
                        <?php echo !empty($about_data['title']) ? htmlspecialchars($about_data['title']) : 'Delivering Excellence Accross Borders'; ?>
                    </h2>
                    
                    <div style="width: 60px; height: 3px; background: #d4af37; margin-bottom: 25px;"></div>
                    
                    <!-- Dynamic Content with extra static rich text for depth[cite: 1] -->
                    <p><?php echo !empty($about_data['content']) ? nl2br(htmlspecialchars($about_data['content'])) : 'VT Export is a premier global trading house dedicated to sourcing, processing, and exporting the finest agricultural products and industrial solutions.'; ?></p>
                    
                    <p>We pride ourselves on our deep-rooted relationships with local farmers and manufacturers. By eliminating middlemen, we ensure that our global clientele receives unadulterated, export-grade quality at competitive prices. Every batch that leaves our facility undergoes rigorous quality checks to meet stringent international food safety standards.</p>
                    
                    <div class="about-features-grid">
                        <div class="feature-item"><i class="fas fa-certificate"></i><span>ISO Certified</span></div>
                        <div class="feature-item"><i class="fas fa-leaf"></i><span>100% Organic</span></div>
                        <div class="feature-item"><i class="fas fa-globe"></i><span>Global Network</span></div>
                        <div class="feature-item"><i class="fas fa-truck-loading"></i><span>Timely Delivery</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Mission & Vision Section -->
<section class="premium-section bg-light-gray">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="mv-card">
                    <div class="mv-icon"><i class="fas fa-bullseye"></i></div>
                    <h3>Our Mission</h3>
                    <p>To bridge the gap between hardworking farmers and global kitchens by providing hygienically processed, premium-quality agricultural products. We strive to empower local communities while maintaining an unwavering commitment to sustainable sourcing and ethical trade practices across all our operations.</p>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="mv-card">
                    <div class="mv-icon"><i class="fas fa-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>To emerge as the world's most trusted and preferred export partner for authentic Indian spices, agro-products, and industrial commodities. We aim to set global benchmarks in quality, transparency, and customer satisfaction, ensuring our products enhance the lives of consumers worldwide.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. What We Do (Dynamic from Services) -->
<section class="premium-section">
    <div class="container">
        <div class="section-header">
            <span class="sub-title">Expertise</span>
            <h2>What We Do</h2>
            <div class="header-line"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php 
            if(mysqli_num_rows($services_query) > 0) {
                while($service = mysqli_fetch_assoc($services_query)) {
                    $img_src = !empty($service['img_path']) ? 'admin/assets/img/uploads/' . $service['img_path'] : 'assets/images/default-icon.png';
            ?>
            <div class="col-lg-6">
                <div class="d-flex p-4 border rounded" style="background: #ffffff; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="min-width: 80px;">
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>" style="width: 60px; height: 60px; object-fit: contain;">
                    </div>
                    <div>
                        <h4 style="color: #0a2540; font-weight: 700;"><?php echo htmlspecialchars($service['service_name']); ?></h4>
                        <!-- Utilizing long_desc for detail on About page[cite: 1] -->
                        <div style="color: #555; font-size: 0.95rem; line-height: 1.6;">
                            <?php echo strip_tags($service['long_desc']); ?> 
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- 4. Global Presence Map Section (Updated) -->
<section class="global-map-section">
    <div class="container map-content-wrapper text-center">
        <span class="sub-title" style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Global Reach</span>
        <h2 style="font-size: clamp(2rem, 3vw, 2.8rem); font-weight: 800; margin-bottom: 20px;">Our Primary Markets</h2>
        <p style="color: rgba(255,255,255,0.8); max-width: 600px; margin: 0 auto 60px;">Delivering uncompromised quality specifically tailored to the Middle Eastern and South Asian markets.</p>
        
        <div class="map-visual-container" style="position: relative; width: 100%; max-width: 900px; height: 500px; margin: 0 auto;">
            
            <!-- Exact Location Pins -->
            <div class="map-pin pin-kuwait">
                <span class="country-label">Kuwait</span>
            </div>
            
            <div class="map-pin pin-bahrain">
                <span class="country-label">Bahrain</span>
            </div>
            
            <div class="map-pin pin-uae">
                <span class="country-label">UAE</span>
            </div>
            
            <div class="map-pin pin-oman">
                <span class="country-label">Oman</span>
            </div>
            
            <div class="map-pin pin-nepal">
                <span class="country-label">Nepal</span>
            </div>

            <!-- Optional visual frame -->
            <div style="position: absolute; inset: 0; border: 1px dashed rgba(212,175,55,0.2); border-radius: 10px; pointer-events: none;"></div>
        </div>
    </div>
</section>

<!-- 5. Working FAQs Section -->
<section class="premium-section bg-light-gray">
    <div class="container">
        <div class="section-header">
            <span class="sub-title">Queries Answered</span>
            <h2>Working FAQs</h2>
            <div class="header-line"></div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="exportFaq">
                    
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                01. What are your quality control measures?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#exportFaq">
                            <div class="accordion-body">
                                We maintain strict compliance with global food safety & hygiene standards. Every batch of our products is tested in certified laboratories for purity, moisture content, and authentic aroma before packaging and dispatch.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                02. What is the Minimum Order Quantity (MOQ)?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#exportFaq">
                            <div class="accordion-body">
                                The MOQ varies depending on the product type (e.g., Spices, Dry Fruits, Industrial items). However, we are equipped to handle bulk supply chain operations seamlessly. Please contact our sales team with your specific requirements for an exact quote.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                03. Which countries do you currently export to?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#exportFaq">
                            <div class="accordion-body">
                                We have a robust global presence, actively exporting to markets in the Middle East (UAE, Saudi Arabia), Europe, North America, and parts of Asia. Our logistics team handles all customs and clearance documentation for a smooth delivery process.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                04. Do you offer custom or private label packaging?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#exportFaq">
                            <div class="accordion-body">
                                Yes, we offer white-labeling and private packaging services for bulk B2B orders. You can provide your brand design, and we will ensure the products are packaged securely under your brand name meeting all international labeling regulations.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php 
include 'includes/footer.php'; 
?>