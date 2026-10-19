<?php
require_once 'config/connect.php'; 

// SEO & Page Title Setup
$pageTitle = "Furniture Fittings & Architectural Hardware";
$meta_title = "Premium Furniture Fittings & Architectural Hardware - VT Export";
$meta_desc = "Explore our exclusive range of heavy-duty furniture fittings, architectural hardware, modular kitchen accessories, and sliding door systems built for global export standards.";
$meta_key = "Furniture Fittings, Architectural Hardware, Modular Kitchen Accessories, Drawer Slides, Door Hinges, VT Export";

include 'includes/header.php';
include 'includes/breadcrumb.php';

// Fetch Contact Number for Call/Inquire buttons
$contact_query = mysqli_query($conn, "SELECT phone FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);
$phone_number = !empty($contact['phone']) ? $contact['phone'] : '+91-0000000000';

// Fetch Products specifically from "Furniture Fitting" category (cate_id = 74465)[cite: 28]
$furniture_cate_id = 74465;
$products_query = mysqli_query($conn, "SELECT * FROM products WHERE pro_cate = '$furniture_cate_id' AND status = 1 ORDER BY id DESC");
?>

<style>
    :root {
        --primary-blue: #0a2540;
        --accent-gold: #d4af37;
        --badge-color: #741b38;
        --bg-light: #f8f9fc;
        --text-muted: #555555;
    }

    /* Page Hero Banner */
    .fitting-hero {
        background: linear-gradient(rgba(10, 37, 64, 0.9), rgba(10, 37, 64, 0.85)), url('assets/images/default-fitting-hero.jpg');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        color: #ffffff;
        text-align: center;
    }
    .fitting-hero h1 { font-size: clamp(2.2rem, 4vw, 3rem); font-weight: 800; margin-bottom: 15px; }
    .fitting-hero p { font-size: 1.1rem; color: rgba(255, 255, 255, 0.8); max-width: 700px; margin: 0 auto; line-height: 1.7; }

    /* Main Section Styling */
    .fittings-section { padding: 90px 0; background-color: #ffffff; }

    /* Intro Split Section */
    .intro-box { background: var(--bg-light); border: 1px solid #eaeaea; border-radius: 12px; padding: 40px; margin-bottom: 70px; }
    .intro-box h2 { color: var(--primary-blue); font-weight: 800; font-size: 2rem; margin-bottom: 20px; }
    .intro-box p { color: var(--text-muted); font-size: 1.05rem; line-height: 1.8; margin-bottom: 0; }

    /* Product Cards (Grid Style matching Products page) */
    .prod-card {
        background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px;
        overflow: hidden; transition: all 0.4s ease; display: flex; flex-direction: column; height: 100%;
    }
    .prod-card:hover { box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08); border-color: #dcdcdc; transform: translateY(-5px); }
    
    .prod-img-wrapper { position: relative; height: 230px; width: 100%; overflow: hidden; background: #fff; }
    .prod-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .prod-card:hover .prod-img-wrapper img { transform: scale(1.08); }

    .export-badge {
        position: absolute; top: 12px; left: 12px; background-color: var(--badge-color);
        color: #ffffff; font-size: 0.75rem; font-weight: 600; padding: 5px 12px;
        border-radius: 20px; z-index: 2; box-shadow: 0 4px 10px rgba(116, 27, 56, 0.3);
    }

    .prod-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; text-align: left; }
    .prod-title { font-size: 1.15rem; font-weight: 700; color: var(--primary-blue); margin-bottom: 12px; }
    .prod-link { font-size: 0.9rem; color: #555; text-decoration: none; margin-bottom: 20px; font-weight: 500; display: inline-flex; align-items: center; transition: color 0.3s ease; }
    .prod-link i { font-size: 0.75rem; margin-left: 5px; transition: transform 0.3s ease; color: var(--accent-gold); }
    .prod-card:hover .prod-link { color: var(--badge-color); }
    .prod-card:hover .prod-link i { transform: translateX(4px); }

    .prod-actions { display: flex; gap: 12px; margin-top: auto; }
    .btn-call {
        width: 42px; height: 42px; border: 1px solid var(--badge-color); border-radius: 6px;
        display: flex; align-items: center; justify-content: center; color: var(--badge-color);
        text-decoration: none; transition: all 0.3s ease; flex-shrink: 0;
    }
    .btn-call i { font-size: 1.1rem; transform: rotate(90deg); }
    .btn-call:hover { background-color: var(--badge-color); color: #fff; }
    
    .btn-inquire {
        flex-grow: 1; height: 42px; background-color: #222222; color: #ffffff;
        border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease;
    }
    .btn-inquire:hover { background-color: var(--primary-blue); color: #ffffff; }

    /* Trust Section 1: Quality Assurance Features */
    .trust-features { background: var(--bg-light); padding: 80px 0; border-top: 1px solid #eaeaea; }
    .feature-box { text-align: center; padding: 30px; background: #fff; border-radius: 8px; border: 1px solid #eaeaea; height: 100%; transition: 0.3s; }
    .feature-box:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .feature-icon { font-size: 2.2rem; color: var(--accent-gold); width: 70px; height: 70px; background: rgba(212,175,55,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
    .feature-box h4 { color: var(--primary-blue); font-weight: 700; font-size: 1.15rem; margin-bottom: 12px; }
    .feature-box p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin: 0; }

    /* Trust Section 2: Global Export Compliance Banner */
    .compliance-banner {
        background: linear-gradient(rgba(10, 37, 64, 0.95), rgba(10, 37, 64, 0.95)), url('assets/images/default-compliance.jpg');
        background-size: cover; background-position: center; padding: 70px 0; color: #fff; text-align: center;
    }
    .compliance-banner h2 { font-weight: 800; font-size: 2.2rem; margin-bottom: 20px; color: #fff; }
    .compliance-banner p { color: rgba(255,255,255,0.85); max-width: 800px; margin: 0 auto 30px; line-height: 1.8; font-size: 1.05rem; }
    .compliance-stats { display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; margin-top: 40px; }
    .stat-item h3 { font-size: 2.5rem; font-weight: 800; color: var(--accent-gold); margin-bottom: 5px; }
    .stat-item p { font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin: 0; color: #fff; }
</style>

<!-- Hero Section -->
<section class="fitting-hero">
    <div class="container">
        <h1>Furniture Fittings & Architectural Hardware</h1>
        <p>Engineered for precision, durability, and modern aesthetics. Supplying world-class modular hardware solutions for global B2B markets.</p>
    </div>
</section>

<!-- Main Dynamic Product Grid Section -->
<section class="fittings-section">
    <div class="container">
        
        <!-- Corporate Intro Banner -->
        <div class="intro-box">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2>Engineered Excellence in Every Joint & Mechanism</h2>
                    <p>VT Export extends its manufacturing and export prowess into high-grade furniture fittings and architectural hardware. Designed to withstand rigorous daily wear while maintaining silent, seamless operation, our hardware components are trusted by top-tier furniture manufacturers worldwide.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="contact.php?product=Furniture+Fittings" class="btn" style="background: var(--accent-gold); color: var(--primary-blue); font-weight: 700; padding: 14px 30px; border-radius: 30px; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Request Bulk Catalog</a>
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <span style="color: var(--accent-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Catalog Showcase</span>
            <h2 style="color: var(--primary-blue); font-size: 2.3rem; font-weight: 800;">Furniture Fitting Products</h2>
            <div style="width: 60px; height: 3px; background: var(--accent-gold); margin: 15px auto;"></div>
        </div>

        <!-- Dynamic Product Cards Grid -->
        <div class="row g-4">
            <?php 
            if (mysqli_num_rows($products_query) > 0) {
                while ($prod = mysqli_fetch_assoc($products_query)) {
                    $db_img = $prod['pro_img'];
                    // Handling image paths flexibly
                    $p_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-product.jpg';
                    if (!empty($db_img) && !file_exists($p_img)) {
                        $p_img = 'admin/' . $db_img;
                    }
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="prod-card">
                    <div class="prod-img-wrapper">
                        <span class="export-badge">Export Grade</span>
                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>">
                            <img src="<?php echo $p_img; ?>" alt="<?php echo htmlspecialchars($prod['pro_name']); ?>">
                        </a>
                    </div>
                    
                    <div class="prod-body">
                        <h3 class="prod-title">
                            <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($prod['pro_name']); ?>
                            </a>
                        </h3>
                        
                        <a href="product-details.php?slug=<?php echo $prod['slug_url']; ?>" class="prod-link">
                            View Details <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <div class="prod-actions">
                            <a href="tel:<?php echo $phone_number; ?>" class="btn-call" title="Call Us">
                                <i class="fas fa-phone"></i>
                            </a>
                            <a href="contact.php?product=<?php echo urlencode($prod['pro_name']); ?>" class="btn-inquire">
                                Inquire Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center py-5">
                        <i class="fas id fa-box-open mb-3" style="font-size: 3rem; color: #ddd;"></i>
                        <h4 style="color: var(--primary-blue);">No Products Found in Furniture Fittings</h4>
                        <p class="text-muted">Products under this category are currently being updated.</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Trust Section 1: Quality Assurance Features -->
<section class="trust-features">
    <div class="container">
        <div class="text-center mb-5">
            <span style="color: var(--accent-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Why Trust Us</span>
            <h3 style="color: var(--primary-blue); font-weight: 800; font-size: 2.2rem;">Global Quality Assurance</h3>
            <div style="width: 50px; height: 3px; background: var(--accent-gold); margin: 15px auto;"></div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h4>Corrosion Resistant</h4>
                    <p>Tested rigorously via salt spray testing to ensure long-lasting rust-free performance.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-cogs"></i></div>
                    <h4>Cycle Tested</h4>
                    <p>Hardware mechanisms rated for over 80,000 smooth operating cycles.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-globe"></i></div>
                    <h4>Global Standards</h4>
                    <p>Compliant with international quality benchmarks for seamless modular installation.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                    <h4>Export Packaging</h4>
                    <p>Secure export-grade pallet packaging to prevent transit damage.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section 2: Global Compliance & Stats Banner -->
<section class="compliance-banner">
    <div class="container">
        <h2>Committed to International Export Excellence</h2>
        <p>Our infrastructure allows us to handle high-volume B2B orders with stringent quality checks at every level. We ensure timely container dispatches, complete documentation transparency, and robust customer assistance for international buyers.</p>
        <a href="contact.php" class="btn" style="background: var(--accent-gold); color: var(--primary-blue); font-weight: 700; padding: 12px 35px; border-radius: 30px; text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Partner With Us</a>
        
        <div class="compliance-stats">
            <div class="stat-item">
                <h3>15+</h3>
                <p>Years of Trust</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Quality Inspected</p>
            </div>
            <div class="stat-item">
                <h3>Global</h3>
                <p>Shipping Network</p>
            </div>
        </div>
    </div>
</section>

<?php 
include 'includes/inquiry_section.php'; 
include 'includes/footer.php'; 
?>