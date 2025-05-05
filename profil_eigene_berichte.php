<?php
$cssFiles = array("css/pages/profil.css","css/preview.css", "css/pages/profil_eigene_berichte.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once "php/head.php" ?>

<body class="flex-column">
    <div class="page-wrapper flex-column">
        <?php include_once "php/nav.php" ?>
        <main class="flex-row flex-grow">
            <section class="sidebar flex-shrink">
                <div class="sidebar-content">
                    <a href="profil.php">Konto</a>
                    <a href="profil_eigene_berichte.php" id="active">Eigene Berichte</a>
                    <a href="profil_bewertete_berichte.php">Bewertete Berichte</button>
                    <a href="profil_freunde.php">Freunde</a>
                </div>
            </section>
            <section class="content flex-column flex-grow">
                <?php include_once "php/profil_eigene_berichte.php" ?>
            </section>
        </main>
        <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>