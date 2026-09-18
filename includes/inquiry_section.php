<?php
$contact_query = mysqli_query($conn, "SELECT map, address, phone, email FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);

$map_url = !empty($contact['map']) ? $contact['map'] : 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83923192868!2d77.06889754725779!3d28.52758200617607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin';
?>

<section class="inquiry-section">
    <div class="container">
        <div class="inquiry-header">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Get In Touch</span>
            <h3>Request a Callback or Quote</h3>
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
                    <h4>Send Your Inquiry</h4>
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