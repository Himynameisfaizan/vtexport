<?php
// Fetch active footer logo[cite: 1]
$f_logo_query = mysqli_query($conn, "SELECT logo_path FROM logos WHERE location='header' AND is_active=1 ORDER BY id DESC LIMIT 1");
$f_logo_data = mysqli_fetch_assoc($f_logo_query);
$footer_logo = !empty($f_logo_data['logo_path']) ? $f_logo_data['logo_path'] : 'assets/images/default-logo.png';

// Contact Data header.php se available hoga, agar nahi toh:
$contact_query = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
?>

<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <img src="admin/uploads/<?php echo $footer_logo; ?>" alt="Footer Logo" style="max-height: 60px; margin-bottom: 20px;">
                <p>Delivering premium quality export products worldwide. We bridge the gap between global markets and ethically sourced agricultural excellence.</p>
                <div class="footer-social mt-4">
                    <?php if(!empty($contact['facebook'])): ?><a href="<?php echo $contact['facebook']; ?>"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if(!empty($contact['instagram'])): ?><a href="<?php echo $contact['instagram']; ?>"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if(!empty($contact['twitter'])): ?><a href="<?php echo $contact['twitter']; ?>"><i class="fab fa-twitter"></i></a><?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="products.php">Our Products</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Our Services</h4>
                <ul class="footer-links">
                    <li><a href="#">Agricultural Export</a></li>
                    <li><a href="#">Bulk Supply</a></li>
                    <li><a href="#">Quality Testing</a></li>
                    <li><a href="#">Global Logistics</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-title">Contact Info</h4>
                <ul class="footer-links footer-contact">
                    <?php if(!empty($contact['address'])): ?>
                    <li class="d-flex"><i class="fas fa-map-marker-alt mt-1"></i> <span><?php echo $contact['address']; ?></span></li>
                    <?php endif; ?>
                    <?php if(!empty($contact['phone'])): ?>
                    <li><i class="fas fa-phone-alt"></i> <?php echo $contact['phone']; ?></li>
                    <?php endif; ?>
                    <?php if(!empty($contact['email'])): ?>
                    <li><i class="fas fa-envelope"></i> <?php echo $contact['email']; ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="mb-0">Copyright &copy; <?php echo date('Y'); ?> VT-EXPORT. All Rights Reserved. Design By digitalwebtrackers.com</p>
        </div>
    </div>
</footer>