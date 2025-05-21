<?php
require_once $abs_path . "/php/include/head.php"
?>
<title><?= htmlspecialchars($report->getTitle()) ?></title>
<link rel="stylesheet" href="css/pages/bericht.css">
</head>
<body>
    <?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="container">
            <section class="bericht-container">

                <div class="img-container">
                    <img src="<?= htmlspecialchars($report->getPictures()[max(0,min($_GET["img"]??0,count($report->getPictures())-1))]) ?>" alt="Reisebild" class="bericht-bild">
                    <div class="left-button <?=($_GET["img"]??0)<=0?"hidden":""?>"><a href="report.php?id=<?=$report->getId()?>&img=<?=(($_GET["img"]??0)-1)?>"><img src="resources/links_icon.png" alt="Links"> </a></div>
                    <div class="right-button <?=($_GET["img"]??0)>=count($report->getPictures())-1?"hidden":""?>"><a href="report.php?id=<?=$report->getId()?>&img=<?=(($_GET["img"]??0)+1)?>"><img src="resources/rechts_icon.png" alt="Rechts"></a></div>
                </div>



                <div class="bericht-header">
                    <h2 class="bericht-titel"><?= htmlspecialchars($report->getTitle()) ?></h2>
                    <p><strong>Autor: </strong><a href="public_profile.php?id=<?=htmlspecialchars($report->getAuthor()->getId())?>"><?= htmlspecialchars($report->getAuthor()->getUsername()) ?></a></p>
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