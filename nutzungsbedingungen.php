<?php
/**
 * Terms of Service Page Template (nutzungsbedingungen.php)
 * Displays the website's terms and conditions for user agreement
 * Outlines the rules, rights, and responsibilities for platform users
 */
$cssFiles = array("css/pages/footer-elements.css"); // Footer pages styling
?>
<?php include_once "php/head.php" ?>

<body>
    <!-- Include navigation bar -->
    <?php include_once "php/nav.php" ?>
    
    <main>
        <h1>Nutzungsbedingungen</h1>
        <div class="datenschutz-container">
            <div>
                <div>
                    <!-- Scope section -->
                    <section>
                        <h2>Geltungsbereich</h2>
                        <p>Diese Nutzungsbedingungen gelten für die Nutzung der Webseite „Reiseberichte".<br> Mit der Registrierung und Nutzung unserer Dienste akzeptieren Sie diese Bedingungen.</p>
                    </section>
                    
                    <!-- Registration requirements -->
                    <section>
                        <h2>Registrierung</h2>
                        <p>Um Inhalte erstellen und bewerten zu können, müssen Sie sich registrieren.<br> Sie sind verpflichtet, Ihre Daten wahrheitsgemäß anzugeben.</p>
                    </section>
                    
                    <!-- User behavior guidelines -->
                    <section>
                        <h2>Nutzerverhalten</h2>
                        <ul>
                            <li>Sie sind verantwortlich für alle Inhalte, die Sie auf der Plattform veröffentlichen.</li>
                            <li>Es ist untersagt, beleidigende, diskriminierende oder illegale Inhalte zu teilen.</li>
                            <li>Die Nutzung von Bots oder automatisierten Diensten ist nicht gestattet.</li>
                        </ul>
                    </section>
                    
                    <!-- Content rights -->
                    <section>
                        <h2>Rechte an Inhalten</h2>
                        <p>Durch das Hochladen von Inhalten räumen Sie uns das Recht ein,<br> diese Inhalte auf unserer Plattform anzuzeigen und zu verbreiten.</p>
                    </section>
                    
                    <!-- Liability limitations -->
                    <section>
                        <h2>Haftung</h2>
                        <p>Wir übernehmen keine Haftung für die Inhalte Dritter oder für Schäden, <br>die durch die Nutzung unserer Plattform entstehen.</p>
                    </section>
                    
                    <!-- Terms modification policy -->
                    <section>
                        <h2>Änderungen der Nutzungsbedingungen</h2>
                        <p>Wir behalten uns das Recht vor, diese Nutzungsbedingungen jederzeit zu ändern. <br>Änderungen werden auf der Webseite veröffentlicht.</p>
                    </section>
                    
                    <!-- Contact information -->
                    <section>
                        <h2>Kontakt</h2>
                        <p>Bei Fragen zu diesen Nutzungsbedingungen können Sie uns unter <a href="mailto:reiseberichte@info.de">reiseberichte@info.de</a> kontaktieren.</p>
                    </section>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Include footer -->
    <?php include_once "php/footer.php" ?>
</body>
</html>