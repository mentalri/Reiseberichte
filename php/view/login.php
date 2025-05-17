<?php 
/**
 * Login Page Template
 * Provides user authentication form with email and password inputs
 */
$cssFiles = array("pages/formular.css");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body>
    <!-- Include feedback message system for login status notifications -->
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
        <!-- Include navigation bar -->
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        
        <main class="container">
            <section>
                <h2>Anmelden</h2>
                
                <!-- Login form with email and password fields -->
                <form action="login.php" method="POST">
                    <!-- Email input field with validation -->
                    <div>
                        <label for="email" class="required">E-Mail:</label>
                        <div>
                            <input type="email" id="email" name="email" placeholder="E-Mail eingeben"
                                pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" 
                                title="Bitte geben Sie eine gültige E-Mail-Adresse ein (z.B. name@example.com)" 
                                maxlength="100" required>
                        </div>
                    </div>
                    
                    <!-- Password input field with validation -->
                    <div>
                        <label for="password">Passwort:</label>
                        <div>
                            <input type="password" id="password" name="password" placeholder="Passwort eingeben"
                                pattern=".{8,}"
                                title="Bitte geben Sie Ihr Passwort ein." 
                                minlength="8" maxlength="12"
                                required>
                        </div>
                    </div>
                    
                    <!-- Form actions: submit button and registration link -->
                    <div>
                        <button type="submit">Anmelden</button>
                        <a href="register.php">Registrieren</a>
                    </div>
                </form>
            </section>
        </main>
        
        <!-- Include footer -->
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>
</html>