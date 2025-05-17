<?php
/**
 * Registration Page Template
 * Provides a form for new user registration with validation
 */
$cssFiles = array("pages/formular.css"); // Form-specific styling
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body>
    <!-- Include feedback message system -->
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
        <!-- Include navigation bar -->
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        
        <main class="container">
            <section>
                <h2>Registrieren</h2>
                
                <!-- Registration form with POST submission -->
                <form action="register.php" method="POST">
                    <!-- Username field with validation -->
                    <div>
                        <label for="username" class="required">Benutzername:</label>
                        <div>
                            <input type="text" id="username" name="username" 
                                placeholder="Benutzernamen eingeben" 
                                title="Bitte geben Sie Ihren Benutzernamen ein"
                                required minlength="5" maxlength="8"
                                oninvalid="setCustomValidity('Benutzername muss zwischen 5 und 8 Zeichen lang sein')"
                                oninput="setCustomValidity('')">
                        </div>
                    </div>
                    
                    <!-- Email field with validation -->
                    <div>
                        <label for="email" class="required">E-Mail:</label>
                        <div>
                            <input type="email" id="email" name="email" 
                                placeholder="E-Mail eingeben"
                                pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" 
                                title="Bitte geben Sie eine gültige E-Mail-Adresse ein (z.B. name@example.com)" 
                                maxlength="100" required>
                        </div>
                    </div>
                    
                    <!-- Password field with validation -->
                    <div>
                        <label for="password" class="required">Passwort:</label>
                        <div>
                            <input type="password" id="password" name="password" 
                                placeholder="Passwort eingeben"
                                pattern=".{8,}"
                                title="Bitte geben Sie Ihr Passwort ein. Es sollte mindestens 8 Zeichen lang sein" 
                                minlength="8" maxlength="12"
                                required
                                oninvalid="setCustomValidity('Passwort muss zwischen 8 und 12 Zeichen lang sein')"
                                oninput="setCustomValidity('')">
                        </div>
                    </div>
                    
                    <!-- Password confirmation field -->
                    <div>
                        <label for="password_repeat" class="required">Passwort bestätigen:</label>
                        <div>
                            <input type="password" id="password_repeat" name="password_repeat" 
                                placeholder="Passwort wiederholen"
                                minlength="8" maxlength="12" 
                                title="Bitte geben Sie das gleiche Passwort wie oben ein." 
                                required
                                oninvalid="setCustomValidity('Passwort muss zwischen 8 und 12 Zeichen lang sein')"
                                oninput="setCustomValidity('')">
                        </div>
                    </div>
                    
                    <!-- Terms and conditions agreement text -->
                    <p class="terms-text">
                        Durch die Erstellung eines Kontos stimmen Sie unseren 
                        <a href="nutzungsbedingungen.php">Nutzungsbedingungen</a> & 
                        <a href="datenschutz.php">Datenschutzrichtlinien</a> zu.
                    </p>
                    
                    <!-- Form actions: register button and login link -->
                    <div>
                        <button type="submit">Registrieren</button>
                        <a href="login.php">Anmelden</a>
                    </div>
                </form>
            </section>
        </main>
        
        <!-- Include footer -->
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>
</html>