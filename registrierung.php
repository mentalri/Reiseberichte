<?php $cssFiles = array("css/pages/formular.css");?>
<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>
        <section>
            <h2>Registrieren</h2>
            <form action="anmeldung.php" method="POST">
                <div>
                    <label for="benutzername" class="required">Benutzername:</label>
                    <div>
                        <input type="text" id="benutzername" name="benutzername" placeholder="Benutzernamen eingeben" title="Bitte geben Sie Ihren Benutzernamen ein"
                            required minlength="5" maxlength="8" 
                            oninvalid="setCustomValidity('Benutzername muss zwischen 5 und 8 Zeichen lang sein')"
                            oninput="setCustomValidity('')">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="required">E-Mail:</label>
                    <div>
                        <input type="email" id="email" name="email" placeholder="E-Mail eingeben"
                            pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="Bitte geben Sie eine gültige E-Mail-Adresse ein (z.B. name@example.com)" maxlength="100" required>
                    </div>
                </div>
                
                <div>
                    <label for="pwd" class="required">Passwort:</label>
                    <div>
                        <input type="password" id="pwd" name="pwd"  placeholder="Passwort eingeben"
                            pattern=".{8,}"
                            title="Bitte geben Sie Ihr Passwort ein. Es sollte mindestens 8 Zeichen lang sein" minlength="8" maxlength="12"
                            required
                            oninvalid="setCustomValidity('Passwort muss zwischen 8 und 12 Zeichen lang sein')"
                            oninput="setCustomValidity('')">
                    </div>
                </div>
                
                <div>
                    <label for="password_repeat" class="required">Passwort bestätigen:</label>
                    <div>
                        <input type="password" id="password_repeat" name="password_repeat" placeholder="Passwort wiederholen"
                            minlength="8" maxlength="12" title="Bitte geben Sie das gleiche Passwort wie oben ein." required 
                            oninvalid="setCustomValidity('Passwort muss zwischen 8 und 12 Zeichen lang sein')"
                            oninput="setCustomValidity('')">
                    </div>
                </div>
                
                <p class="terms-text">
                    Durch die Erstellung eines Kontos stimmen Sie unseren <a href="nutzungsbedingungen.php">Nutzungsbedingungen & <a href="datenschutz.php">Datenschutzrichtlinien</a> zu.
                </p>
                <div>
                    <button type="submit">Registrieren</button>
                    <a href= "anmeldung.php">Anmelden</a>
                </div>
                
    
            </form>
        </section>
    </main>
    <?php include_once "php/footer.php" ?>
</body>
</html>