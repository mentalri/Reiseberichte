<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>

        <section>
            <h2>Registrieren</h2>

            <form action="index.php" method="POST">

            
                <div>
                    <label for="name">Name:</label>
                    <div>
                        <input type="text" id="name" name="name" maxlength="100" required>
                        <div>Name ist erforderlich.</div>
                    </div>
                </div>

                <div>
                    <label for="email">E-Mail:</label>
                    <div>
                        <input type="email" id="email" name="email" maxlength="100" required>
                        <div>E-Mail ist erforderlich.</div>
                    </div>
                </div>

                <div>
                    <label for="password">Passwort:</label>
                    <div>
                        <input type="password" id="password" name="password" minlength="8" maxlength="100" required>
                        <div>Passwort ist erforderlich.</div>
                    </div>
                </div>

                <div>
                    <label for="password_repeat">Passwort bestätigen:</label>
                    <div>
                        <input type="password" id="password_repeat" name="password_repeat" minlength="8" maxlength="100"
                            required>
                        <div>Passwort bestätigen ist erforderlich.</div>
                    </div>
                </div>

                <div>
                    <button>Anmelden</button>
                    <button type="submit">Registrieren</button>
                </div>
            </form>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>