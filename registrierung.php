<?php $cssFiles = array("css/pages/formular.css");?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>

        <section>
            <h2>Registrieren</h2>

            <form action="anmeldung.php" method="POST">

            
                <div>
                    <label for="benutzername">Benutzername:</label>
                    <div>
                    <input id="benutzername" 
                            required minlength="5" maxlength="8"
                               oninvalid="setCustomValidity('Benutzername muss zwischen 5 und 8 Zeichen lang sein')"
                               oninput="setCustomValidity('')">
                    </div>
                </div>

                <div>
                    <label for="email">E-Mail:</label>
                    <div>
                        <input type="email" id="email" maxlength="100" required>
                    </div>
                </div>

                <div>
                    <label for="password">Passwort:</label>
                    <div>
                    <input type="password" id="password" name="password" 
                               required minlength="8" maxlength="12"
                               oninvalid="setCustomValidity('Passwort muss zwischen 8 und 12 Zeichen lang sein')"
                               oninput="setCustomValidity('')">
                    </div>
                </div>

                <div>
                    <label for="password_repeat">Passwort bestätigen:</label>
                    <div>
                        <input type="password" id="password_repeat" name="password_repeat" minlength="8" maxlength="12"
                            required oninvalid="setCustomValidity('Passwort muss zwischen 8 und 12 Zeichen lang sein')"
                            oninput="setCustomValidity('')">
                    </div>
                </div>

                <div>
                <a href= "anmeldung.php">Anmelden</a>
                    <button type="submit">Registrieren</button>
                </div>
            </form>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>
</html>