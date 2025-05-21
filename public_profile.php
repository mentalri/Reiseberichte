<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ProfileController.php";
require_once $abs_path . "/php/controller/AuthController.php";
$profileController = new ProfileController();
$profile = $profileController->requestPublicProfile();
$authController = new AuthController();
$user = $authController->requestUser();
require_once $abs_path . "/php/view/public_profile.php";
?>