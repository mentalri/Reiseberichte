<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main class="container">

        <section>
            <h2 class="h2">Neuer Reisebericht</h2>

            <form method="post">
                <button> Bilder Hochladen</button>
                <div>
                    <label for="titel">Titel:</label>
                    <input type="text" id="titel" name="titel" maxlength="100" required>
                </div>
                
                <div>
                    <label for="text">Text:</label>
                    <textarea id="text" name="text" cols="20" rows="10" maxlength="2000"></textarea>
                </div>
                <div>
                    <input type="submit" id="eintragen" name="eintragen" value="Speichern">
                    <button>Löschen</button>
                </div>
                
            </form>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>