<?php require_once $abs_path . "/php/include/head.php" ?>
<link rel="stylesheet" href="css/pages/formular.css">
</head>
<body>
<?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">    
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="container">

            <section>
                <h2>Anmelden</h2>

                <form action="login.php" method="POST">
                    <div>
                        <label for="email" class="required">E-Mail:</label>
                        <div>
                            <input type="email" id="email" name="email" placeholder="E-Mail eingeben"
                                pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                                   title="Bitte geben Sie eine gültige E-Mail-Adresse ein (z.B. name@example.com)" maxlength="100"
                                   value="<?php if(isset($_POST["email"])) echo htmlspecialchars($_POST["email"])?>" required>

                        </div>
                    </div>
                    <div>
                        <label for="password">Passwort:</label>
                        <div>
                        <input type="password" id="password" name="password"  placeholder="Passwort eingeben"
                                pattern=".{8,}"
                                title="Bitte geben Sie Ihr Passwort ein." minlength="8" maxlength="12"
                                required>
                            
                        </div>
                    </div>
                    <div>
                        <button type="submit">Anmelden</button>
                        <a href= "register.php">Registrieren</a>
                    </div>
                </form>

            </section>

        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
   
</body>

</html>