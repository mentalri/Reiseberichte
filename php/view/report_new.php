<?php
$cssFiles = array("pages/bericht-formular.css");  
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body>
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
    <?php require_once $abs_path . "/php/include/nav.php" ?>

        <main class="container flex-column">
            <section class="neu-container flex-column flex-grow">
                <h2>Neuen Reisebericht erstellen</h2>

                <form class="bericht-formular flex-column" action="report_add.php" method="post" enctype="multipart/form-data">
                    
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" required>

                    <label for="location">Ort</label>
                    <input type="text" id="location" name="location" required>

                    <label for="pictures">Bilder hochladen</label>
                    <input type="file" id="pictures" name="pictures[]" accept="image/*"   multiple required>


                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" required></textarea>

                    <button type="submit" id="submit">Eintragen</button>
                </form>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>    
</body>
</html>
