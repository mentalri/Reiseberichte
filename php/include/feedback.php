<?php if (!empty($_SESSION['message'])): ?>
        <input type="checkbox" class="overlay-toggle-checkbox" id="overlayToggleCheckbox"/>
        <div class="overlay" id="overlay-msg">

            <p class="overlay-msg-text">
                <?php
                switch ($_SESSION['message']) {
                    case "internal_error":
                        echo "Ein interner Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.";
                        break;
                    case "invalid_entry_id":
                        echo "Der Eintrag existiert nicht oder ist nicht mehr verfügbar.";
                        break;
                    case "invalid_profile_id":
                        echo "Das Profil existiert nicht oder ist nicht mehr verfügbar.";
                        break;
                    case "missing_parameter":
                        echo "Ein erforderlicher Parameter fehlt. Bitte überprüfen Sie die Eingaben.";
                        break;
                    case "new_report":
                        echo "Der Eintrag wurde erfolgreich erstellt.";
                        break;
                    case "edit_report":
                        echo "Der Eintrag wurde erfolgreich bearbeitet.";
                        break;
                    case "delete_report":
                        echo "Der Eintrag wurde erfolgreich gelöscht.";
                        break;
                    case "login_success":
                        echo "Sie haben sich erfolgreich angemeldet.";
                        break;
                    case "logout_success":
                        echo "Sie haben sich erfolgreich abgemeldet.";
                        break;
                    case "invalid_email":
                        echo "Die eingegebene E-Mail-Adresse ist ungültig.";
                        break;
                    case "invalid_password":
                        echo "Das eingegebene Passwort ist ungültig.";
                        break;
                    case "register_success":
                        echo "Sie haben sich erfolgreich registriert.";
                        break;
                    case "register_error":
                        echo "Ein Fehler ist bei der Registrierung aufgetreten. Bitte versuchen Sie es später erneut.";
                        break;
                    case "invalid_password_repeat":
                        echo "Die Passwörter stimmen nicht überein.";
                        break;
                    case "invalid_username":
                        echo "Der Benutzername ist ungültig oder bereits vergeben.";
                        break;
                    case "not_logged_in":
                        echo "Sie sind nicht angemeldet. Bitte melden Sie sich an, um diese Funktion zu nutzen.";
                        break;
                    default:
                        echo "Unbekannte Meldung: " . $_SESSION['message'];
                        break;
                }
                ?>
            </p>
            <label for="overlayToggleCheckbox" class="overlay-msg-close" id="overlay-msg-close">
                <span class="overlay-msg-close">Ok</span>
            </label>            
        </div>
    <?php endif; ?>
<?php unset($_SESSION["message"]); ?>