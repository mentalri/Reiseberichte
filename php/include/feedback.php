<?php if (!empty($_SESSION['message'])): ?>
    <!-- Checkbox for toggling the overlay visibility -->
    <input type="checkbox" class="overlay-toggle-checkbox" id="overlayToggleCheckbox"/>
    
    <!-- Overlay container for displaying session messages -->
    <div class="overlay" id="overlay-msg">
        <p class="overlay-msg-text">
            <?php
            // Switch statement to handle different message types
            switch ($_SESSION['message']) {
                // System/Technical errors
                case "internal_error":
                    echo "Ein interner Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.";
                    // Translation: "An internal error has occurred. Please try again later."
                    break;
                case "invalid_entry_id":
                    echo "Der Eintrag existiert nicht oder ist nicht mehr verfügbar.";
                    // Translation: "The entry does not exist or is no longer available."
                    break;
                case "invalid_profile_id":
                    echo "Das Profil existiert nicht oder ist nicht mehr verfügbar.";
                    // Translation: "The profile does not exist or is no longer available."
                    break;
                case "missing_parameter":
                    echo "Ein erforderlicher Parameter fehlt. Bitte überprüfen Sie die Eingaben.";
                    // Translation: "A required parameter is missing. Please check your inputs."
                    break;
                    
                // Report management messages
                case "new_report":
                    echo "Der Eintrag wurde erfolgreich erstellt.";
                    // Translation: "The entry was successfully created."
                    break;
                case "edit_report":
                    echo "Der Eintrag wurde erfolgreich bearbeitet.";
                    // Translation: "The entry was successfully edited."
                    break;
                case "delete_report":
                    echo "Der Eintrag wurde erfolgreich gelöscht.";
                    // Translation: "The entry was successfully deleted."
                    break;
                    
                // Authentication messages
                case "login_success":
                    echo "Sie haben sich erfolgreich angemeldet.";
                    // Translation: "You have successfully logged in."
                    break;
                case "logout_success":
                    echo "Sie haben sich erfolgreich abgemeldet.";
                    // Translation: "You have successfully logged out."
                    break;
                case "invalid_email":
                    echo "Die eingegebene E-Mail-Adresse ist ungültig.";
                    // Translation: "The entered email address is invalid."
                    break;
                case "invalid_password":
                    echo "Das eingegebene Passwort ist ungültig.";
                    // Translation: "The entered password is invalid."
                    break;
                    
                // Registration messages
                case "register_success":
                    echo "Sie haben sich erfolgreich registriert.";
                    // Translation: "You have successfully registered."
                    break;
                case "register_error":
                    echo "Ein Fehler ist bei der Registrierung aufgetreten. Bitte versuchen Sie es später erneut.";
                    // Translation: "An error occurred during registration. Please try again later."
                    break;
                case "invalid_password_repeat":
                    echo "Die Passwörter stimmen nicht überein.";
                    // Translation: "The passwords do not match."
                    break;
                case "invalid_username":
                    echo "Der Benutzername ist ungültig oder bereits vergeben.";
                    // Translation: "The username is invalid or already taken."
                    break;
                    
                // Access control message
                case "not_logged_in":
                    echo "Sie sind nicht angemeldet. Bitte melden Sie sich an, um diese Funktion zu nutzen.";
                    // Translation: "You are not logged in. Please log in to use this feature."
                    break;
                    
                // Default case for unhandled message types
                default:
                    echo "Unbekannte Meldung: " . $_SESSION['message'];
                    // Translation: "Unknown message: " + the message content
                    break;
            }
            ?>
        </p>
        
        <!-- Close button for the overlay -->
        <label for="overlayToggleCheckbox" class="overlay-msg-close" id="overlay-msg-close">
            <span class="overlay-msg-close">Ok</span>
        </label>
    </div>
<?php endif; ?>

<?php 
// Clear the message from the session after displaying it
// to prevent it from appearing again on page refresh
unset($_SESSION["message"]); 
?>