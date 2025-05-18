<?php
global $abs_path;
$cssFiles = array("pages/profil-sidebar.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
    <div class="page-wrapper flex-column">
        <?php include_once $abs_path . "/php/include/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php include_once $abs_path . "/php/include/profil_sidebar.php" ?>
        </main>
        <?php include_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>

</html>