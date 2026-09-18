<?php
$pageTitle = "Contact Us";
require_once 'config/connect.php'; 
include 'includes/header.php';
include 'includes/breadcrumb.php';

$contact_query = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);

$product_inquiry = isset($_GET['product']) ? mysqli_real_escape_string($conn, $_GET['product']) : '';
$pre_subject = !empty($product_inquiry) ? "Inquiry for " . $product_inquiry : '';
?>

<section class="contact-page-section">
    <div class="container">
        
        <!-- INFO CARDS -->
        <div class="row g-4 mb-5 pb-4">
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card">
                    <div class="ci-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="ci-title">Head Office</h3>
                    <p class="ci-text"><?php echo !empty($contact['address']) ? nl2br(htmlspecialchars($contact['address'])) : 'No Address Available'; ?></p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card">
                    <div class="ci-icon"><i class="fas fa-phone-alt"></i></div>
                    <h3 class="ci-title">Call Us</h3>
                    <p class="ci-text">
                        <?php if(!empty($contact['phone'])) echo "Phone: " . htmlspecialchars($contact['phone']) . "<br>"; ?>
                        <?php if(!empty($contact['wp_number'])) echo "WhatsApp: " . htmlspecialchars($contact['wp_number']); ?>
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card">
                    <div class="ci-icon"><i class="fas fa-envelope"></i></div>
                    <h3 class="ci-title">Email Us</h3>
                    <p class="ci-text">
                        <?php if(!empty($contact['email'])) echo "Sales: " . htmlspecialchars($contact['email']) . "<br>"; ?>
                        <?php if(!empty($contact['working_hours'])) echo "<br><span style='color:#d4af37; font-weight:600;'>Hours:</span> " . htmlspecialchars($contact['working_hours']); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- FORM & MAP SPLIT -->
        <div class="row g-5 align-items-stretch">
            <!-- Left Side: Map[cite: 2] -->
            <div class="col-lg-6">
                <div class="map-box-contact">
                    <?php 
                    $map_src = !empty($contact['map']) ? $contact['map'] : 'https://www.google.com/maps/embed?pb=...'; 
                    ?>
                    <iframe src="<?php echo $map_src; ?>" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="col-lg-6">
                <div class="contact-form-wrapper">
                    <h3>Send a Message</h3>
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 30px;">Have a bulk inquiry or question? Drop us a message.</p>
                    
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
                                <input type="tel" name="phone" class="form-control-custom" placeholder="Phone Number *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="subject" class="form-control-custom" placeholder="Subject *" value="<?php echo htmlspecialchars($pre_subject); ?>" required>
                            </div>
                        </div>
                        <textarea name="message" class="form-control-custom" rows="5" placeholder="Your Message *" required></textarea>
                        
                        <button type="submit" name="submit_inquiry" class="btn-hero-primary" style="width: 100%; border-radius: 4px; padding: 14px;">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- FAQ SECTION -->
<section class="faq-section">
    <div class="container">
        <div class="text-center mb-5">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Support</span>
            <h2 style="color: #0a2540; font-size: clamp(2rem, 3vw, 2.5rem); font-weight: 800;">Frequently Asked Questions</h2>
            <div style="width: 60px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="contactFaq">
                    
                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingC1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseC1" aria-expanded="true">
                                How soon can I expect a reply to my inquiry?
                            </button>
                        </h2>
                        <div id="collapseC1" class="accordion-collapse collapse show" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                Our dedicated B2B sales team aims to respond to all inquiries within 24 business hours. If your request is urgent, please call our head office directly or connect with us on WhatsApp.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingC2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseC2" aria-expanded="false">
                                Do you provide samples for bulk export orders?
                            </button>
                        </h2>
                        <div id="collapseC2" class="accordion-collapse collapse" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                Yes, we provide product samples for quality testing to our serious international buyers. Please mention your sample requirement in the message box along with your company details.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3 border-0">
                        <h2 class="accordion-header" id="headingC3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseC3" aria-expanded="false">
                                Where is your manufacturing/processing unit located?
                            </button>
                        </h2>
                        <div id="collapseC3" class="accordion-collapse collapse" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                You can find our primary operational and head office details on the map above[cite: 2]. We also have multiple tie-up farms and processing centers across the country to ensure fresh sourcing.
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