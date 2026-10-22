<?php
require_once 'config/connect.php';

// Active page detect karne ka logic
$current_page = basename($_SERVER['PHP_SELF']);

// --- DYNAMIC SEO FETCHING LOGIC ---
$db_meta_query = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='$current_page'");
$db_meta = mysqli_fetch_assoc($db_meta_query);

$db_schema_query = mysqli_query($conn, "SELECT schema_markup FROM page_schemas WHERE page_url='$current_page'");
$db_schema = mysqli_fetch_assoc($db_schema_query);

$final_meta_title = !empty($meta_title) ? $meta_title : ($db_meta['meta_title'] ?? 'VT Export - Premium Solutions');
$final_meta_desc = !empty($meta_desc) ? $meta_desc : ($db_meta['meta_desc'] ?? 'VT Export is a global leader in premium exports.');
$final_meta_key = !empty($meta_key) ? $meta_key : ($db_meta['meta_key'] ?? 'VT Export, Premium products, Global Export');
$final_schema = !empty($custom_schema) ? $custom_schema : ($db_schema['schema_markup'] ?? '');
// ----------------------------------

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

    <!-- Dynamic Meta Tags -->
    <title><?php echo htmlspecialchars($final_meta_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($final_meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($final_meta_key); ?>">
    <?php
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $clean_uri = strtok($_SERVER['REQUEST_URI'], '?');

    $final_canonical = isset($custom_canonical) ? $custom_canonical : ($protocol . $host . $clean_uri);
    ?>

    <!-- SEO Canonical Tag -->
    <link rel="canonical" href="<?php echo htmlspecialchars($final_canonical); ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/style/include.css">
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/about.css">
    <link rel="stylesheet" href="assets/style/product.css">
    <link rel="stylesheet" href="assets/style/service.css">
    <link rel="stylesheet" href="assets/style/blog.css">
    <link rel="stylesheet" href="assets/style/gallery.css">
    <link rel="stylesheet" href="assets/style/contact.css">
    <base href="http://localhost/projects/vtexport/">
    <!-- Basic Organization Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "VT Export",
        "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>",
        "logo": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/admin/' . $logo_url; ?>",
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

    <!-- Dynamic Page Level Schema -->
    <?php if (!empty($final_schema)): ?>
    <?php echo $final_schema; ?>
    <?php endif; ?>

    <style>
    /* White Gap Fix */
    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Topbar Reset */
    .topbar {
        margin: 0;
        background: #0a2540;
        color: #fff;
        padding: 8px 0;
        font-size: 0.85rem;
    }

    /* Search Box Design */
    .search-collapse-area {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        z-index: 1030;
        background: #ffffff;
        border-top: 1px solid #eaeaea;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
    </style>
</head>

<body>

    <!-- Topbar Section -->
    <div class="topbar d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex gap-4">
                        <?php if (!empty($contact['phone'])): ?>
                        <span><i class="fas fa-phone-alt me-2" style="color: #d4af37;"></i> <a
                                href="tel:<?php echo $contact['phone']; ?>"
                                style="color: #fff; text-decoration: none;"><?php echo $contact['phone']; ?></a></span>
                        <?php endif; ?>

                        <?php if (!empty($contact['email'])): ?>
                        <span><i class="fas fa-envelope me-2" style="color: #d4af37;"></i> <a
                                href="mailto:<?php echo $contact['email']; ?>"
                                style="color: #fff; text-decoration: none;"><?php echo $contact['email']; ?></a></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 text-end social-icons">
                    <?php if (!empty($contact['facebook'])): ?>
                    <a href="<?php echo $contact['facebook']; ?>" target="_blank" class="text-white ms-3"><i
                            class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($contact['instagram'])): ?>
                    <a href="<?php echo $contact['instagram']; ?>" target="_blank" class="text-white ms-3"><i
                            class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($contact['linkdin'])): ?>
                    <a href="<?php echo $contact['linkdin']; ?>" target="_blank" class="text-white ms-3"><i
                            class="fab fa-linkedin-in"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar Section (sticky-top class added for native smooth sticking) -->
    <header id="main-header" class="sticky-top"
        style="background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.4s;">
        <nav class="navbar navbar-expand-lg py-3">
            <div class="container position-relative">
                <!-- Dynamic Logo -->
                <a class="navbar-brand" href="./">
                    <img src="admin/uploads/<?php echo $logo_url; ?>" alt="VT Export" style="max-height: 60px;">
                </a>

                <!-- Mobile Search Icon + Menu Toggler -->
                <div class="d-flex align-items-center d-lg-none gap-3">
                    <i class="fas fa-search fs-4" data-bs-toggle="collapse" data-bs-target="#searchBoxArea"
                        style="color: #0a2540; cursor: pointer;"></i>
                    <button class="navbar-toggler border-0 px-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarContent">
                        <i class="fas fa-bars fs-1" style="color: #0a2540;"></i>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == './') ? 'active' : ''; ?>"
                                href="./">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>"
                                href="about.php">About Us</a>
                        </li>

                        <style>
                        /* Desktop Hover Dropdown */
                        @media (min-width: 992px) {
                            .navbar-nav .dropdown:hover>.dropdown-menu {
                                display: block;
                                margin-top: 0;
                                animation: fadeInDropdown 0.25s ease;
                            }

                            .dropdown-submenu {
                                position: relative;
                            }

                            .dropdown-submenu>.dropdown-menu {
                                top: 0;
                                left: 100%;
                                margin-top: -6px;
                                margin-left: 0;
                                display: none;
                                min-width: 230px;
                                border-radius: 8px;
                                border: 1px solid #eaeaea;
                                box-shadow: 0 10px 30px rgba(10, 37, 64, 0.12);
                            }

                            .dropdown-submenu:hover>.dropdown-menu {
                                display: block;
                                animation: fadeInDropdown 0.25s ease;
                            }
                        }

                        @keyframes fadeInDropdown {
                            from {
                                opacity: 0;
                                transform: translateY(8px);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }

                        .custom-cat-menu {
                            min-width: 250px;
                            padding: 10px 0;
                            border-radius: 8px;
                            border: 1px solid #eaeaea;
                            box-shadow: 0 10px 30px rgba(10, 37, 64, 0.12);
                            border-top: 3px solid #d4af37;
                        }

                        .custom-cat-menu .dropdown-item {
                            padding: 10px 20px;
                            color: #0a2540;
                            font-weight: 500;
                            font-size: 0.95rem;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            transition: all 0.2s ease;
                        }

                        .custom-cat-menu .dropdown-item:hover {
                            background-color: #f8f9fc;
                            color: #d4af37;
                            padding-left: 25px;
                        }

                        .sub-toggle-icon {
                            font-size: 0.75rem;
                            color: #888;
                            transition: 0.2s;
                        }

                        .custom-cat-menu .dropdown-item:hover .sub-toggle-icon {
                            color: #d4af37;
                        }
                        </style>

                        <!-- Replace "Our Products" <li> inside navbar-nav with this -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo ($current_page == 'products.php' || $current_page == 'category.php') ? 'active' : ''; ?>"
                                href="#" id="productsDropdown">
                                Our Products
                            </a>
                            <ul class="dropdown-menu custom-cat-menu">
                                <?php
                                $nav_cat_query = mysqli_query($conn, "SELECT cate_id, categories, slug_url FROM categories WHERE status=1 ORDER BY id ASC");
                                if (mysqli_num_rows($nav_cat_query) > 0) {
                                    while ($nav_cat = mysqli_fetch_assoc($nav_cat_query)) {
                                        $parent_cid = $nav_cat['cate_id'];
                                        // Fetch active sub-categories for this category
                                        $nav_sub_query = mysqli_query($conn, "SELECT categories, slug_url FROM sub_categories WHERE parent_id='$parent_cid' AND status=1 ORDER BY id ASC");
                                        $has_sub = mysqli_num_rows($nav_sub_query) > 0;
                                ?>
                                <li class="<?php echo $has_sub ? 'dropdown-submenu' : ''; ?>">
                                    <a class="dropdown-item"
                                        href="category.php?slug=<?php echo urlencode($nav_cat['slug_url']); ?>">
                                        <span><?php echo htmlspecialchars($nav_cat['categories']); ?></span>
                                        <?php if ($has_sub): ?>
                                        <i class="fas fa-chevron-right sub-toggle-icon"></i>
                                        <?php endif; ?>
                                    </a>

                                    <?php if ($has_sub): ?>
                                    <ul class="dropdown-menu custom-cat-menu">
                                        <?php while ($nav_sub = mysqli_fetch_assoc($nav_sub_query)): ?>
                                        <li>
                                            <a class="dropdown-item"
                                                href="category.php?slug=<?php echo urlencode($nav_cat['slug_url']); ?>&sub=<?php echo urlencode($nav_sub['slug_url']); ?>">
                                                <?php echo htmlspecialchars($nav_sub['categories']); ?>
                                            </a>
                                        </li>
                                        <?php endwhile; ?>
                                        <li>
                                            <a class="dropdown-item fw-bold"
                                                style="color: #d4af37; border-bottom: 1px solid #eee;"
                                                href="category.php?slug=<?php echo urlencode($nav_cat['slug_url']); ?>">
                                                All <?php echo htmlspecialchars($nav_cat['categories']); ?>
                                            </a>
                                        </li>
                                    </ul>
                                    <?php endif; ?>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'services.php' || $current_page == 'service-details.php') ? 'active' : ''; ?>"
                                href="services.php">Our Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'blogs.php' || $current_page == 'blog-details.php') ? 'active' : ''; ?>"
                                href="blogs.php">Blogs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>"
                                href="gallery.php">Our Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>"
                                href="contact.php">Contact Us</a>
                        </li>
                    </ul>

                    <!-- Desktop Search Icon & Quote Button -->
                    <div class="d-none d-lg-flex align-items-center gap-4 ms-lg-3 mt-3 mt-lg-0">
                        <i class="fas fa-search" data-bs-toggle="collapse" data-bs-target="#searchBoxArea"
                            style="color: #0a2540; font-size: 1.2rem; cursor: pointer; transition: 0.3s;"
                            onmouseover="this.style.color='#d4af37'" onmouseout="this.style.color='#0a2540'"></i>
                        <a href="quote.php" class="btn text-white fw-bold px-4 rounded-pill"
                            style="background: #d4af37; border: 2px solid #d4af37;">GET A QUOTE <i
                                class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Working Search Form Overlay -->
                <div class="collapse search-collapse-area" id="searchBoxArea">
                    <div class="container py-3">
                        <form action="products.php" method="GET" class="d-flex mx-auto search-form-header"
                            style="max-width: 600px;">
                            <input type="text" name="search" class="form-control" placeholder="Search for products..."
                                required
                                style="border: 2px solid #0a2540; border-right: none; border-radius: 30px 0 0 30px; padding: 12px 25px;">
                            <button type="submit" class="btn fw-bold px-4"
                                style="background: #0a2540; color: #fff; border: 2px solid #0a2540; border-radius: 0 30px 30px 0; transition: 0.3s;"
                                onmouseover="this.style.backgroundColor='#d4af37'; this.style.borderColor='#d4af37';"
                                onmouseout="this.style.backgroundColor='#0a2540'; this.style.borderColor='#0a2540';">Search</button>
                        </form>
                    </div>
                </div>

            </div>
        </nav>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Search Auto-Close Script on Scroll -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchBox = document.getElementById('searchBoxArea');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50 && searchBox.classList.contains('show')) {
                // Scroll karne par search box apne aap band ho jayega jisse glitch na aaye
                let bsCollapse = new bootstrap.Collapse(searchBox, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });
    </script>