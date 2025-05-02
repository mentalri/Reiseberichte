<?php
$cssFiles = array("css/pages/bericht.css");
?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>

        <section>
            <img src="someImg" alt="someImg">
            <h2>Titel</h2>

            

            <div>
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et
                ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum
                dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore
                magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet
                clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
            </div>

            <div>
                <span>Nutzer</span>
                <span>Nutzer1</span>
            </div>
            <div>
                <span>Datum:</span>
                <span>18.03.2020</span>
            </div>

            <div>
                <a href="eintrag-neu.php">Ändern (Autor)</a>
            </div>

            <div>
                <a href="eintrag.php">Löschen (Autor, Admin)</a>
            </div>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>