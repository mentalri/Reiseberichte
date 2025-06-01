<?php

use php\controller\ReportController;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$report = $reportController->editReport();
require_once $abs_path . "/php/view/report_edit.php";
