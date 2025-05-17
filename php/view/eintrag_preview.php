<!-- 
  Report Preview Component
  Displays a compact preview of a travel report with image carousel, metadata, and actions
-->
<div class="preview-eintrag flex-shrink">
    <!-- Image carousel section -->
    <div class="preview-bild carousel">
        <a href="report.php?id=<?=urlencode($report->getId())?>">
            <!-- Carousel container with unique ID based on report ID -->
            <div class="report-<?=$report->getId()?>-slides">
                <?php foreach ($report->getPictures() as $picture): ?>
                    <!-- Individual slide with report image -->
                    <div class="report-<?=$report->getId()?>-slide">
                        <img src="<?=htmlspecialchars($picture) ?>" alt="Reisebild" class="bericht-bild">
                    </div>
                <?php endforeach; ?>
            </div>
        </a>
    </div>
    
    <!-- Metadata section: title, author, location, date -->
    <div class="preview-meta">
        <div>
            <!-- Title with link to full report -->
            <p class="titel">
                <a href="report.php?id=<?=urlencode($report->getId())?>"><?=htmlspecialchars($report->getTitle())?></a>
            </p>
            <!-- Author with link to profile page -->
            <p class="author">
                <a href="public_profile.php?id=<?=urlencode($report->getId())?>"><?=htmlspecialchars($report->getAuthor()->getUsername())?></a>
            </p>
        </div>
        <div>
            <!-- Location and formatted date -->
            <p><?=htmlspecialchars($report->getLocation())?></p>
            <p><?=htmlspecialchars(date("d.m.Y",$report->getDate()))?></p>
        </div>
    </div>
    
    <!-- Report description preview -->
    <div class="preview-text">
        <p><?=htmlspecialchars($report->getDescription())?></p>
    </div>
    
    <!-- Action buttons (edit, delete) -->
    <div class="preview-actions flex-column" id="preview-actions">
        <!-- Edit button with link to edit form -->
        <a title="Bericht bearbeiten" href="report_form.php?edit=true&id=<?=urlencode($report->getId())?>">
            <img src="resources/edit_icon.png" alt="Edit" class="preview-action" id="edit-icon" loading="lazy">
        </a>
        <!-- Delete button with link to delete action -->
        <a title="Bericht löschen" href="report_delete.php?id=<?=urlencode($report->getId())?>">
            <img src="resources/delete_icon.png" alt="Delete" class="preview-action" id="delete-icon" loading="lazy">
        </a>
    </div>
</div>