<?php
/**
 * Profile Friends Page
 * Displays profiles the user follows and followers in a two-column layout
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
        
        <!-- Main content with sidebar and two-column layout -->
        <main class="flex-row flex-grow">
            <!-- Include profile sidebar navigation -->
            <?php require_once $abs_path . "/php/include/profil_sidebar.php" ?>
            
            <!-- Main content area with following/followers lists -->
            <section class="content flex-grow">
                <div class="flex-row flex-grow">
                    <!-- Left column: Profiles the user follows -->
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile den ich folge</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php 
                            // Loop through profiles the user follows
                            foreach ($profile->getFollowing() as $follower){
                                include $abs_path . "/php/view/profile_preview.php";
                            } 
                            ?>
                        </div>
                    </div>
                    
                    <!-- Right column: Profiles following the user -->
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile die mir folgen</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php 
                            // Loop through profiles following the user
                            foreach ($profile->getFollowers() as $follower){
                                include $abs_path . "/php/view/profile_preview.php";
                            } 
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        
        <!-- Include footer -->
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>
</html>