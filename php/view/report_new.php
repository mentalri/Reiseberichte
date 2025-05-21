<?php /** @noinspection PhpUndefinedVariableInspection */
require_once $abs_path . "/php/include/head.php" ?>
<title>Bericht neu erstellen</title>
<link rel="stylesheet" href="css/pages/bericht-formular.css">
</head>
<body>
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
    <?php require_once $abs_path . "/php/include/nav.php" ?>

        <main class="container flex-column">
            <section class="neu-container flex-column flex-grow">
                <h2>Neuen Reisebericht erstellen</h2>

                <form class="bericht-formular flex-column" action="report_add.php" method="post" enctype="multipart/form-data">
                    
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST["title"] ?? "") ?>" minlength="5" maxlength="50" required>

                    <label for="location">Ort</label>
                    <input type="text" id="location" name="location" value="<?=htmlspecialchars($_POST["location"] ?? "") ?>" minlength="5" maxlength="50" required>

                    <label for="pictures">Bilder hochladen (min. 1024px/768px)</label>
                    <input type="file" id="pictures" name="pictures[]" accept="image/*"   multiple required>
                    <div>
                        <input type="checkbox" id="tags-toggle" class="tags-toggle" name="tags-toggle">
                        <div class="tags-box">
                            <div class="tags">
                                <?php
                                require_once $abs_path . "/php/include/tags.php";
                                foreach ($tags as $tag): ?>
                                    <div>
                                        <input type="checkbox" id="<?= $tag ?>" name="tags[]" value="<?= $tag ?>" <?= in_array($tag, ($_POST["tags"]??[]))?"checked":"" ?>>
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
                    <textarea minlength="30" maxlength="2000" id="description" name="description" required><?=htmlspecialchars($_POST["description"] ?? "") ?></textarea>

                    <button type="submit" id="submit">Eintragen</button>
                </form>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>    
</body>
</html>
