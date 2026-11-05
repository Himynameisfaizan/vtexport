<?php
include('config/connect.php');

$pageTitle = "Terms & Conditions | VT Export";
$meta_description = "Read the terms and conditions of VT Export for purchasing industrial components, hardware, and pipe fittings globally.";
$meta_key = "terms and conditions, vt export, export terms, industrial hardware terms";

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<style>
    /* Styling reused from Privacy Policy for consistency */
    :root { --primary-blue: #0a2540; --accent-gold: #d4af37; --bg-light: #f8f9fc; --text-muted: #555555; }
    .policy-section { background-color: var(--bg-light); padding: 80px 0; }
    .policy-content { background: #ffffff; padding: 50px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 5px 25px rgba(10,37,64,0.04); }
    .policy-title { color: var(--primary-blue); font-weight: 800; font-size: 2.2rem; margin-bottom: 10px; }
    .policy-heading { color: var(--accent-gold); font-size: 1.1rem; font-weight: 700; margin-top: 30px; margin-bottom: 10px; }
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
                    <h1 class="policy-title">Terms & Conditions</h1>
                    <p class="text-muted mb-4 border-bottom pb-4"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <h4 class="policy-heading">1. Introduction</h4>
                    <p>Welcome to <strong>VT Export</strong>. By browsing our website and inquiring about our industrial components, hardware, and pipe fittings, you agree to abide by these B2B Terms & Conditions.</p>

                    <h4 class="policy-heading">2. Product Specifications & Tolerances</h4>
                    <p>We manufacture and export industrial grade components. Minor variations in casting finish, weight tolerances, and threading density may occur within acceptable ISO/DIN manufacturing standards and do not constitute a defect.</p>

                    <h4 class="policy-heading">3. Pricing and Quotations</h4>
                    <p>All prices quoted are strictly B2B and depend on order volume, raw material market rates, and selected shipping INCOTERMS. Quotes provided are valid for 15 days unless explicitly stated otherwise.</p>

                    <h4 class="policy-heading">4. Intellectual Property & Drawings</h4>
                    <p>Any custom technical drawings, molds, or specifications provided by the client will remain confidential. However, VT Export retains the rights to its proprietary manufacturing processes and catalog designs.</p>

                    <h4 class="policy-heading">5. Governing Law</h4>
                    <p>Any disputes arising from commercial exports or website usage shall be governed by the laws of India and subject to local jurisdiction in Rajkot, Gujarat.</p>

                    <h4 class="policy-heading">6. Contact Information</h4>
                    <div class="p-4 mt-4 rounded" style="background-color: #f1f3f5; border-left: 4px solid var(--accent-gold);">
                        <p class="mb-2"><strong style="color: var(--primary-blue); font-size: 1.1rem;">VT Export</strong></p>
                        <p class="mb-2"><strong>Email:</strong> info@vtexport.in</p>
                        <p class="mb-0"><strong>Phone:</strong> +91-8882623545</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar Widgets -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Search Products</h5>
                        <form action="products.php" method="GET" class="search-flex">
                            <input type="text" name="search" placeholder="Search..." required>
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
                        <i class="fa-solid fa-briefcase" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 800; font-size: 1.3rem; margin-bottom: 10px;">Wholesale Inquiries</h4>
                        <p style="font-size: 0.95rem; color: rgba(255,255,255,0.8); margin-bottom: 20px;">Get in touch with our B2B experts for bulk orders and export pricing.</p>
                        <a href="contact" class="cta-btn">Request Quote</a>
                        <a href="tel:+918882623545" style="color: var(--accent-gold); text-decoration: none; font-weight: 600;"><i class="fa-solid fa-phone me-1"></i> +91-8882623545</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>