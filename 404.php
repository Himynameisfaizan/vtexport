<?php
$pageTitle = "404 - Page Not Found | VT Export";
$meta_title = "404 - Page Not Found";
$meta_desc = "The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.";
$meta_key = "404, page not found, VT Export";

include 'includes/header.php'; 
?>

<style>
/* Premium 404 Page Design for VT Export */
.error-page-wrap {
    background-color: #0a2540;
    /* Navy Blue Theme */
    background-image: linear-gradient(135deg, #0a2540 0%, #153c66 100%);
    color: #ffffff;
    padding: 120px 20px;
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Subtle dot pattern background */
.error-page-wrap::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 30px 30px;
    z-index: 1;
}

.error-content {
    position: relative;
    z-index: 2;
    max-width: 650px;
    margin: 0 auto;
}

.error-heading {
    font-size: clamp(7rem, 18vw, 13rem);
    font-weight: 900;
    line-height: 1;
    margin-bottom: 0;
    color: transparent;
    -webkit-text-stroke: 3px #d4af37;
    /* Gold Accent */
    text-shadow: 0 10px 40px rgba(212, 175, 55, 0.2);
}

.error-subheading {
    font-size: 2.2rem;
    font-weight: 700;
    margin-top: -10px;
    margin-bottom: 25px;
    color: #ffffff;
}

.error-desc {
    color: #b0c4de;
    font-size: 1.15rem;
    margin-bottom: 45px;
    line-height: 1.7;
}

/* VT Export Gold Button */
.btn-gold-outline {
    background-color: #d4af37;
    color: #0a2540 !important;
    padding: 15px 40px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    border: 2px solid #d4af37;
}

.btn-gold-outline:hover {
    background-color: transparent;
    color: #d4af37 !important;
    box-shadow: 0 5px 25px rgba(212, 175, 55, 0.3);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-up {
    animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<div class="error-page-wrap">
    <div class="error-content animate-fade-up">
        <h1 class="error-heading">404</h1>
        <h2 class="error-subheading">Page Not Found</h2>
        <p class="error-desc">Oops! The industrial export page you are looking for seems to be missing. It might have
            been removed or relocated.</p>

        <a href="index.php" class="btn-gold-outline">
            <i class="fa-solid fa-arrow-left"></i> Return to Homepage
        </a>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>