<?php
/**
 * Imprint Page Template (impressum.php)
 * Displays legal information about the website's ownership and responsibility
 * Required by German law for website operator identification
 */
$cssFiles = array("css/pages/footer-elements.css"); // Footer pages styling
?>
<?php include_once "php/head.php" ?>

<body>
    <!-- Include navigation bar -->
    <?php include_once "php/nav.php" ?>
    
    <main>
        <h1>Impressum</h1>
        <div class="datenschutz-container">
            <div class="content">
                <div class="preview-liste">
                    <!-- Responsible entity information -->
                    <section>
                        <h2>Verantwortlich für den Inhalt</h2>
                        <p><strong>Reiseberichte</strong></p>
                        <p>Internet: <a href="http://www.Reiseberichte.de" target="_blank">www.Reiseberichte.de</a></p>
                        <p>E-Mail: <a href="mailto:reiseberichte@info.de">reiseberichte@info.de</a></p>
                        <p>Telefon: 0443567128-9</p>
                    </section>
                    
                    <!-- Development team information -->
                    <section>
                        <h2>Entwickler</h2>
                        <ul>
                            <li>Fanping Zhou Schulze</li>
                            <li>Thore Ritterhoff</li>
                            <li>Bayan Alhawari</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Include footer -->
    <?php include_once "php/footer.php" ?>
</body>
</html>