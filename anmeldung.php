<?php $cssFiles = array("css/pages/formular.css");?>
<?php include_once "php/head.php" ?>

<body>
    <div class="page-wrapper">    
        <?php include_once "php/nav.php" ?>
        <main class="container">

            <section>
                <h2>Anmelden</h2>

                <form action="index.php" method="POST">
                    <div>
                        <label for="benutzername">Benutzername:</label>
                        <div>
                        <input type="text" id="benutzername" name="benutzername" placeholder="Benutzernamen eingeben" title="Bitte geben Sie Ihren Benutzernamen ein"
                                required minlength="5" maxlength="8">
                        
                        </div>
                    </div>
                    <div>
                        <label for="password">Passwort:</label>
                        <div>
                        <input type="password" id="pwd" name="pwd"  placeholder="Passwort eingeben"
                                pattern=".{8,}"
                                title="Bitte geben Sie Ihr Passwort ein." minlength="8" maxlength="12"
                                required>
                            
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
    </div>
   
</body>

</html>