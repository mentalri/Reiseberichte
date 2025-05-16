<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

$cssFiles = array("pages/profil.css", "pages/profil_freunde.css", "pages/profil-sidebar.css");
?>
<?php require_once $abs_path . "/php/include/head.php" ?>

<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
<div class="page-wrapper flex-column">
    <?php require_once $abs_path . "/php/include/nav.php" ?>
    <main class="flex-row flex-grow">

    </main>
    <?php require_once $abs_path . "/php/include/footer.php" ?>
</div>
</body>

</html>