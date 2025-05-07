<?php
$cssFiles = array("css/pages/bericht-formular.css");  // 添加一个对应的 CSS 文件
?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main class="container">

        <section class="bericht-formular">
            <h2>Neuer Reisebericht</h2>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="bilder">Bilder hochladen:</label>
                    <input type="file" id="bilder" name="bilder[]" multiple accept="image/*">
                </div>

                <div class="form-group">
                    <label for="titel">Titel:</label>
                    <input type="text" id="titel" name="titel" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="text">Text:</label>
                    <textarea id="text" name="text" cols="30" rows="10" maxlength="2000" required></textarea>
                </div>

                <div class="form-actions">
                    <input type="submit" id="eintragen" name="eintragen" value="Speichern">
                    <button type="reset">Zurücksetzen</button>
                </div>
            </form>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>
</html>
