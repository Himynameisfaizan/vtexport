<?php
$pageTitle = "Our Gallery";
require_once 'config/connect.php'; 
include 'includes/header.php';
include 'includes/breadcrumb.php';

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9; 
$offset = ($page - 1) * $limit;
$count_query = mysqli_query($conn, "SELECT COUNT(ID) as total FROM gallery");
$total_records = mysqli_fetch_assoc($count_query)['total'];
$total_pages = ceil($total_records / $limit);

$gal_query = mysqli_query($conn, "SELECT * FROM gallery ORDER BY ID DESC LIMIT $offset, $limit");
$images_array = [];
?>

<section class="gallery-page-section">
    <div class="container">
        
        <div class="text-center mb-5 pb-3">
            <span style="color: #d4af37; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Visual Journey</span>
            <h2 style="color: #0a2540; font-size: clamp(2rem, 3vw, 2.8rem); font-weight: 800;">Infrastructure & Quality</h2>
            <div style="width: 60px; height: 3px; background: #d4af37; margin: 15px auto;"></div>
        </div>

        <div class="row g-4">
            <?php 
            if(mysqli_num_rows($gal_query) > 0) {
                $index = 0;
                while($gal = mysqli_fetch_assoc($gal_query)) {
                    $db_img = $gal['image_path'];
                    $g_img = !empty($db_img) ? 'admin/assets/img/uploads/' . $db_img : 'assets/images/default-gallery.jpg';
                    if(!empty($db_img) && !file_exists($g_img)) { $g_img = 'admin/' . $db_img; }
                    
                    $img_title = htmlspecialchars($gal['image_name']);
                    $images_array[] = ['src' => $g_img, 'title' => $img_title];
            ?>
            <div class="col-lg-4 col-md-6">
                <!-- href hata diya aur onclick add kar diya! URL open nahi hoga ab -->
                <div class="gallery-grid-item" onclick="openLightbox(<?php echo $index; ?>)">
                    <img src="<?php echo $g_img; ?>" alt="<?php echo $img_title; ?>">
                    <!-- <div class="gallery-overlay">
                        <i class="fas fa-expand-arrows-alt"></i>
                        <span class="gallery-title"><?php echo $img_title; ?></span>
                    </div> -->
                </div>
            </div>
            <?php 
                    $index++;
                }
            } else {
                echo '<div class="col-12 text-center"><p>Gallery images coming soon.</p></div>';
            }
            ?>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <ul class="premium-pagination">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
        <?php endif; ?>

    </div>
</section>

<!-- THE LIGHTBOX POPUP HTML -->
<div id="premiumLightbox" class="lightbox-modal">
    <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
    <div class="lightbox-content">
        <a class="lightbox-prev" onclick="changeSlide(-1)">&#10094;</a>
        
        <div style="position: relative; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
            <img id="lightboxImage" src="" alt="">
            <div id="lightboxCaption" class="lightbox-caption"></div>
        </div>
        
        <a class="lightbox-next" onclick="changeSlide(1)">&#10095;</a>
    </div>
</div>

<script>
    const galleryImages = <?php echo json_encode($images_array); ?>;
    let currentSlideIndex = 0;
    const lightbox = document.getElementById("premiumLightbox");
    const lightboxImg = document.getElementById("lightboxImage");
    const lightboxCaption = document.getElementById("lightboxCaption");

    function openLightbox(index) {
        currentSlideIndex = index;
        updateLightboxContent();
        lightbox.classList.add("show");
        document.body.style.overflow = "hidden"; 
    }

    function closeLightbox() {
        lightbox.classList.remove("show");
        document.body.style.overflow = "auto";
    }

    function changeSlide(n) {
        currentSlideIndex += n;
        if (currentSlideIndex >= galleryImages.length) { currentSlideIndex = 0; }
        if (currentSlideIndex < 0) { currentSlideIndex = galleryImages.length - 1; }
        updateLightboxContent();
    }

    function updateLightboxContent() {
        lightboxImg.src = galleryImages[currentSlideIndex].src;
        lightboxCaption.innerText = galleryImages[currentSlideIndex].title;
    }

    window.onclick = function(event) {
        if (event.target == lightbox) {
            closeLightbox();
        }
    }
</script>

<?php 
include 'includes/footer.php'; 
?>