<?php
$current_page_title = isset($pageTitle) ? $pageTitle : 'VT Export';
?>

<section class="breadcrumb-section">
    <div class="container">
        <div class="breadcrumb-content">
            <!-- Dynamic Page Title -->
            <h1 class="breadcrumb-title"><?php echo htmlspecialchars($current_page_title); ?></h1>
            
            <nav class="breadcrumb-nav" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($current_page_title); ?></li>
                </ol>
            </nav>
        </div>
    </div>
</section>