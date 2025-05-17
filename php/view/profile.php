<?php
/**
 * Profile Page Base Template
 * Minimal skeleton for profile pages with sidebar structure
 * Serves as a foundation for more specific profile views
 */
$cssFiles = array("css/pages/profil-sidebar.css"); // Only includes sidebar CSS
// Commented-out JS files (not currently used)
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once "php/head.php" ?>

<body class="flex-column">
    <div class="page-wrapper flex-column">
        <!-- Include navigation bar -->
        <?php include_once "php/nav.php" ?>
        
        <!-- Main content area with sidebar -->
        <main class="flex-row flex-grow">
            <!-- Include profile sidebar navigation -->
            <?php include_once "php/profil_sidebar.php" ?>
        </main>
        
        <!-- Include footer -->
        <?php include_once "php/footer.php" ?>
    </div>
</body>
</html>