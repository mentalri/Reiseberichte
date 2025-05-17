<?php
global $user_rating, $report, $abs_path;
require_once "path.php";
$cssFiles = array("pages/bericht.css", "special-elements/star-rating.css");
$phpFiles = ["css/carousel-style.php"];
require_once $abs_path . "/php/include/head.php"
?>

<body>
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="container">
            <section class="bericht-container">

                <div class="img-container carousel">
                    <div class="report-<?= $report->getId() ?>-slides">
                        <?php foreach ($report->getPictures() as $picture): ?>
                            <div class="report-<?= $report->getId() ?>-slide"><img src="<?= htmlspecialchars($picture) ?>" alt="Reisebild" class="bericht-bild"></div>
                        <?php endforeach; ?>
                    </div>
                </div>



                <div class="bericht-header">
                    <h2 class="bericht-titel"><?= htmlspecialchars($report->getTitle()) ?></h2>
                    <p><strong>Autor:</strong><?= htmlspecialchars($report->getAuthor()->getUsername()) ?></p>
                    <p><span class="icon">📍</span><?= htmlspecialchars($report->getLocation()) ?></p>
                    <p><span class="icon">📅</span><?= htmlspecialchars(date("d-m-Y", $report->getDate())) ?></p>
                    <p><span class="icon">⭐</span> <strong><?= htmlspecialchars($report->getRating()) ?></strong></p>
                </div>


                <div class="beschreibung">
                    <p><?= htmlspecialchars($report->getDescription()) ?></p>
                </div>


                <div class="bewertung-bereich">
                    <h3>Bewertung abgeben:</h3>
                    <form action="rating_add.php?id=<?= $report->getId() ?>" method="POST" class="bewertung-formular">
                        <button id="submit" name="submit" type="submit" value="Bewertung absenden">
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?php if ($user_rating->getRating() == $i) {echo "checked";} ?>>
                                    <label for="star<?= $i ?>">★</label>
                                <?php endfor; ?>
                            </div>
                        </button>
                    </form>
                </div>
                <div class="kommentarbereich">
                    <h3>Kommentare</h3>

                    <form action="comment_add.php?id=<?= $report->getId() ?>" class="kommentar-formular" method="POST">
                        <textarea id="comment" name="comment" placeholder="Dein Kommentar schreiben..." required></textarea>
                        <button type="submit">Kommentar absenden</button>
                    </form>
                    <?php $entry = $report;

                    require $abs_path . "/php/view/comment.php" ?>
                </div>
            </section>
        </main>
        <?php require_once  $abs_path . "/php/include/footer.php" ?>
    </div>

</body>

</html>