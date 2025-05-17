<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}
$cssFiles = array("pages/profil.css","pages/profil-sidebar.css","pages/profil_konto.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once $abs_path."/php/include/head.php" ?>

<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <?php include_once $abs_path."/php/include/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php include_once $abs_path."/php/include/profil_sidebar.php" ?>
            <section class="content flex-column flex-grow">
            <h2>Konto</h2>
                <div class="konto-container">
                    <div class="konto-header">
                        <h3>Kontoinformationen</h3>
                        
                    </div>
                    <form class="konto-info">
                        <div class="konto-field">
                            <label for="username">Benutzername:</label>            
                            <div>
                                <input type="text" id="username" value=<?=$profile->getUsername()?>>                                
                            </div>   
                        </div>
                        <div class="konto-field">
                            <label for="email">E-Mail:</label>            
                            <div>
                                <input type="email" id="email" value=<?=$profile->getEmail()?>>                                
                            </div>   
                        </div>
                        <div class="konto-field">
                            <label for="current_password">Aktuelles Passwort:</label>
                            <div>
                                <input type="password" id="current_password" value="********" disabled>                                
                            </div>            
                        </div>
                        <div class="konto-field">
                            <label for="new_password">Neues Passwort:</label>
                            <div>
                                <input type="password" id="new_password" value="">                                
                            </div>
                        </div>
                        <div class="konto-field">
                            <label for="repeat_password">Passwort bestätigen:</label>
                            <div>
                                <input type="password" id="repeat_password" value="">
                            </div>            
                        </div>
                    </form>
                    <button type="submit" class="delete-button">Profil löschen</button>
                    <button type="submit" class="display_password">Passwort anzeigen</button>
                    <button type="submit" class="save-button">Speichern</button>
                    <button type="submit" class="cancel-button">Abbrechen</button>
                </div>        
            </section>
        </main>
        <?php include_once $abs_path."/php/include/footer.php" ?>
    </div>
</body>

</html>