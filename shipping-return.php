<?php
include('config/connect.php');

$pageTitle = "Shipping & Delivery Policy | VT Export";
$meta_description = "Learn about domestic and international shipping options for premium industrial hardware and pipe fittings by VT Export.";
$meta_key = "shipping policy, delivery policy, vt export, industrial export, sea freight";

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
                    <h1 class="policy-title">Shipping & Delivery Policy</h1>
                    <p class="text-muted mb-4 border-bottom pb-4"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>
                    
                    <h3 class="policy-heading">Shipping Logistics</h3>
                    
                    <h4 class="policy-subheading">1. Order Processing & Manufacturing</h4>
                    <p>Standard B2B hardware orders are processed and prepared for dispatch within <strong>7 to 10 business days</strong>. Custom investment casting components or bulk container exports require custom lead times ranging from 3 to 6 weeks based on tooling and production schedules.</p>

                    <h4 class="policy-subheading">2. International Sea & Air Freight</h4>
                    <p>We supply globally via major seaports in India. We offer multiple INCOTERMS such as <strong>FOB, CIF, and EXW</strong>. Transit times vary depending on the destination port and shipping lines chosen (usually 15-45 days for sea freight).</p>

                    <h4 class="policy-subheading">3. Customs, Duties & Taxes</h4>
                    <p>Unless shipped under DDP terms, the buyer is fully responsible for destination country customs duties, import clearance fees, and local taxes. <strong>VT Export</strong> provides all mandatory Indian export compliance documentation, commercial invoices, packing lists, and Certificates of Origin.</p>

                    <h3 class="policy-heading" style="margin-top: 50px;">Logistics Liabilities</h3>
                    
                    <h4 class="policy-subheading">1. Freight Damage</h4>
                    <p>Goods are securely packed in export-quality wooden crates, pallets, or custom corrugated boxes. Responsibility for freight damage transfers to the buyer or insurance provider once the container is loaded onboard the vessel (FOB terms).</p>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar -->
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
                        <i class="fa-solid fa-ship" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 20px;"></i>
                        <h4 style="font-weight: 800; font-size: 1.3rem; margin-bottom: 10px;">Export Inquiry?</h4>
                        <p style="font-size: 0.95rem; color: rgba(255,255,255,0.8); margin-bottom: 20px;">Get reliable global shipping quotes for wholesale industrial commodities.</p>
                        <a href="contact" class="cta-btn">Request Quote</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>