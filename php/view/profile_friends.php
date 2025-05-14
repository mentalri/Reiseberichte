<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}
$cssFiles = array("pages/profil.css", "pages/profil_freunde.css", "pages/profil-sidebar.css");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php require_once $abs_path . "/php/include/profil_sidebar.php" ?>
            <section class="content flex-grow">
                <div class="flex-row flex-grow">
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile den ich folge</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php for ($x = 0; $x < 10; $x++) {
                                require $abs_path . "/php/include/profile_preview.php";
                            } ?>
                        </div>
                    </div>
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile die mir folgen</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php for ($x = 0; $x < 15; $x++) {
                                require $abs_path . "/php/include/profile_preview.php";
                            } ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>

</html>