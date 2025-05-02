<?php include_once "php/head.php" ?>

<body>

    <?php include_once "php/nav.php" ?>

    <main>

        <section>
            <h2>Anmelden</h2>

            <form action="index.php" method="POST">
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
                        <input type="password" id="password" name="password" maxlength="100" required>
                        <div>Passwort ist erforderlich.</div>
                    </div>
                </div>
                <div>
                    <Button>Registrieren</Button>
                    <button type="submit">Anmelden</button>
                </div>
            </form>

        </section>

    </main>

    <?php include_once "php/footer.php" ?>
</body>

</html>