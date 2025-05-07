<!DOCTYPE html>
<html lang="de" data-theme="bright">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Reiseberichte">
    <meta name="author" content="Thore Ritterhoff, Fanping Zhou-Schulze, Bayan Alhawari">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reiseberichte</title>
    <!-- Globale Styles -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/nav.css">
    
    <?php
    // Include the CSS files for the current page
    if (isset($cssFiles)) {
        foreach ($cssFiles as $cssFile) {
            echo '<link rel="stylesheet" href="' . $cssFile . '">';
        }
    }
    ?>
</head>