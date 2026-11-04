<?php
$pageTitle = 'Explore Custom Pages | VT Export';
$meta_title = 'Locations & Custom Pages | VT Export';
$meta_desc = 'Explore location-specific pages, premium services, and industrial information published by VT Export.';
$meta_key = 'VT Export, locations, custom pages, premium quality, industrial hardware';

include 'config/connect.php';

// Fetch active custom pages from database
$customPages = [];
$sql = "SELECT page_title, breadcrumb_title, slug_url, meta_title, meta_desc
        FROM custom_pages
        WHERE status = 1 AND slug_url <> ''
        ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customPages[] = $row;
    }
}

function locationPageText($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

include 'includes/header.php';
// Agar breadcrumb file hai toh include karo
// include 'includes/breadcrumb.php'; 
?>

<style>
/* ================= PREMIUM THEME (VT EXPORT) ================= */
:root {
    --primary-blue: #0a2540;
    --accent-gold: #d4af37;
    --badge-color: #741b38;
    --card-bg: #ffffff;
    --text-muted: #555555;
    --body-bg: #f8f9fc;
}

body.location-page {
    background: var(--body-bg);
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-up {
    animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Premium VT Export Hero Section */
.location-hero {
    background: linear-gradient(rgba(10, 37, 64, 0.9), rgba(10, 37, 64, 0.85)), url('assets/images/default-fitting-hero.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
    padding: 100px 0;
    color: #ffffff;
    text-align: center;
}

.location-hero h1 {
    font-size: clamp(2.5rem, 4vw, 3.5rem);
    font-weight: 800;
    margin-bottom: 15px;
}

.location-hero p {
    color: rgba(255, 255, 255, 0.85);
    font-size: 1.1rem;
    max-width: 750px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Premium Card Layout */
.location-section {
    padding: 90px 0;
}

.premium-card {
    background: var(--card-bg);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(10, 37, 64, 0.05);
    transition: all 0.4s ease;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #eaeaea;
    position: relative;
}

.premium-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(10, 37, 64, 0.1);
    border-color: var(--accent-gold);
}

.card-img-wrapper {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
    background: #fff;
}

.card-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.premium-card:hover .card-img-wrapper img {
    transform: scale(1.08);
}

/* Export Grade Badge */
.export-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background-color: var(--badge-color);
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    z-index: 2;
    box-shadow: 0 4px 10px rgba(116, 27, 56, 0.3);
}

.card-content {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.premium-card h3 {
    color: var(--primary-blue);
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.premium-card p {
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
    display: -webkit-box;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-btn {
    display: inline-block;
    color: var(--primary-blue);
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    transition: color 0.3s ease;
}

.card-btn i {
    margin-left: 5px;
    color: var(--accent-gold);
    transition: transform 0.3s ease;
}

.premium-card:hover .card-btn {
    color: var(--badge-color);
}

.premium-card:hover .card-btn i {
    transform: translateX(5px);
}
</style>

<header class="location-hero">
    <div class="container animate-fade-up">
        <h1>Explore Our Network</h1>
        <p>Discover our location-focused pages, premium industrial services, and global export information tailored for
            B2B requirements.</p>
    </div>
</header>

<main style="background-color: #f8f9fc;">
    <section class="location-section">
        <div class="container">

            <div class="text-center mb-5">
                <span
                    style="color: var(--accent-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Global
                    Presence</span>
                <h2 style="color: var(--primary-blue); font-size: 2.3rem; font-weight: 800;">Location & Custom Pages
                </h2>
                <div style="width: 60px; height: 3px; background: var(--accent-gold); margin: 15px auto;"></div>
            </div>

            <?php if (!empty($customPages)): ?>
            <div class="row g-4 justify-content-center">
                <?php 
                $delay = 0.1;
                foreach ($customPages as $customPage):
                    $cardTitle = trim((string)($customPage['breadcrumb_title'] ?? ''));
                    if ($cardTitle === '') { $cardTitle = trim((string)($customPage['page_title'] ?? '')); }
                    if ($cardTitle === '') { $cardTitle = 'Explore Page'; }

                    $description = trim(strip_tags((string)($customPage['meta_desc'] ?? '')));
                    if ($description === '') { $description = 'Explore premium quality details and export information.'; }

                    $slug = trim((string)($customPage['slug_url'] ?? ''));
                    // Note: Update URL structure if you are using .htaccess routing (e.g. 'page/slug')
                    $pageUrl = 'page.php?slug=' . rawurlencode($slug); 
                ?>
                <div class="col-12 col-md-6 col-lg-4 animate-fade-up" style="animation-delay: <?= $delay; ?>s;">
                    <a href="<?= locationPageText($pageUrl); ?>" class="premium-card">
                        <div class="card-img-wrapper">
                            <span class="export-badge">VT Export</span>
                            <!-- Placeholder image map to your actual folder -->
                            <img src="https://via.placeholder.com/400x250/0a2540/ffffff?text=<?= urlencode(substr($cardTitle, 0, 15)) ?>..."
                                alt="<?= locationPageText($cardTitle); ?>">
                        </div>
                        <div class="card-content">
                            <h3><?= locationPageText($cardTitle); ?></h3>
                            <p><?= locationPageText($description); ?></p>
                            <div class="card-btn">View Details <i class="fa-solid fa-chevron-right"></i></div>
                        </div>
                    </a>
                </div>
                <?php 
                $delay += 0.1;
                endforeach; 
                ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5 bg-white rounded border" style="max-width: 600px; margin: 0 auto;">
                <i class="fas fa-map-marker-alt mb-3" style="font-size: 3rem; color: #ddd;"></i>
                <h3 style="color: var(--primary-blue);">No Pages Available Yet</h3>
                <p class="text-muted">We are currently updating our location and service pages.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>