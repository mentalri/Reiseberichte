<?php include_once "php/head.php" ?>

<body>

    
    <div class="page-wrapper">
    <?php include_once "php/nav.php" ?>
        <main class="container">

            <section>

                <h2 class="h2">Einträge</h2>

                <div>
                    <span>Bild</span>
                    <span>Titel</span>
                    <span>Text</span>
                    <span>Nutzer</span>
                    <span>Datum</span>
                </div>

                <div>
                    <?php include_once "php/eintrag_preview.php" ?>
                    <div>
                        <span><img src="img2" alt="Img"></span>
                        <span><a href="eintrag.php">Titel 2</a></span>
                        <span>Nutzer 2</span>
                        <span>Datum 2</span>
                    </div>
                    <div>
                        <span><img src="img3" alt="Img"></span>
                        <span><a href="eintrag.php">Titel 3</a></span>
                        <span>Nutzer 3</span>
                        <span>Datum 3</span>
                    </div>
                    <div>
                        <span><img src="img4" alt="Img"></span>
                        <span><a href="eintrag.php">Titel 4</a></span>
                        <span>Nutzer 4</span>
                        <span>Datum 4</span>
                    </div>
                </div>
            </section>
        </main>

    <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>