<?php
include('config/connect.php');

$pageTitle = "Refund & Cancellation Policy | VT Export";
$meta_description = "Read the refund and cancellation policy of VT Export for industrial components, hardware, and marine casting products.";
$meta_key = "refund policy, cancellation policy, vt export, industrial export, pipe fittings";

include 'includes/header.php';
include 'includes/breadcrumb.php';
?>

<style>
    /* Styling reused from Privacy Policy for consistency */
    :root { --primary-blue: #0a2540; --accent-gold: #d4af37; --bg-light: #f8f9fc; --text-muted: #555555; }
    .policy-section { background-color: var(--bg-light); padding: 80px 0; }
    .policy-content { background: #ffffff; padding: 50px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 5px 25px rgba(10,37,64,0.04); }
    .policy-title { color: var(--primary-blue); font-weight: 800; font-size: 2.2rem; margin-bottom: 10px; }
    .policy-heading { color: var(--primary-blue); font-size: 1.4rem; font-weight: 800; margin-top: 40px; margin-bottom: 15px; border-bottom: 2px solid #f1f3f5; padding-bottom: 10px;}
    .policy-subheading { color: var(--accent-gold); font-size: 1.1rem; font-weight: 700; margin-top: 25px; margin-bottom: 10px; }
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
                    <h1 class="policy-title">Refund & Cancellation Policy</h1>
                    <p class="text-muted mb-4 border-bottom pb-4"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <p>At <strong>VT Export</strong>, we ensure the highest quality of our industrial components, including pipe fittings, flanges, and investment castings. Because we deal in heavy industrial and bulk hardware products for B2B export, our refund and cancellation policies are structured to comply with strict international trade standards.</p>

                    <h3 class="policy-heading">Cancellation Policy</h3>
                    
                    <h4 class="policy-subheading">1. Order Cancellation Before Production/Dispatch</h4>
                    <p>You may cancel your bulk order within <strong>24 to 48 hours</strong> of placing it, provided the custom manufacturing or port dispatch process has not commenced. Approved cancellations will be refunded subject to minor banking/processing deductions.</p>

                    <h4 class="policy-subheading">2. Cancellation After Dispatch</h4>
                    <p>Due to the heavy logistics and international shipping nature of industrial hardware, orders <strong>cannot be cancelled</strong> once they have been handed over to freight forwarders or loaded onto shipping vessels.</p>
                    
                    <h4 class="policy-subheading">3. Custom & Wholesale Orders</h4>
                    <p>For custom-casted components or specialized bulk container orders, cancellations are not permitted once casting and tooling have started. Advance payments for custom industrial orders remain non-refundable.</p>

                    <h3 class="policy-heading" style="margin-top: 50px;">Return and Refund Policy</h3>
                    
                    <h4 class="policy-subheading">1. Eligibility for Returns</h4>
                    <p>We <strong>do not accept general returns</strong> due to the complex logistics of international hardware supply. However, replacements or adjustments are applicable if:</p>
                    <ul>
                        <li>The components received significantly deviate from the agreed technical drawings or specifications.</li>
                        <li>The shipment sustained severe structural damage during transit that compromises the component's industrial utility.</li>
                    </ul>
                    <p><em>Note: You must notify us within <strong>5 business days of port delivery</strong> with detailed photographic proof and inspection reports.</em></p>

                    <h4 class="policy-subheading">2. Refund/Replacement Timeline</h4>
                    <p>Valid claims will be compensated either via credit note for future orders, replacement of defective parts in the next container, or a partial refund processed within <strong>15 to 20 business days</strong> after internal verification.</p>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    <div class="sidebar-widget">
                        <h5 class="widget-title">Search Products</h5>
                        <form action="products.php" method="GET" class="search-flex">
                            <input type="text" name="search" placeholder="Search components..." required>
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
                        <i class="fa-solid fa-headset" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 800; font-size: 1.3rem; margin-bottom: 10px;">Need Help?</h4>
                        <p style="font-size: 0.95rem; color: rgba(255,255,255,0.8); margin-bottom: 20px;">Contact our support team for order or logistics assistance.</p>
                        <a href="contact" class="cta-btn">Contact Us</a>
                        <a href="mailto:info@vtexport.in" style="color: var(--accent-gold); text-decoration: none; font-weight: 600;"><i class="fa-solid fa-envelope me-1"></i> info@vtexport.in</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>