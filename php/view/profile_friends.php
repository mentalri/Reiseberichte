
<?php require_once $abs_path . "/php/include/head.php" ?>
<title>Freunde</title>
<link rel="stylesheet" href="css/pages/profil.css">
<link rel="stylesheet" href="css/pages/profil_freunde.css">
<link rel="stylesheet" href="css/pages/profil-sidebar.css">
</head>
<body class="flex-column">
<?php require_once $abs_path . "/php/include/feedback.php" ?>
    <div class="page-wrapper flex-column">
        <?php require_once $abs_path . "/php/include/nav.php" ?>
        <main class="flex-row flex-grow">
            <?php require_once $abs_path . "/php/include/profil_sidebar.php" ?>
            <section class="content flex-grow">
                <div class="flex-row flex-grow">
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile den ich folge</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php foreach ($profile->getFollowing() as $follower){
                                include $abs_path . "/php/view/profile_preview.php";
                            } ?>
                        </div>
                    </div>
                    <div class="flex-column flex-grow profil-list">
                        <div class="flex-shrink">
                            <h2>Profile die mir folgen</h2>
                        </div>
                        <div class="flex-column flex-grow">
                            <?php foreach ($profile->getFollowers() as $follower){
                                include $abs_path . "/php/view/profile_preview.php";
                            } ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php require_once $abs_path . "/php/include/footer.php" ?>
    </div>
</body>

</html>