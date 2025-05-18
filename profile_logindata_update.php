<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ProfileController.php";
$profileController = new ProfileController();
$profile = $profileController->updateLoginData();
require_once $abs_path . "/php/view/profile_konto.php";
?>
