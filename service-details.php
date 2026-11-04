<?php
include 'config/connect.php'; 

// --- 1. INQUIRY FORM LOGIC (Self-Contained) ---
$inquiry_msg = "";
$inquiry_msg_type = "";

if (isset($_POST['submit_service_inquiry'])) {
    $q_name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $q_email   = mysqli_real_escape_string($conn, trim($_POST['email']));
    $q_phone   = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $q_subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $q_message = mysqli_real_escape_string($conn, trim($_POST['message']));

    if (!empty($q_name) && !empty($q_email) && !empty($q_phone)) {
        $ins_quote = "INSERT INTO inquiries (name, email, phone, subject, message, status) 
                      VALUES ('$q_name', '$q_email', '$q_phone', '$q_subject', '$q_message', 0)";
        if (mysqli_query($conn, $ins_quote)) {
            $inquiry_msg = "Thank you! Your service inquiry has been submitted.";
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
// ----------------------------------------------

// Fetch Service by Slug URL
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
if(empty($slug)){
    header("Location: services.php");
    exit;
}

$serv_query = mysqli_query($conn, "SELECT * FROM services WHERE slug_url = '$slug'");
if(mysqli_num_rows($serv_query) == 0){
    header("Location: services.php");
    exit;
}
$service = mysqli_fetch_assoc($serv_query);

// Set Page Title for Breadcrumb and Meta
$pageTitle = $service['service_name'];
$meta_title = $service['service_name'] . " | VT Export Services";

// Image Path Logic
$db_img = $service['img_path'];
$s_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-service-detail.jpg';
if(!empty($db_img) && !file_exists($s_img)) { $s_img = 'admin/' . $db_img; }

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<style>
/* ================= PREMIUM SERVICE DETAILS STYLING ================= */
:root {
    --primary-blue: #0a2540;
    --accent-gold: #d4af37;
    --bg-light: #f8f9fc;
    --text-muted: #555555;
}

.service-detail-section {
    padding: 80px 0;
    background-color: var(--bg-light);
}

/* Left Column Unified Card */
.service-content-wrapper {
    background: #ffffff;
    padding: 45px;
    border-radius: 12px;
    border: 1px solid #eaeaea;
    box-shadow: 0 5px 25px rgba(10, 37, 64, 0.04);
}

.service-main-title {
    color: var(--primary-blue);
    font-weight: 800;
    font-size: clamp(2rem, 3vw, 2.5rem);
    margin-bottom: 20px;
    line-height: 1.2;
}

/* Image Frame Setup */
.service-image-box {
    background: var(--bg-light);
    border: 1px solid #eaeaea;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    margin-bottom: 35px;
}

.service-main-img {
    width: 100%;
    max-height: 450px;
    object-fit: contain;
    /* Pura image safely dikhega */
    border-radius: 6px;
}

.service-content-body {
    font-size: 1.05rem;
    color: var(--text-muted);
    line-height: 1.8;
}

.service-content-body p {
    margin-bottom: 20px;
}

.service-content-body h2,
.service-content-body h3,
.service-content-body h4 {
    color: var(--primary-blue);
    font-weight: 700;
    margin-top: 30px;
    margin-bottom: 15px;
}

.service-content-body ul {
    padding-left: 20px;
    margin-bottom: 20px;
}

.service-content-body ul li {
    margin-bottom: 12px;
    color: var(--text-muted);
}

/* Sidebar Widgets */
.widget-box {
    background: #ffffff;
    padding: 35px 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    border: 1px solid #eaeaea;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
}

.widget-title {
    color: var(--primary-blue);
    font-size: 1.3rem;
    font-weight: 800;
    border-bottom: 2px solid #f1f3f5;
    padding-bottom: 12px;
    margin-bottom: 20px;
    position: relative;
}

.widget-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 50px;
    height: 2px;
    background: var(--accent-gold);
}

/* Inquiry Form Styling */
.widget-form .form-control {
    background: #f8f9fc;
    border: 1px solid #e2e8f0;
    padding: 12px 15px;
    margin-bottom: 15px;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #444;
}

.widget-form .form-control:focus {
    border-color: var(--accent-gold);
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.15);
    background: #ffffff;
}

.btn-widget-submit {
    background: var(--primary-blue);
    color: #ffffff;
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 6px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.btn-widget-submit:hover {
    background: var(--accent-gold);
    color: var(--primary-blue);
}

/* Dark Contact Widget */
.dark-widget {
    background: linear-gradient(135deg, var(--primary-blue) 0%, #153c66 100%);
    color: #ffffff;
    border-top: 4px solid var(--accent-gold);
}

.dark-widget .widget-title {
    color: #ffffff;
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

.widget-contact-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget-contact-list li {
    font-size: 1.05rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.widget-contact-list li i {
    color: var(--accent-gold);
    font-size: 1.2rem;
    margin-right: 15px;
    width: 25px;
    text-align: center;
}
</style>

<section class="service-detail-section">
    <div class="container">
        <div class="row g-5">

            <!-- Left Side: Unified Service Content Card -->
            <div class="col-lg-8">
                <div class="service-content-wrapper">

                    <h2 class="service-main-title"><?php echo htmlspecialchars($service['service_name']); ?></h2>
                    <div style="width: 60px; height: 3px; background: var(--accent-gold); margin-bottom: 30px;"></div>

                    <div class="service-image-box">
                        <img src="<?php echo $s_img; ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>"
                            class="service-main-img">
                    </div>

                    <div class="service-content-body">
                        <?php 
                        if(!empty($service['long_desc'])) {
                            echo htmlspecialchars_decode($service['long_desc']); 
                        } else {
                            echo "<p>Detailed description for this premium service is currently being updated. Please check back later or contact our team for more information.</p>";
                        }
                        ?>
                    </div>

                </div>
            </div>

            <!-- Right Side: Sidebar Widgets -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">

                    <!-- Quick Inquiry Form Widget -->
                    <div class="widget-box">
                        <h3 class="widget-title">Inquire About This Service</h3>
                        <p style="color: #666; font-size: 0.95rem; margin-bottom: 20px;">Fill out the form below and our
                            B2B experts will contact you shortly.</p>

                        <?php if (!empty($inquiry_msg)): ?>
                        <div class="alert alert-<?php echo $inquiry_msg_type; ?> py-2 small fw-bold">
                            <?php echo $inquiry_msg; ?>
                        </div>
                        <?php endif; ?>

                        <form action="" method="POST" class="widget-form">
                            <input type="hidden" name="subject"
                                value="Inquiry for <?php echo htmlspecialchars($service['service_name']); ?>">

                            <input type="text" name="name" class="form-control" placeholder="Your Name *" required>
                            <input type="email" name="email" class="form-control" placeholder="Business Email *"
                                required>
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number *" required>
                            <textarea name="message" class="form-control" rows="4" placeholder="How can we help you? *"
                                required></textarea>

                            <button type="submit" name="submit_service_inquiry" class="btn-widget-submit">Submit Request
                                <i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>

                    <!-- Need Help Widget -->
                    <div class="widget-box dark-widget">
                        <h3 class="widget-title">Need Immediate Help?</h3>
                        <p style="color: rgba(255,255,255,0.8); margin-bottom: 25px;">Contact our support team directly
                            for faster assistance with industrial exports.</p>

                        <?php 
                        $contact_widget_query = mysqli_query($conn, "SELECT phone, email FROM contacts ORDER BY id DESC LIMIT 1");
                        $c_widget = mysqli_fetch_assoc($contact_widget_query);
                        ?>
                        <ul class="widget-contact-list">
                            <?php if(!empty($c_widget['phone'])): ?>
                            <li><i class="fas fa-phone-alt"></i>
                                <a href="tel:<?php echo $c_widget['phone']; ?>"
                                    style="color: #fff; text-decoration: none;"><?php echo $c_widget['phone']; ?></a>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($c_widget['email'])): ?>
                            <li><i class="fas fa-envelope"></i>
                                <a href="mailto:<?php echo $c_widget['email']; ?>"
                                    style="color: #fff; text-decoration: none;"><?php echo $c_widget['email']; ?></a>
                            </li>
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