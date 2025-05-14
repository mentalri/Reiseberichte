<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}
?>
<?php
$cssFiles = array("pages/index.css", "preview.css");
$phpFiles = ["css/multiple-carousel-styles.php"];
?>

<?php require_once $abs_path . "/php/include/head.php"; ?>

<input type="checkbox" class="sidebar-toggle-checkbox" id="sidebarToggleCheckbox" />

<body class="flex-column">
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="container flex-row ">
            <section class="sidebar flex-shrink">
                <div class="sidebar-content">
                    <h2 class="m0 h2 fit-width">Filter</h2>
                    <form class="filter-container">
                        <div class="filter-item">
                            <label for="ort">Ort</label>
                            <input type="text" id="ort" name="ort" placeholder="Stadt, PLZ, Land">
                        </div>
                        <div class="filter-item">
                            <label for="umkreis">Umkreis: <span>50km</span></label>
                            <input type="range" id="umkreis" name="umkreis" min="0" max="100" value="50" step="1">

                        </div>
                        <div class="filter-item">
                            <label for="bewertung">Bewertung</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
                                    <label for="star<?= $i ?>">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="filter-item">
                            <p class="m0">Tags</p>
                            <button>Select Tags</button>
                        </div>
                        <div class="filter-item">
                            <label for="datum">Zeitraum</label>
                            <div class="datum-container">
                                <div>
                                    <span>von</span><input type="date" id="datum" name="datum">
                                </div>
                                <div>
                                    <span>bis</span><input type="date" id="datum2" name="datum2">
                                </div>
                            </div>
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
                        <button type="submit" class="submit-btn">Filter anwenden</button>
                    </form>
                </div>
            </section>
            <section class="content flex-column flex-grow">
                <div class="content-header flex-shrink">
                    <label for="sidebarToggleCheckbox" class="sidebar-toggle" id="sidebarToggle">
                        <span class="sidebar-toggle-arrow">&#8594;</span>
                    </label>
                    <h2 class="m0 fit-width">Einträge</h2>
                </div>
                <div class="preview-liste flex-grow">
                    <?php foreach ($reports as $report) {
                        include $abs_path . "/php/view/eintrag_preview.php";
                    }
                    ?>
                </div>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>

</html>