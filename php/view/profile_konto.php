<?php
/**
 * Profile Account Settings Page
 * Allows users to view and modify their account information
 */
if (!isset($abs_path)) {
    require_once "../../path.php";
}

// Page-specific CSS files
$cssFiles = array("pages/profil.css","pages/profil-sidebar.css","pages/profil_konto.css");
// Commented-out JS files (not currently used)
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once $abs_path."/php/include/head.php" ?>

<body class="flex-column">
    <!-- Include feedback message system -->
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <!-- Include navigation bar -->
        <?php include_once $abs_path."/php/include/nav.php" ?>
        
        <!-- Main content with sidebar and settings form -->
        <main class="flex-row flex-grow">
            <!-- Include profile sidebar navigation -->
            <?php include_once $abs_path."/php/include/profil_sidebar.php" ?>
            
            <!-- Account settings main content -->
            <section class="content flex-column flex-grow">
                <h2>Konto</h2>
                <div class="konto-container">
                    <!-- Section header -->
                    <div class="konto-header">
                        <h3>Kontoinformationen</h3>
                    </div>
                    
                    <!-- Account information form -->
                    <form class="konto-info">
                        <!-- Username field -->
                        <div class="konto-field">
                            <label for="username">Benutzername:</label>
                            <div>
                                <input type="text" id="username" value=<?=$profile->getUsername()?>>
                            </div>
                        </div>
                        
                        <!-- Email field -->
                        <div class="konto-field">
                            <label for="email">E-Mail:</label>
                            <div>
                                <input type="email" id="email" value=<?=$profile->getEmail()?>>
                            </div>
                        </div>
                        
                        <!-- Current password field (disabled) -->
                        <div class="konto-field">
                            <label for="current_password">Aktuelles Passwort:</label>
                            <div>
                                <input type="password" id="current_password" value="********" disabled>
                            </div>
                        </div>
                        
                        <!-- New password field -->
                        <div class="konto-field">
                            <label for="new_password">Neues Passwort:</label>
                            <div>
                                <input type="password" id="new_password" value="">
                            </div>
                        </div>
                        
                        <!-- Password confirmation field -->
                        <div class="konto-field">
                            <label for="repeat_password">Passwort bestätigen:</label>
                            <div>
                                <input type="password" id="repeat_password" value="">
                            </div>
                        </div>
                    </form>
                    
                    <!-- Action buttons -->
                    <button type="submit" class="delete-button">Profil löschen</button>
                    <button type="submit" class="display_password">Passwort anzeigen</button>
                    <button type="submit" class="save-button">Speichern</button>
                    <button type="submit" class="cancel-button">Abbrechen</button>
                </div>
            </section>
        </main>
        
        <!-- Include footer -->
        <?php include_once $abs_path."/php/include/footer.php" ?>
    </div>
</body>
</html>