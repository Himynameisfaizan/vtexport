<?php
$pageTitle = "Our Premium Services";
require_once 'config/connect.php';
include 'includes/header.php';
include 'includes/breadcrumb.php';

$services_query = mysqli_query($conn, "SELECT * FROM services ORDER BY id ASC");
?>

<section class="services-list-section">
    <div class="container">

        <div class="text-center mb-5 pb-3">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">What We Do</span>
            <h2 style="color: #0a2540; font-size: clamp(2rem, 3vw, 2.8rem); font-weight: 800;">Our Expertise & Services</h2>
            <div style="width: 60px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>

        <div class="services-wrapper">
            <?php
            if (mysqli_num_rows($services_query) > 0) {
                while ($service = mysqli_fetch_assoc($services_query)) {
                    $db_img = $service['img_path'];
                    $s_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-service.jpg';
                    if (!empty($db_img) && !file_exists($s_img)) {
                        $s_img = 'admin/' . $db_img;
                    }
            ?>
                    <div class="service-row">
                        <div class="service-img-col">
                            <a href="service-details.php?slug=<?php echo $service['slug_url']; ?>">
                                <img src="<?php echo $s_img; ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                            </a>
                        </div>
                        <div class="service-content-col">
                            <h3 class="service-title">
                                <a href="service-details.php?slug=<?php echo $service['slug_url']; ?>" style="text-decoration: none;">
                                    <?php echo htmlspecialchars($service['service_name']); ?>
                            </h3>
                            </a>
                            <p class="service-desc"><?php echo htmlspecialchars($service['short_desc']); ?></p>

                            <a href="service-details.php?slug=<?php echo $service['slug_url']; ?>" class="btn-service-outline">
                                Read More <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="text-center"><p>No services found.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>