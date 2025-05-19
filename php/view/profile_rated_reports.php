<?php require_once $abs_path . "/php/include/head.php" ?>
<title>Bewertete Berichte</title>
<link rel="stylesheet" href="css/pages/profil.css">
<link rel="stylesheet" href="css/pages/profil-sidebar.css">
<link rel="stylesheet" href="css/preview.css">
</head>

<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php require_once $abs_path . "/php/include/profil_sidebar.php" ?>
            <section class="content flex-column flex-grow">
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