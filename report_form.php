<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ReportController.php";
require_once $abs_path . "/php/controller/AuthController.php";
$authController = new AuthController();
$authController->requireLogin();
$reportController = new ReportController();
$report = $reportController->requestForm();
?>