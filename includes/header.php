<?php

require_once 'config/connect.php'; 

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
<div class="topbar d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex gap-4">
                    <?php if(!empty($contact['phone'])): ?>
                    <span><i class="fas fa-phone-alt me-2 text-warning"></i> <a href="tel:<?php echo $contact['phone']; ?>"><?php echo $contact['phone']; ?></a></span>
                    <?php endif; ?>
                    
                    <?php if(!empty($contact['email'])): ?>
                    <span><i class="fas fa-envelope me-2 text-warning"></i> <a href="mailto:<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></a></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 text-end social-icons">
                <?php if(!empty($contact['facebook'])): ?>
                <a href="<?php echo $contact['facebook']; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <?php endif; ?>
                <?php if(!empty($contact['instagram'])): ?>
                <a href="<?php echo $contact['instagram']; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <?php endif; ?>
                <?php if(!empty($contact['linkdin'])): ?>
                <a href="<?php echo $contact['linkdin']; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar Section -->
<header id="main-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Dynamic Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="admin/uploads/<?php echo $logo_url; ?>" alt="VT Export Premium Business Logo" title="VT Export">
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars fs-1 text-primary-blue"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="products.php" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Our Products
                        </a>
                        <ul class="dropdown-menu border-0 shadow" aria-labelledby="productsDropdown">
                            <?php 
                            if(mysqli_num_rows($cat_query) > 0) {
                                while($cat = mysqli_fetch_assoc($cat_query)) {
                                    echo '<li><a class="dropdown-item" href="category.php?slug='.$cat['slug_url'].'">'.$cat['categories'].'</a></li>';
                                }
                            }
                            ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="products.php">View All Products</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Our Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blogs.php">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Our Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-4">
                    <i class="fas fa-search search-icon" aria-label="Search" title="Search"></i>
                    <a href="quote.php" class="btn btn-quote text-decoration-none">Get a Quote <i class="fas fa-arrow-right ms-1"></i></a>
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
                // THE FIX: Body ko utni hi padding do jitni header ki height hai taaki content jump na ho
                document.body.style.paddingTop = headerHeight + 'px'; 
            } else {
                header.classList.remove('is-sticky');
                // Padding wapas normal
                document.body.style.paddingTop = '0';
            }
        });
    });
</script>