<?php
require_once 'config/connect.php'; 

// Active page detect karne ka logic
$current_page = basename($_SERVER['PHP_SELF']);

$contact_query = mysqli_query($conn, "SELECT phone, email, facebook, instagram, twitter, linkdin FROM contacts ORDER BY id DESC LIMIT 1");
$contact = mysqli_fetch_assoc($contact_query);

$logo_query = mysqli_query($conn, "SELECT logo_path FROM logos WHERE location='header' AND is_active=1 ORDER BY id DESC LIMIT 1");
$logo_data = mysqli_fetch_assoc($logo_query);
$logo_url = !empty($logo_data['logo_path']) ? $logo_data['logo_path'] : 'assets/images/default-logo.png';

$cat_query = mysqli_query($conn, "SELECT categories, slug_url FROM categories WHERE status=1 ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $meta_title ?? 'VT Export - Premium Casting Solutions'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/style/include.css">
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/about.css">
    <link rel="stylesheet" href="assets/style/product.css">
    <link rel="stylesheet" href="assets/style/service.css">
    <link rel="stylesheet" href="assets/style/blog.css">
    <link rel="stylesheet" href="assets/style/gallery.css">
    <link rel="stylesheet" href="assets/style/contact.css">

    <!-- SEO JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "VT Export",
      "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>",
      "logo": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/' . $logo_url; ?>",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "<?php echo $contact['phone'] ?? ''; ?>",
        "contactType": "customer service"
      },
      "sameAs": [
        "<?php echo $contact['facebook'] ?? ''; ?>",
        "<?php echo $contact['instagram'] ?? ''; ?>",
        "<?php echo $contact['linkdin'] ?? ''; ?>"
      ]
    }
    </script>

</head>
<body>

<!-- Topbar Section -->
<div class="topbar d-none d-lg-block" style="background: #0a2540; color: #fff; padding: 8px 0; font-size: 0.85rem;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex gap-4">
                    <?php if(!empty($contact['phone'])): ?>
                    <span><i class="fas fa-phone-alt me-2" style="color: #d4af37;"></i> <a href="tel:<?php echo $contact['phone']; ?>" style="color: #fff; text-decoration: none;"><?php echo $contact['phone']; ?></a></span>
                    <?php endif; ?>
                    
                    <?php if(!empty($contact['email'])): ?>
                    <span><i class="fas fa-envelope me-2" style="color: #d4af37;"></i> <a href="mailto:<?php echo $contact['email']; ?>" style="color: #fff; text-decoration: none;"><?php echo $contact['email']; ?></a></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 text-end social-icons">
                <?php if(!empty($contact['facebook'])): ?>
                <a href="<?php echo $contact['facebook']; ?>" target="_blank" class="text-white ms-3"><i class="fab fa-facebook-f"></i></a>
                <?php endif; ?>
                <?php if(!empty($contact['instagram'])): ?>
                <a href="<?php echo $contact['instagram']; ?>" target="_blank" class="text-white ms-3"><i class="fab fa-instagram"></i></a>
                <?php endif; ?>
                <?php if(!empty($contact['linkdin'])): ?>
                <a href="<?php echo $contact['linkdin']; ?>" target="_blank" class="text-white ms-3"><i class="fab fa-linkedin-in"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar Section -->
<header id="main-header" style="background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.4s; position: relative;">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container position-relative">
            <!-- Dynamic Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="admin/uploads/<?php echo $logo_url; ?>" alt="VT Export" style="max-height: 60px;">
            </a>
            
            <!-- 🔥 FIX: Mobile Search Icon + Menu Toggler 🔥 -->
            <div class="d-flex align-items-center d-lg-none gap-3">
                <i class="fas fa-search fs-4" data-bs-toggle="collapse" data-bs-target="#searchBoxArea" style="color: #0a2540; cursor: pointer;"></i>
                <button class="navbar-toggler border-0 px-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <i class="fas fa-bars fs-1" style="color: #0a2540;"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="about.php">About Us</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo ($current_page == 'products.php' || $current_page == 'category.php') ? 'active' : ''; ?>" href="products.php" id="productsDropdown">
                            Our Products
                        </a>
                        <ul class="dropdown-menu">
                            <?php 
                            if(mysqli_num_rows($cat_query) > 0) {
                                while($cat = mysqli_fetch_assoc($cat_query)) {
                                    echo '<li><a class="dropdown-item" href="category.php?slug='.$cat['slug_url'].'">'.$cat['categories'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'services.php' || $current_page == 'service-details.php') ? 'active' : ''; ?>" href="services.php">Our Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'blogs.php' || $current_page == 'blog-details.php') ? 'active' : ''; ?>" href="blogs.php">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>" href="gallery.php">Our Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact Us</a>
                    </li>
                </ul>
                
                <!-- Desktop Search Icon & Quote Button -->
                <div class="d-none d-lg-flex align-items-center gap-4 ms-lg-3 mt-3 mt-lg-0">
                    <!-- 🔥 FIX: Desktop Working Search Icon 🔥 -->
                    <i class="fas fa-search" data-bs-toggle="collapse" data-bs-target="#searchBoxArea" style="color: #0a2540; font-size: 1.2rem; cursor: pointer; transition: 0.3s;" onmouseover="this.style.color='#d4af37'" onmouseout="this.style.color='#0a2540'"></i>
                    <a href="quote.php" class="btn text-white fw-bold px-4 rounded-pill" style="background: #d4af37; border: 2px solid #d4af37;">GET A QUOTE <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
            
            <!-- 🔥 FIX: Working Search Form Overlay (Works for Desktop & Mobile) 🔥 -->
            <div class="collapse search-collapse-area" id="searchBoxArea">
                <div class="container py-3">
                    <form action="products.php" method="GET" class="d-flex mx-auto search-form-header" style="max-width: 600px;">
                        <input type="text" name="search" class="form-control" placeholder="Search for products..." required style="border: 2px solid #0a2540; border-right: none; border-radius: 30px 0 0 30px; padding: 12px 25px;">
                        <button type="submit" class="btn fw-bold px-4" style="background: #0a2540; color: #fff; border: 2px solid #0a2540; border-radius: 0 30px 30px 0; transition: 0.3s;" onmouseover="this.style.backgroundColor='#d4af37'; this.style.borderColor='#d4af37';" onmouseout="this.style.backgroundColor='#0a2540'; this.style.borderColor='#0a2540';">Search</button>
                    </form>
                </div>
            </div>
            
        </div>
    </nav>
</header>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sticky Header Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const header = document.getElementById('main-header');
        const topbar = document.querySelector('.topbar');
        
        window.addEventListener('scroll', function() {
            const topbarHeight = topbar ? topbar.offsetHeight : 0;
            const headerHeight = header.offsetHeight;
            
            if (window.scrollY > topbarHeight) {
                header.classList.add('is-sticky');
                document.body.style.paddingTop = headerHeight + 'px'; 
            } else {
                header.classList.remove('is-sticky');
                document.body.style.paddingTop = '0';
            }
        });
    });
</script>