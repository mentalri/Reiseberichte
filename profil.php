<?php
$cssFiles = array("css/pages/profil.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once "php/head.php" ?>

<body class="flex-column">
    <div class="page-wrapper flex-column">
        <?php include_once "php/nav.php" ?>
        <main class="flex-row flex-grow">
            <section class="sidebar flex-shrink">
                <div class="sidebar-content">
                    <button class="active">Konto</button>
                    <button>Eigene Berichte</button>
                    <button>Bewertete Berichte</button>
                    <button>Freunde</button>
                </div>
            </section>
            <section class="content flex-grow">
                <?php include_once "php/profil_konto.php" ?>
            </section>
        </main>
        <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>