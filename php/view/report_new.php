<?php
/**
 * New Report Creation Form Template
 * Provides a form for creating new travel reports
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
                <h2>Neuen Reisebericht erstellen</h2>
                
                <!-- New report form with file upload support -->
                <form class="bericht-formular flex-column" 
                      action="report_add.php" 
                      method="post" 
                      enctype="multipart/form-data">
                    
                    <!-- Title field -->
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" required>
                    
                    <!-- Location field -->
                    <label for="location">Ort</label>
                    <input type="text" id="location" name="location" required>
                    
                    <!-- Multiple image upload field -->
                    <label for="pictures">Bilder hochladen</label>
                    <input type="file" id="pictures" name="pictures[]" accept="image/*" multiple required>
                    
                    <!-- Description textarea -->
                    <label for="description">Beschreibung</label>
                    <textarea id="description" name="description" required></textarea>
                    
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