<?php

use php\controller\ProfileController;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ProfileController.php";
$profileController = new ProfileController();
$profile = $profileController->updateLoginData();
$userReports = $profileController->requestUserReports($profile->getId());
require_once $abs_path . "/php/view/profile_konto.php";
