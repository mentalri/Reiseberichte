<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}
$cssFiles = array("css/pages/profil.css", "css/pages/profil_freunde.css", "css/pages/profil-sidebar.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once "php/head.php" ?>

<body class="flex-column">
    <div class="page-wrapper flex-column">
        <?php include_once "php/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php include_once "php/profil_sidebar.php" ?>
            <section class="content flex-grow">
                <div class="flex-row flex-grow">
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile den ich folge</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php for ($x = 0; $x < 10; $x++) {
                                include "php/profil_preview.php";
                            } ?>
                        </div>
                    </div>
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile die mir folgen</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php for ($x = 0; $x < 15; $x++) {
                                include "php/profil_preview.php";
                            } ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>