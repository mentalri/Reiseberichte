<?php if (!empty($_SESSION['message'])): ?>
        <input type="checkbox" class="overlay-toggle-checkbox" id="overlayToggleCheckbox"/>
        <div class="overlay" id="overlay-msg">

            <p class="overlay-msg-text">
                <?php
                echo match ($_SESSION['message']) {
                    "internal_error" => "Ein interner Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.",
                    "invalid_entry_id" => "Der Eintrag existiert nicht oder ist nicht mehr verfügbar.",
                    "invalid_profile_id" => "Das Profil existiert nicht oder ist nicht mehr verfügbar.",
                    "missing_parameter" => "Ein erforderlicher Parameter fehlt. Bitte überprüfen Sie die Eingaben.",
                    "new_report" => "Der Eintrag wurde erfolgreich erstellt.",
                    "edit_report" => "Der Eintrag wurde erfolgreich bearbeitet.",
                    "report_deleted" => "Der Eintrag wurde erfolgreich gelöscht.",
                    "login_success" => "Sie haben sich erfolgreich angemeldet.",
                    "logout_success" => "Sie haben sich erfolgreich abgemeldet.",
                    "invalid_username" => "Der Benutzername ist ungültig.",
                    "username_taken" => "Der Benutzername ist bereits vergeben.",
                    "invalid_email" => "Die eingegebene E-Mail-Adresse ist ungültig.",
                    "email_taken" => "Die E-Mail-Adresse ist bereits vergeben.",
                    "invalid_password" => "Das eingegebene Passwort ist ungültig.",
                    "invalid_password_repeat" => "Die Passwörter stimmen nicht überein.",
                    "register_success" => "Sie haben sich erfolgreich registriert.",
                    "register_error" => "Ein Fehler ist bei der Registrierung aufgetreten. Bitte versuchen Sie es später erneut.",
                    "not_logged_in" => "Sie sind nicht angemeldet. Bitte melden Sie sich an, um diese Funktion zu nutzen.",
                    "missing_file" => "Die Datei ist nicht vorhanden.",
                    "missing_files" => "Die Dateien sind nicht vorhanden.",
                    "too_many_files" => "Es sind zu viele Dateien hochgeladen worden. Bitte laden Sie maximal 5 Dateien hoch.",
                    "no_files" => "Es wurden keine Dateien hochgeladen. Bitte laden Sie mindestens eine Datei hoch.",
                    "invalid_image" => "Das Bild ist ungültig. Bitte laden Sie ein gültiges Bild hoch.",
                    "unsupported_image_type" => "Der Bildtyp wird nicht unterstützt. Bitte verwenden Sie JPG, PNG oder GIF.",
                    "invalid_image_size" => "Das Bild ist zu klein. Bitte halten sie sich an die empfohlenen Bildgrößen.",
                    "file_upload_error" => "Ein Fehler ist beim Hochladen der Datei aufgetreten. Bitte versuchen Sie es später erneut.",
                    "profile_picture_uploaded" => "Das Profilbild wurde erfolgreich hochgeladen.",
                    "invalid_input_length" => "Eine Eingabe ist zu kurz oder zu lang. Bitte überprüfen Sie die Eingaben.",
                    "description_changed" => "Die Beschreibung wurde erfolgreich geändert.",
                    "profile_deleted" => "Das Profil wurde erfolgreich gelöscht.",
                    "profile_updated" => "Das Profil wurde erfolgreich aktualisiert.",
                    "profile_followed" => "Sie folgen jetzt diesem Profil.",
                    "profile_unfollowed" => "Sie folgen diesem Profil nicht mehr.",
                    "report_liked" => "Der Bericht wurde erfolgreich geliked.",
                    "report_unliked" => "Der Bericht wurde erfolgreich entliked.",
                    "cannot_follow_yourself" => "Sie können sich nicht selbst folgen.",

                    default => "Unbekannte Meldung: " . $_SESSION['message'],
                };
                ?>
            </p>
            <label for="overlayToggleCheckbox" class="overlay-msg-close" id="overlay-msg-close">
                <span class="overlay-msg-close">Ok</span>
            </label>            
        </div>
    <?php endif; ?>
<?php unset($_SESSION["message"]); ?>