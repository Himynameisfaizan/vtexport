<?php
include('config/connect.php');

$pageTitle = "Privacy Policy | VT Export";
$meta_description = "Read the Privacy Policy of VT Export regarding personal data protection for our industrial components, pipe fittings, and metal casting products.";
$meta_key = "privacy policy, vt export, industrial components, pipe fittings, metal casting, b2b export";

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<style>
    :root {
        --primary-blue: #0a2540;
        --accent-gold: #d4af37;
        --bg-light: #f8f9fc;
        --text-muted: #555555;
    }
    .policy-section { background-color: var(--bg-light); padding: 80px 0; }
    .policy-content { background: #ffffff; padding: 50px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 5px 25px rgba(10,37,64,0.04); }
    .policy-title { color: var(--primary-blue); font-weight: 800; font-size: 2.2rem; margin-bottom: 10px; }
    .policy-heading { color: var(--accent-gold); font-size: 1.3rem; font-weight: 700; margin-top: 40px; margin-bottom: 15px; }
    .policy-content p, .policy-content ul li { color: var(--text-muted); line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px; }
    .sidebar-widget { background: #ffffff; padding: 35px 30px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #eaeaea; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
    .widget-title { color: var(--primary-blue); font-size: 1.3rem; font-weight: 800; border-bottom: 2px solid #f1f3f5; padding-bottom: 12px; margin-bottom: 20px; position: relative; }
    .widget-title::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 50px; height: 2px; background: var(--accent-gold); }
    .search-flex { display: flex; }
    .search-flex input { width: 100%; border: 2px solid #eaeaea; padding: 10px 15px; border-radius: 6px 0 0 6px; outline: none; }
    .search-flex button { background: var(--primary-blue); border: none; padding: 0 20px; color: #fff; border-radius: 0 6px 6px 0; transition: 0.3s; }
    .cat-list-widget { list-style: none; padding: 0; margin: 0; }
    .cat-list-widget li { border-bottom: 1px solid #f1f3f5; }
    .cat-list-widget li:last-child { border-bottom: none; }
    .cat-list-widget a { display: flex; justify-content: space-between; padding: 12px 0; color: var(--text-muted); text-decoration: none; font-weight: 500; transition: 0.3s; }
    .cat-list-widget a:hover { color: var(--accent-gold); padding-left: 10px; }
    .cta-widget { background: linear-gradient(135deg, var(--primary-blue) 0%, #153c66 100%); color: #fff; text-align: center; padding: 40px 25px; border-radius: 10px; border-top: 4px solid var(--accent-gold); }
    .cta-btn { display: inline-block; background: var(--accent-gold); color: var(--primary-blue); padding: 12px 30px; border-radius: 30px; font-weight: 700; text-transform: uppercase; text-decoration: none; transition: 0.3s; width: 100%; margin-bottom: 15px;}
    .cta-btn:hover { background: #fff; color: var(--primary-blue); }
</style>

<section class="policy-section">
    <div class="container">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <div class="policy-content">
                    <h1 class="policy-title">Privacy Policy</h1>
                    <p class="text-muted mb-4 border-bottom pb-4"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <p>Welcome to <strong>VT Export</strong>. We respect your privacy and are committed to protecting your personal data. This Privacy Policy outlines how we collect, use, process, and safeguard your information when you visit our website, inquire about our premium B2B products—including industrial hardware, pipe fittings, and marine castings—or use our services.</p>

                    <h4 class="policy-heading">1. Information We Collect</h4>
                    <p>To provide you with a seamless and secure B2B wholesale inquiry experience, we collect:</p>
                    <ul>
                        <li><strong>Personal & Business Information:</strong> Name, email address, phone number, company name, and job title.</li>
                        <li><strong>Logistics Information:</strong> Delivery address, port of destination, and specific shipping requirements for international freight.</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, time zone setting, and platform details when browsing our catalog.</li>
                        <li><strong>Trade Compliance Data:</strong> For bulk and wholesale export orders, we may collect business credentials, tax IDs, or import licenses as required by international trade regulations.</li>
                    </ul>

                    <h4 class="policy-heading">2. How We Use Your Information</h4>
                    <ul>
                        <li>To process, manufacture, pack, and fulfill your industrial and hardware orders.</li>
                        <li>To communicate regarding quotations, shipping updates, customs documentation, and support.</li>
                        <li>To comply with export regulations and legal trade obligations globally.</li>
                    </ul>

                    <h4 class="policy-heading">3. Third-Party Data Sharing</h4>
                    <p>We do not sell or trade your data. Trusted third parties assist us in our export operations, including:</p>
                    <ul>
                        <li><strong>Logistics & Freight Partners:</strong> Shipping lines and freight forwarders required to deliver your containers safely.</li>
                        <li><strong>Regulatory Authorities:</strong> Government or customs bodies when legally required for export compliance.</li>
                    </ul>

                    <h4 class="policy-heading">4. Data Security</h4>
                    <p>We implement robust technical and physical security measures to protect your commercial data against unauthorized access, disclosure, or misuse during the quotation and ordering process.</p>

                    <h4 class="policy-heading">5. Contact Us</h4>
                    <div class="p-4 mt-4 rounded" style="background-color: #f1f3f5; border-left: 4px solid var(--accent-gold);">
                        <p class="mb-2"><strong style="color: var(--primary-blue); font-size: 1.1rem;">VT Export</strong></p>
                        <p class="mb-2"><strong>Specialization:</strong> Global Exporters of Industrial Hardware, Pipe Fittings & Investment Castings</p>
                        <p class="mb-2"><strong>Phone:</strong> +91-8882623545</p>
                        <p class="mb-0"><strong>Email:</strong> info@vtexport.in</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Search Products</h5>
                        <form action="products.php" method="GET" class="search-flex">
                            <input type="text" name="search" placeholder="Search industrial parts..." required>
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <div class="sidebar-widget">
                        <h5 class="widget-title">Categories</h5>
                        <ul class="cat-list-widget">
                            <?php
                            $catQuery = mysqli_query($conn, "SELECT categories, slug_url FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 6");
                            if (mysqli_num_rows($catQuery) > 0) {
                                while($catRow = mysqli_fetch_assoc($catQuery)) {
                            ?>
                            <li>
                                <a href="category/<?= urlencode($catRow['slug_url']) ?>">
                                    <span><i class="fa-solid fa-folder-open me-2" style="color: var(--accent-gold);"></i> <?= htmlspecialchars($catRow['categories']) ?></span>
                                    <i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i>
                                </a>
                            </li>
                            <?php 
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="cta-widget">
                        <i class="fa-solid fa-box-open" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 800; font-size: 1.3rem; margin-bottom: 10px;">Bulk Export Orders?</h4>
                        <p style="font-size: 0.95rem; color: rgba(255,255,255,0.8); margin-bottom: 20px;">Get premium quality export-grade industrial components at wholesale pricing.</p>
                        <a href="contact" class="cta-btn">Request Quote</a>
                        <a href="tel:+918882623545" style="color: var(--accent-gold); text-decoration: none; font-weight: 600;"><i class="fa-solid fa-phone me-1"></i> +91-8882623545</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>