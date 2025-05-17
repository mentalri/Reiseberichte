<!-- 
  Profile Preview Component
  Displays a condensed view of a user profile with follow status and actions
-->
<div class="flex-column profil flex-shrink">
    <!-- Top row: Username and follow status indicator -->
    <div class="flex-row">
        <!-- Username with XSS protection -->
        <h3><?=htmlspecialchars($follower->getUsername())?></h3>
        
        <!-- Indicator showing if this user follows the current user -->
        <p><?= $follower->isFollowing($profile) ? "folgt dir" : "folgt dir nicht" ?></p>
    </div>
    
    <!-- Bottom row: Post count and follow/unfollow action -->
    <div class="flex-row">
        <!-- Number of reports/posts by this user -->
        <p>Beiträge: <?= count($follower->getReports()) ?></p>
        
        <!-- Follow/unfollow toggle link with dynamic text -->
        <a href="profile_follow.php?id=<?=urlencode($follower->getId())?>">
            <?= $profile->isFollowing($follower) ? "nicht mehr Folgen" : "Folgen" ?>
        </a>
    </div>
</div>