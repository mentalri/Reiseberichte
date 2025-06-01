<?php

use php\controller\AuthController;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/AuthController.php";
$authController = new AuthController();
$authController->register();
require_once $abs_path . "/php/view/register.php";
