<?php foreach ($entry->getComments() as $comment) :?>        
    <div class="kommentar">
        <div class="header">
            <strong><?=htmlspecialchars($comment->getUser()->getUsername())?></strong>
            <div class="date"><span class="icon">📅</span><?=htmlspecialchars(date("d.m.Y H:i",$comment->getDate()))?></div>
        </div>
        
        <p><?=htmlspecialchars($comment->getText())?></p>
        <?php 
            $entry = $comment;
            require $abs_path."/php/view/comment.php" 
            ?>
    </div>
<?php endforeach; ?>