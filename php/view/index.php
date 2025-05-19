<?php require_once $abs_path . "/php/include/head.php"; ?>
<!-- IntelliJ: <base href="/"> -->

<link rel="stylesheet" href="css/pages/index.css">
<link rel="stylesheet" href="css/preview.css">
</head>

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
                            <label for="location">Ort</label>
                            <input type="text" id="location" name="location" placeholder="Stadt, PLZ, Land" value="<?php if (isset($_GET["location"])) {echo htmlspecialchars($_GET["location"]);} ?>">
                        </div>
                        <div class="filter-item">
                            <label for="perimeter">Umkreis: <span>50km</span></label>
                            <input type="range" id="perimeter" name="perimeter" min="0" max="100" value="50" step="1">

                        </div>
                        <div class="filter-item">
                            <label for="rating">Bewertung</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?=$i?>" <?php if (isset($_GET["rating"]) && $_GET["rating"] == $i) {echo "checked";} ?>>
                                    <label for="star<?= $i ?>">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="filter-item">

                            <input type="checkbox" id="tags-toggle" class="tags-toggle" name="tags-toggle">
                                <div class="tags-box">
                                    <div class="tags">
                                        <?php
                                        $tags = ["Natur", "Stadt", "Kultur", "Essen", "Abenteuer"];
                                        foreach ($tags as $tag): ?>
                                        <div>
                                            <input type="checkbox" id="<?= $tag ?>" name="tags[]" value="<?= $tag ?>" <?php if (isset($_GET["tags"]) && in_array($tag, $_GET["tags"])) {echo "checked";} ?>>
                                            <label for="<?= $tag ?>"><?= $tag ?></label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <label for="tags-toggle" class="tags-button">Schließen</label>
                                </div>
                            <div class="tags-container">
                                <p class="m0">Tags</p>
                                <label for="tags-toggle" class="tags-button">Select Tags</label>
                            </div>
                        </div>
                        <div class="filter-item">
                            <label for="date">Zeitraum</label>
                            <div class="datum-container">
                                <div>
                                    <label for="date"><span>von</span></label><input type="date" id="date" name="date" value="<?php if (isset($_GET["date"])) {echo htmlspecialchars($_GET["date"]);} ?>">
                                </div>
                                <div>
                                    <label for="date2"><span>bis</span></label><input type="date" id="date2" name="date2" value="<?php if (isset($_GET["date2"])) {echo htmlspecialchars($_GET["date2"]);} ?>">
                                </div>
                            </div>
                        </div>
                        <div class="filter-item">
                            <label for="sorting">Sortierung</label>
                            <select id="sorting" name="sorting">
                                <option value="date_desc">Datum (absteigend)</option>
                                <option value="date_asc">Datum (aufsteigend)</option>
                                <option value="rating_desc">Bewertung (absteigend)</option>
                                <option value="rating_asc">Bewertung (aufsteigend)</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label for="count">Anzahl der Einträge</label>
                            <select id="count" name="count">
                                <option value="10" <?php if(isset($_GET["count"]) && $_GET["count"]==="10"){echo "selected";}?>>10</option>
                                <option value="20" <?php if(isset($_GET["count"]) && $_GET["count"]==="20"){echo "selected";}?>>20</option>
                                <option value="50" <?php if(isset($_GET["count"]) && $_GET["count"]==="50"){echo "selected";}?>>50</option>
                                <option value="100" <?php if(isset($_GET["count"]) && $_GET["count"]==="100"){echo "selected";}?>>100</option>
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