<?php

use php\controller\ProfileController;

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ProfileController.php";
$profileController = new ProfileController();
$profileController->deleteProfile();
