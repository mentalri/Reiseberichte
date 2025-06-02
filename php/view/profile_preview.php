<div class="flex-column profil flex-shrink">
    <div class="flex-row">
        <h3><?=htmlspecialchars($follower->getUsername())?></h3>
       <p><?= $follower->isFollowing($profile) ? "folgt dir" : "folgt dir nicht" ?></p>
    </div>
    <div class="flex-row">
        <p>Beiträge: <?= count($userReports) ?></p>
        <a href="profile_follow.php?id=<?=urlencode($follower->getId())?>"><?= $profile->isFollowing($follower) ? "nicht mehr Folgen" : "Folgen" ?></a>
    </div>
</div>