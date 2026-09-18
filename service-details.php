<?php
require_once 'config/connect.php'; 

// Fetch Service by Slug URL
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if(empty($slug)){
    header("Location: services.php");
    exit;
}

// Database mein id ki jagah slug_url check kar rahe hain
$serv_query = mysqli_query($conn, "SELECT * FROM services WHERE slug_url = '$slug'");
if(mysqli_num_rows($serv_query) == 0){
    header("Location: services.php");
    exit;
}
$service = mysqli_fetch_assoc($serv_query);

// Set Page Title for Breadcrumb
$pageTitle = $service['service_name'];

// Image Path Logic
$db_img = $service['img_path'];
$s_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-service-detail.jpg';
if(!empty($db_img) && !file_exists($s_img)) { $s_img = 'admin/' . $db_img; }

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<section class="service-detail-section">
    <div class="container">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <img src="<?php echo $s_img; ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>" class="service-main-img">
                
                <h2 style="color: #0a2540; font-weight: 800; font-size: 2.5rem; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($service['service_name']); ?>
                </h2>
                
                <div class="service-content-body">
                 <?php 
                    if(!empty($service['long_desc'])) {
                        echo htmlspecialchars_decode($service['long_desc']); 
                    } else {
                        echo "<p>Detailed description for this service is currently being updated.</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- Right Side: Sidebar Widgets -->
            <div class="col-lg-4">
                <div class="service-sidebar">
                    
                    <!-- Quick Inquiry Form Widget -->
                    <div class="widget-box">
                        <h3 class="widget-title">Inquire About This Service</h3>
                        <p style="color: #666; font-size: 0.95rem; margin-bottom: 20px;">Fill out the form below and our experts will contact you shortly.</p>
                        
                        <form action="submit_inquiry.php" method="POST" class="widget-form">
                            <!-- Pre-filling the subject with the service name -->
                            <input type="hidden" name="subject" value="Inquiry for <?php echo htmlspecialchars($service['service_name']); ?>">
                            
                            <input type="text" name="name" class="form-control" placeholder="Your Name *" required>
                            <input type="email" name="email" class="form-control" placeholder="Email Address *" required>
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number *" required>
                            <textarea name="message" class="form-control" rows="4" placeholder="How can we help you? *" required></textarea>
                            
                            <button type="submit" name="submit_inquiry" class="btn-widget-submit">Submit Request</button>
                        </form>
                    </div>

                    <!-- Need Help Widget -->
                    <div class="widget-box" style="background: #0a2540; color: #fff;">
                        <h3 class="widget-title" style="color: #fff; border-bottom-color: #d4af37;">Need Immediate Help?</h3>
                        <p style="color: rgba(255,255,255,0.8); margin-bottom: 25px;">Contact our support team directly for faster assistance.</p>
                        
                        <?php 
                        // Fetch contact info for widget[cite: 2]
                        $contact_widget_query = mysqli_query($conn, "SELECT phone, email FROM contacts ORDER BY id DESC LIMIT 1");
                        $c_widget = mysqli_fetch_assoc($contact_widget_query);
                        ?>
                        <ul class="widget-contact-list">
                            <?php if(!empty($c_widget['phone'])): ?>
                            <li style="color: #fff;"><i class="fas fa-phone-alt"></i> <?php echo $c_widget['phone']; ?></li>
                            <?php endif; ?>
                            
                            <?php if(!empty($c_widget['email'])): ?>
                            <li style="color: #fff;"><i class="fas fa-envelope"></i> <?php echo $c_widget['email']; ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<?php 
include 'includes/footer.php'; 
?>