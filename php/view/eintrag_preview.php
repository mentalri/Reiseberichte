<div class="preview-eintrag flex-shrink">
    <div class="preview-bild carousel">
        <a href=<?="report.php?id=".$report->getId()?>>
            <div class="report-<?=$report->getId()?>-slides">
                <?php foreach ($report->getPictures() as $picture): ?>
                    <div class="report-<?=$report->getId()?>-slide"><img src="<?=htmlspecialchars($picture) ?>" alt="Reisebild" class="bericht-bild"></div>
                <?php endforeach; ?>
            </div>
        </a>
    </div>
    <div class="preview-meta">
        <div>
            <p class="titel"><a href=<?="report.php?id=".$report->getId()?>><?=htmlspecialchars($report->getTitle()) ?></a></p>
            <p><?=htmlspecialchars($report->getAuthor()->getUsername())?></p>
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
        <a href=<?="report_form.php?edit=true&id=".$report->getId()?>>
            <img src="resources/edit_icon.png" alt="Edit" class="preview-action" id="edit-icon" loading="lazy">
        </a>
        <img src="resources/delete_icon.png" alt="Delete" class="preview-action" id="delete-icon" loading="lazy">
    </div>
</div>