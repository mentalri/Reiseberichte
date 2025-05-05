<?php
$cssFiles = array("css/pages/profil.css","css/pages/profil_freunde.css");
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
                    <a href="profil_eigene_berichte.php">Eigene Berichte</a>
                    <a href="profil_bewertete_berichte.php">Bewertete Berichte</button>
                    <a href="profil_freunde.php"  id="active">Freunde</a>
                </div>
            </section>
            <section class="content flex-grow">
                <?php include_once "php/profil_freunde.php" ?>
            </section>
        </main>
        <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>