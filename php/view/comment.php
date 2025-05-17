<?php 
/**
 * Comment display template - Renders comments for a travel report
 * 
 * This template iterates through comments of a parent entry (report or comment)
 * and displays them with proper HTML escaping. It also supports nested comments.
 */
foreach ($entry->getComments() as $comment) :?>
    <!-- Comment container -->
    <div class="kommentar">
        <!-- Comment header with author and timestamp -->
        <div class="header">
            <!-- Author username with HTML escaping -->
            <strong><?=htmlspecialchars($comment->getUser()->getUsername())?></strong>
            <!-- Formatted date with calendar icon -->
            <div class="date">
                <span class="icon">📅</span>
                <?=htmlspecialchars(date("d.m.Y H:i", $comment->getDate()))?>
            </div>
        </div>
        
        <!-- Comment text with HTML escaping -->
        <p><?=htmlspecialchars($comment->getText())?></p>
        
        <?php 
        // Recursive inclusion of comment template for nested comments
        // The current comment becomes the parent entry for nested comments
        $entry = $comment;
        require $abs_path."/php/view/comment.php" 
        ?>
    </div>
<?php endforeach; ?>