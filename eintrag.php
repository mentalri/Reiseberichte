<?php
$cssFiles = array("css/pages/bericht.css");
?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>
        <section class="bericht-container">
            <img class="bericht-bild" src="someImg" alt="someImg" />

            <h2 class="bericht-titel">Titel</h2>

            <div class="bericht-beschreibung">
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt...
            </div>

            <div class="bericht-meta">
                <div class="bericht-autor">
                    <strong>Autor:</strong> <span>Nutzer1</span>
                </div>
                <div class="bericht-datum">
                    <strong>Datum:</strong> <span>18.03.2020</span>
                </div>
            </div>

            <div class="bericht-aktionen">
                <a href="eintrag-neu.php" class="aktion-link">✏️ Ändern (nur Autor)</a>
                <a href="eintrag.php" class="aktion-link delete-link">🗑️ Löschen (nur Autor/Admin)</a>
            </div>
        </section>
    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>
