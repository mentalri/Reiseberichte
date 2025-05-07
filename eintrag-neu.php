<?php
$cssFiles = array("css/pages/bericht-formular.css");  
?>
<?php include_once "php/head.php" ?>

<body>
    <div class="page-wrapper">
    <?php include_once "php/nav.php" ?>

        <main class="container flex-column">
            <section class="neu-container flex-column flex-grow">
                <h2>Neuen Reisebericht erstellen</h2>

                <form class="bericht-formular flex-column" action="eintrag.php" method="post" enctype="multipart/form-data">
                    
                    <label for="titel">Titel</label>
                    <input type="text" id="titel" name="titel" required>

                    <label for="ort">Ort</label>
                    <input type="text" id="ort" name="ort" required>

                    <label for="bild">Bilder hochladen</label>
                    <input type="file" id="bild" name="bild" accept="image/*" multiple required>


                    <label for="beschreibung">Beschreibung</label>
                    <textarea id="beschreibung" name="beschreibung"  required></textarea>

                    <button type="submit" id="submit">Eintragen</button>
                </form>
            </section>
        </main>
        <?php include_once "php/footer.php" ?>
    </div>    
</body>
</html>
