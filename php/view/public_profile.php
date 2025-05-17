<?php
/**
 * Minimal Page Template
 * Basic page structure with navigation and footer but empty main content
 * Could serve as a starter template or placeholder for new pages
 */
if (!isset($abs_path)) {
    require_once "../../path.php";
}

// Page-specific CSS files
$cssFiles = array("pages/profil.css", "pages/profil_freunde.css", "pages/profil-sidebar.css");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
    <!-- Include feedback message system -->
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <!-- Include navigation bar -->
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        
        <!-- Main content area - currently empty -->
        <main class="flex-row flex-grow">
        </main>
        
        <!-- Include footer -->
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>
</html>