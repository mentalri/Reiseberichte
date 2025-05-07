<?php
$cssFiles = array("css/pages/profil.css","css/pages/profil-sidebar.css","css/pages/profil_konto.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once "php/head.php" ?>

<body class="flex-column">
    <div class="page-wrapper flex-column">
        <?php include_once "php/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php include_once "php/profil_sidebar.php" ?>
            <section class="content flex-column flex-grow">
                <?php include_once "php/profil_konto.php" ?>
            </section>
        </main>
        <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>