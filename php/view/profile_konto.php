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
                <input type="checkbox" name="upload-profile-picture-toggle-box" id="upload-profile-picture-toggle-box">
                <div class="blur-overlay"></div>
                <div class="upload-profile-picture">
                    <h3>Profilbild ändern</h3>
                    <p>Hier kannst du dein Profilbild ändern.</p>
                    <form action="index.php" class="upload-form" method="POST" enctype="multipart/form-data">
                        <input type="file" id="profile_picture" accept="image/*">
                        <div class="upload-buttons">
                            <button type="submit" class="save-button">Speichern</button>
                            <a href="profile.php?side=konto" class="cancel-button" >Abbrechen</a>
                        </div>
                    </form>
                </div>
                <h2>Konto</h2>
                <div class="konto-container">
                    <div class="login-data-container">
                        <div class="konto-header">
                            <h3>Anmeldedaten</h3>

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
                    <div class="profile-data-container">
                        <input type="checkbox" name="description-form-toggle-box" id="description-form-toggle-box">

                        <div class="profile-data-header">
                            <div class="profile-picture">
                                <img src="<?=$profile->getProfilePicture()?>" alt="Profilbild">
                            </div>
                            <input type="checkbox" name="action-menu-toggle-box" id="action-menu-toggle-box">
                            <label for="action-menu-toggle-box" class="action-menu-toggle">
                                <span></span>
                                <span></span>
                                <span></span>
                            </label>
                            <div class="action-menu">
                                <label for="upload-profile-picture-toggle-box">Profilbild ändern</label>
                                <a href="#">Profilbild löschen</a>
                                <label for="description-form-toggle-box">Beschreibung ändern</label>
                            </div>
                            <div class="profile-meta">
                                <div class="meta-header">
                                    <h3 class="username"><?= htmlspecialchars($profile->getUsername()) ?></h3>
                                </div>
                                <div class="meta-body">
                                    <p><?=count($profile->getFollowers())?> Follower</p>
                                    <p><?=count($profile->getFollowing())?> gefolgt</p>
                                    <p><?=count($profile->getReports())?> Berichte</p>
                                </div>
                            </div>
                        </div>


                        <div class="description">
                            <h3>Beschreibung</h3>
                            <p>Hier kannst du eine kurze Beschreibung über dich hinzufügen.</p>
                            <form action="index.php" method="post" class="description-form">
                                <textarea id="description" rows="4" cols="50" placeholder="Hier kannst du eine kurze Beschreibung über dich hinzufügen."></textarea>
                                <div class="description-buttons">
                                    <button type="submit" class="save-button">Speichern</button>
                                    <a href="profile.php?side=konto" class="cancel-button">Abbrechen</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </section>
        </main>
        <?php include_once $abs_path."/php/include/footer.php" ?>
    </div>
</body>

</html>