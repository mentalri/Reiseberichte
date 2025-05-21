<?php /** @noinspection ALL */
require_once $abs_path . "/php/include/head.php" ?>
<title>Bericht bearbeiten</title>
<link rel="stylesheet" href="css/pages/bericht-formular.css">
</head>
<body>
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
    <?php require_once $abs_path . "/php/include/nav.php" ?>

        <main class="container flex-column">
            <section class="form-container">
                <h2>Reisebericht bearbeiten</h2>

                <form class="bericht-formular flex-column" action="report_edit.php?id=<?=htmlspecialchars($report->getId())?>" method="post" enctype="multipart/form-data">
                    
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" value="<?=htmlspecialchars($_POST["title"]??($report->getTitle()))?>" required>

                    <label for="location">Ort</label>
                    <input type="text" id="location" name="location" value="<?=htmlspecialchars($_POST["location"]??($report->getLocation()))?>" required>

                    <!-- Vorhandene Bilder anzeigen -->
                    <label for="existing_pictures">Bereits hochgeladene Bilder</label>
                    <div class="existing-images">

                        <?php foreach ($report->getPictures() as $idx => $picture): ?>
                            <div class="existing-image">
                                <label for="delete_picture_<?= $idx ?>">
                                    <img src="<?= htmlspecialchars($picture) ?>" alt="Bild" style="max-height: 150px;">
                                </label>


                                <label>
                                    <input type="checkbox" id="delete_picture_<?= $idx ?>" name="delete_pictures[]" value="<?= htmlspecialchars($picture) ?>">
                                    Bild löschen
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Bereits hochgeladene Bilder müssen angezeigt und weiter geben werden und gelöscht werden können-->
                    <label for="pictures">Bilder hochladen</label>
                    <input type="file" id="pictures" name="pictures[]" accept="image/*"  multiple>
                    <div>
                        <input type="checkbox" id="tags-toggle" class="tags-toggle" name="tags-toggle">
                        <div class="tags-box">
                            <div class="tags">
                                <?php
                                require_once $abs_path . "/php/include/tags.php";
                                foreach ($tags as $tag): ?>
                                    <div>
                                        <input type="checkbox" id="<?= $tag ?>" name="tags[]" value="<?= $tag ?>" <?= in_array($tag, ($_POST["tags"]??$report->getTags()))?"checked":"" ?>>
                                        <label for="<?= $tag ?>"><?= $tag ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <label for="tags-toggle" class="tags-button">Schließen</label>
                        </div>
                        <div class="tags-container">
                            <p class="m0">Tags</p>
                            <label for="tags-toggle" class="tags-button">Select Tags</label>
                        </div>
                    </div>

                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" minlength="30" maxlength="2000" required><?=htmlspecialchars($_POST["description"]??($report->getDescription()))?></textarea>

                    <button type="submit" id="submit">Eintragen</button>
                </form>
            </section>

        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>

</body>
</html>
