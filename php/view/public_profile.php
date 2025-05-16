<?php
global $profile;
if (!isset($abs_path)) {
    require_once "../../path.php";
}

$cssFiles = array("pages/profil.css", "pages/profil_freunde.css", "pages/public_profile.css");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
<div class="page-wrapper flex-column">
    <?php require_once $abs_path . "/php/include/nav.php" ?>
    <main class="flex-row flex-grow">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-picture">
                    <img src="<?= htmlspecialchars($profile->getProfilePicture()) ?>" alt="Profilbild" class="profilbild">
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
            <div class="profile-body">
                <?php foreach ($profile->getReports() as $report): ?>
                    <div class="report-card">
                        <img src="<?=htmlspecialchars($report->getPictures()[0])?>" alt="Reisebild">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <?php require_once $abs_path . "/php/include/footer.php" ?>
</div>
</body>

</html>