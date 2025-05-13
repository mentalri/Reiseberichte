<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}
$cssFiles = array("pages/profil.css","preview.css","pages/profil-sidebar.css");
$phpFiles = ["css/multiple-carousel-styles.php"];
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php require_once $abs_path . "/php/include/profil_sidebar.php" ?>
            <section class="content flex-column flex-grow">
                <div class="preview-liste flex-grow">
                    <?php foreach ($reports as $report) {
                        include $abs_path . "/php/view/eintrag_preview.php";
                    }
                    ?>
                </div>   
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>

</html>