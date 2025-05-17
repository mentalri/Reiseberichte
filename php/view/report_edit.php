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
                <h2>Reisebericht bearbeiten</h2>

               <form class="bericht-formular flex-column" action="report_edit.php?id=<?=htmlspecialchars($report->getId())?>" method="post" enctype="multipart/form-data">

    <label for="title">Titel</label>
    <input type="text" id="title" name="title" value="<?=htmlspecialchars($report->getTitle())?>" required>

    <label for="location">Ort</label>
    <input type="text" id="location" name="location" value="<?=htmlspecialchars($report->getLocation())?>" required>

    <!-- Vorhandene Bilder anzeigen -->
    <?php foreach ($report->getPictures() as $picture): ?>
        <div class="existing-image">
            <img src="<?= htmlspecialchars($picture) ?>" alt="Bild" style="max-height: 150px;">
            
            <label>
                <input type="checkbox" name="delete_pictures[]" value="<?= htmlspecialchars($picture) ?>">
                Bild löschen
            </label>
            <input type="hidden" name="existing_pictures[]" value="<?= htmlspecialchars($picture) ?>">
        </div>
    <?php endforeach; ?>

    <!-- Neue Bilder hochladen -->
    <label for="pictures">Neue Bilder hochladen (optional)</label>
    <input type="file" id="pictures" name="pictures[]" accept="image/*" multiple>

    <label for="description">Beschreibung</label>
    <textarea id="description" name="description" required><?=htmlspecialchars($report->getDescription())?></textarea>

    <button type="submit" id="submit">Speichern</button>
</form>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>    
</body>
</html>
