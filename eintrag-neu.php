<?php
$cssFiles = array("css/pages/bericht-formular.css");  
?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main class="container">

    <section class="neu-container">
    <h2>Neuen Reisebericht eintragen</h2>

    <form class="bericht-formular" action="eintrag-speichern.php" method="post" enctype="multipart/form-data">
        <label for="titel">Titel</label>
        <input type="text" id="titel" name="titel" required>

        <label for="autor">Autor</label>
        <input type="text" id="autor" name="autor" required>

        <label for="ort">Ort</label>
        <input type="text" id="ort" name="ort" required>

        <label for="datum">Datum</label>
        <input type="date" id="datum" name="datum" required>

        <label for="bild">Bild hochladen</label>
        <input type="file" id="bild" name="bild" accept="image/*">

        <label for="bewertung">Bewertung</label>
        <select id="bewertung" name="bewertung">
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
        </select>

        <label for="beschreibung">Beschreibung</label>
        <textarea id="beschreibung" name="beschreibung" rows="6" required></textarea>

        <button type="submit">Eintragen</button>
    </form>
</section>


    </main>

    <?php include_once "php/footer.php" ?>
</body>
</html>
