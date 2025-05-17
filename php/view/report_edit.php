<?php
/**
 * Report Edit Form Template
 * Provides a form for editing existing travel reports with pre-populated data
 */
$cssFiles = array("pages/bericht-formular.css"); // Form-specific styling
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body>
    <!-- Include feedback message system -->
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
        <!-- Include navigation bar -->
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        
        <main class="container flex-column">
            <section class="neu-container flex-column flex-grow">
                <h2>Reisebericht bearbeiten</h2>
                
                <!-- Report editing form with file upload support -->
                <form class="bericht-formular flex-column" 
                      action="report_edit.php?<?=htmlspecialchars($report->getId())?>" 
                      method="post" 
                      enctype="multipart/form-data">
                    
                    <!-- Title field pre-populated with current title -->
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" 
                           value="<?=htmlspecialchars($report->getTitle())?>" required>
                    
                    <!-- Location field pre-populated with current location -->
                    <label for="location">Ort</label>
                    <input type="text" id="location" name="location" 
                           value="<?=htmlspecialchars($report->getLocation())?>" required>
                    
                    <!-- Image upload field with note about existing images -->
                    <!-- Note: Comment indicates existing images should be displayed but implementation is missing -->
                    <label for="pictures">Bilder hochladen</label>
                    <input type="file" id="pictures" name="pictures" accept="image/*" multiple required>
                    
                    <!-- Description textarea pre-populated with current description -->
                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" required><?=htmlspecialchars($report->getDescription())?></textarea>
                    
                    <!-- Submit button -->
                    <button type="submit" id="submit">Eintragen</button>
                </form>
            </section>
        </main>
        
        <!-- Include footer -->
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>
</html>