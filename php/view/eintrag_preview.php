
<div class="preview-eintrag flex-shrink">
    <div class="preview-bild carousel">
        <a href="report.php?id=<?=urlencode($report->getId())?>">
            <div class="report-<?=$report->getId()?>-slides">
                <?php foreach ($report->getPictures() as $picture): ?>
                    <div class="report-<?=$report->getId()?>-slide"><img src="<?=htmlspecialchars($picture) ?>" alt="Reisebild" class="bericht-bild"></div>
                <?php endforeach; ?>
            </div>
        </a>
    </div>
    <div class="preview-meta">
        <div>
            <p class="titel"><a href="report.php?id=<?=urlencode($report->getId())?>"><?=htmlspecialchars($report->getTitle())?></a></p>
            <p class="author"><a href="public_profile.php?id=<?=urlencode($report->getAuthor()->getId())?>"><?=htmlspecialchars($report->getAuthor()->getUsername())?></a></p>
        </div>
        <div>
            <p><?=htmlspecialchars($report->getLocation())?></p>
            <p><?=htmlspecialchars(date("d.m.Y",$report->getDate()))?></p>
        </div>
        
    </div>
    <div class="preview-text">
        <p><?=htmlspecialchars($report->getDescription())?></p>
    </div>
    <div class ="preview-actions flex-column" id="preview-actions">
        <a title="Bericht bearbeiten" href="report_form.php?edit=true&id=<?=urlencode($report->getId())?>">
            <img src="resources/edit_icon.png" alt="Edit" class="preview-action" id="edit-icon" loading="lazy">
        </a>
        <a title="Bericht löschen" href="report_delete.php?id=<?=urlencode($report->getId())?>">
            <img  src="resources/delete_icon.png" alt="Delete" class="preview-action" id="delete-icon" loading="lazy">
        </a>
    </div>
</div>