<?php
/**
 * Profile Rated Reports Page
 * Displays travel reports that the current user has rated
 * Very similar to the standard reports page but with a different CSS file
 */
if (!isset($abs_path)) {
    require_once "../../path.php";
}

// Page-specific CSS and PHP files
$cssFiles = array(
    "pages/profil.css",           // Base profile styling
    "preview.css",                // Report preview styling
    "pages/profil_eigene_berichte.css", // Specific styling for own/rated reports
    "pages/profil-sidebar.css"    // Profile sidebar styling
);
$phpFiles = ["css/multiple-carousel-styles.php"]; // For report image carousels
// Commented-out JS files (not currently used)
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
    <!-- Include feedback message system -->
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <!-- Include navigation bar -->
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        
        <!-- Main content with sidebar and rated reports list -->
        <main class="flex-row flex-grow">
            <!-- Include profile sidebar navigation -->
            <?php require_once $abs_path . "/php/include/profil_sidebar.php" ?>
            
            <!-- Rated reports listing section -->
            <section class="content flex-column flex-grow">
                <!-- List of reports rated by the user -->
                <div class="preview-liste flex-grow">
                    <?php 
                    // Loop through the rated reports and display each one
                    foreach ($reports as $report) {
                        include $abs_path . "/php/view/eintrag_preview.php";
                    }
                    ?>
                </div>
            </section>
        </main>
        
        <!-- Include footer -->
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>
</html>