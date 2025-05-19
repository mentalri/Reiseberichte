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
                    <input type="text" id="title" name="title" value="<?= $_POST["title"] ?? "" ?>" minlength="5" maxlength="50" required>

                    <label for="location">Ort</label>
                    <input type="text" id="location" name="location" value="<?=$_POST["location"] ?? "" ?>" minlength="5" maxlength="50" required>

                    <label for="pictures">Bilder hochladen (min. 1024px/768px)</label>
                    <input type="file" id="pictures" name="pictures[]" accept="image/*"   multiple required>


                    <label for="description">Beschreibung</label>
                    <textarea minlength="30" maxlength="2000" id="description" name="description" required><?=$_POST["description"] ?? "" ?></textarea>

                    <button type="submit" id="submit">Eintragen</button>
                </form>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>    
</body>
</html>
