<?php $cssFiles = array("css/pages/formular.css");?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>

        <section>
            <h2>Anmelden</h2>

            <form action="index.php" method="POST">
                <div>
                    <label for="benutzername">Benutzername:</label>
                    <div>
                    <input  id="benutzername"  minlength="5" maxlength="8" required>
                       
                    </div>
                </div>
                <div>
                    <label for="password">Passwort:</label>
                    <div>
                        <input type="password" id="password"  minlength="8" maxlength="12" required>
                        
                    </div>
                </div>
                <div>
                    <button type="submit">Anmelden</button>
                    <a href= "registrierung.php">Registrieren</a>
                </div>
            </form>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>