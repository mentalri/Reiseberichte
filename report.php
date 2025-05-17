<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$report = $reportController->request();
$user_rating = $reportController->getUserRating($report->getId(), $_SESSION["user"] ?? -1);
require_once $abs_path . "/php/view/report.php";
?>