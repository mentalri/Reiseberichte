<?php global $userReports, $profile, $abs_path, $user;
require_once $abs_path . "/php/include/head.php" ?>
<title>Öffentliches Profile</title>
<link rel="stylesheet" href="css/pages/public_profile.css">
</head>

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
                        <?php if($user!=null && $user->getId() != $profile->getId()): ?>
                            <a href="profile_follow.php?id=<?=urlencode($profile->getId())?>"> <?=$user->isFollowing($profile)?"Unfollow":"Follow"?></a>
                        <?php endif; ?>
                    </div>
                    <div class="meta-body">
                        <p><?=count($profile->getFollowers())?> Follower</p>
                        <p><?=count($profile->getFollowing())?> gefolgt</p>
                        <p><?=count($userReports)?> Berichte</p>
                    </div>
                    <div class="description">
                        <p><?=$profile->getDescription()?></p>
                    </div>
                </div>
            </div>
            <div class="profile-body">
                <?php foreach ($userReports as $report): ?>
                    <div class="report-card">
                        <a href="report.php?id=<?= urlencode($report->getId()) ?>" class="report-link">
                            <img src="<?=htmlspecialchars($report->getPictures()[0])?>" alt="Reisebild">
                        </a>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <?php require_once $abs_path . "/php/include/footer.php" ?>
</div>
</body>

</html>