<?php
// Fetch active footer logo
$f_logo_query = mysqli_query($conn, "SELECT logo_path FROM logos WHERE location='header' AND is_active=1 ORDER BY id DESC LIMIT 1");
$f_logo_data = mysqli_fetch_assoc($f_logo_query);
$footer_logo = !empty($f_logo_data['logo_path']) ? $f_logo_data['logo_path'] : 'assets/images/default-logo.png';

// Contact Data header.php se available hoga, agar nahi toh:
$contact_query = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
?>

<footer class="footer-section">
    <div class="container">
        <div class="row g-5">
            
            <!-- Column 1: About & Logo -->
            <div class="col-lg-4 col-md-6">
                <img src="admin/uploads/<?php echo $footer_logo; ?>" alt="VT Export Footer Logo" class="footer-logo">
                <p class="footer-desc">Delivering premium quality export products worldwide. We bridge the gap between global markets and ethically sourced agricultural excellence.</p>
                <div class="footer-social">
                    <?php if(!empty($contact['facebook'])): ?><a href="<?php echo $contact['facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if(!empty($contact['twitter'])): ?><a href="<?php echo $contact['twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                    <?php if(!empty($contact['instagram'])): ?><a href="<?php echo $contact['instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if(!empty($contact['linkdin'])): ?><a href="<?php echo $contact['linkdin']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                </div>
            </div>
            
            <!-- Column 2: Quick Links[cite: 11] -->
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="products.php">Our Products</a></li>
                    <li><a href="services.php">Our Services</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            
            <!-- Column 3: Legal & Policies (New addition based on files)[cite: 12] -->
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Legal & Policies</h4>
                <ul class="footer-links">
                    <li><a href="privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="terms-condition.php">Terms & Conditions</a></li>
                    <li><a href="refund-policy.php">Refund Policy</a></li>
                    <li><a href="shipping-return.php">Shipping & Returns</a></li>
                </ul>
            </div>
            
            <!-- Column 4: Contact Info[cite: 11] -->
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Contact Info</h4>
                <ul class="footer-contact">
                    <?php if(!empty($contact['address'])): ?>
                    <li>
                        <i class="fas fa-map-marker-alt"></i> 
                        <span><?php echo nl2br(htmlspecialchars($contact['address'])); ?></span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($contact['phone'])): ?>
                    <li>
                        <i class="fas fa-phone-alt"></i> 
                        <span><?php echo htmlspecialchars($contact['phone']); ?></span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($contact['email'])): ?>
                    <li>
                        <i class="fas fa-envelope"></i> 
                        <span><?php echo htmlspecialchars($contact['email']); ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
        </div>
    </div>
    
    <!-- Bottom Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <p>Copyright &copy; <?php echo date('Y'); ?> VT-EXPORT. All Rights Reserved. Design By <a href="https://digitalwebtrackers.com" target="_blank">digitalwebtrackers.com</a></p>
        </div>
    </div>
</footer>