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
                    case "invalid_username":
                        echo "Der Benutzername ist ungültig.";
                        break;
                    case "username_taken":
                        echo "Der Benutzername ist bereits vergeben.";
                        break;
                    case "invalid_email":
                        echo "Die eingegebene E-Mail-Adresse ist ungültig.";
                        break;
                    case "email_taken":
                        echo "Die E-Mail-Adresse ist bereits vergeben.";
                        break;
                    case "invalid_password":
                        echo "Das eingegebene Passwort ist ungültig.";
                        break;
                    case "invalid_password_repeat":
                        echo "Die Passwörter stimmen nicht überein.";
                        break;
                    case "register_success":
                        echo "Sie haben sich erfolgreich registriert.";
                        break;
                    case "register_error":
                        echo "Ein Fehler ist bei der Registrierung aufgetreten. Bitte versuchen Sie es später erneut.";
                        break;
                    case "not_logged_in":
                        echo "Sie sind nicht angemeldet. Bitte melden Sie sich an, um diese Funktion zu nutzen.";
                        break;
                    case "missing_file":
                        echo "Die Datei ist nicht vorhanden.";
                        break;
                    case "invalid_image":
                        echo "Das Bild ist ungültig. Bitte laden Sie ein gültiges Bild hoch.";
                        break;
                    case "unsupported_image_type":
                        echo "Der Bildtyp wird nicht unterstützt. Bitte verwenden Sie JPG, PNG oder GIF.";
                        break;
                    case "invalid_image_size":
                        echo "Das Bild ist zu klein. Bitte halten sie sich an die empfohlenen Bildgrößen.";
                        break;
                    case "file_upload_error":
                        echo "Ein Fehler ist beim Hochladen der Datei aufgetreten. Bitte versuchen Sie es später erneut.";
                        break;
                    case "profile_picture_uploaded":
                        echo "Das Profilbild wurde erfolgreich hochgeladen.";
                        break;
                    case "invalid_input_length":
                        echo "Die Eingabe ist zu kurz oder zu lang. Bitte überprüfen Sie die Eingaben.";
                        break;
                    case  "description_changed":
                        echo "Die Beschreibung wurde erfolgreich geändert.";
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