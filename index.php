<?php
$cssFiles = array("css/pages/index.css");
#$jsFiles = array("js/index.js", "js/orte.js", "js/eintrag_preview.js", "js/footer.js");
?>
<?php include_once "php/head.php" ?>

<input type="checkbox" class="sidebar-toggle-checkbox" id="sidebarToggleCheckbox"/>
<body>

    
    <div class="page-wrapper">
    <?php include_once "php/nav.php" ?>
        <main class="container">
            
            <section class="sidebar">                
                <div class="sidebar-content">
                <h2 class="m0 h2 fit-width">Filter</h2> 
                    <div class="filter-container">
                        <div class="filter-item">
                            <label for="ort">Ort</label>
                            <input type="text" id="ort" name="ort" placeholder="Ort eingeben...">
                        </div>
                        <div class="filter-item">
                            <label for="datum">Datum</label>
                            <input type="date" id="datum" name="datum">
                        </div>
                        <div class="filter-item">
                            <label for="bewertung">Bewertung</label>
                            <select id="bewertung" name="bewertung">
                                <option value="">Alle</option>
                                <option value="1">1 Stern</option>
                                <option value="2">2 Sterne</option>
                                <option value="3">3 Sterne</option>
                                <option value="4">4 Sterne</option>
                                <option value="5">5 Sterne</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label for="tags">Tags</label>
                            <input type="text" id="tags" name="tags" placeholder="Tags eingeben...">
                        </div>
                        <div class="filter-item">
                            <label for="sortierung">Sortierung</label>
                            <select id="sortierung" name="sortierung">
                                <option value="datum_aufsteigend">Datum (aufsteigend)</option>
                                <option value="datum_absteigend">Datum (absteigend)</option>
                                <option value="bewertung_aufsteigend">Bewertung (aufsteigend)</option>
                                <option value="bewertung_absteigend">Bewertung (absteigend)</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label for="anzahl">Anzahl der Einträge</label>
                            <select id="anzahl" name="anzahl">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter anwenden</button>
                    </div>
                </div>                
            </section>
            <section class="content">
                <div class="content-header">                    
                    <label for="sidebarToggleCheckbox" class="sidebar-toggle" id="sidebarToggle">
                        <span class="sidebar-toggle-arrow">&#8594</span>
                    </label>
                    <h2 class="m0 h2 fit-width">Einträge</h2>
                </div>
                <div class="preview-liste">
                    <?php for($x=0;$x<10;$x++)
                    {
                        include "php/eintrag_preview.php";
                    }
                    ?>
                </div>
            </section>
            
        </main>

    <?php include_once "php/footer.php" ?>
    </div>
</body>

</html>