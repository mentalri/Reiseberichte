<?php
/**
 * Report Display Controller Script (report.php)
 * Handles requests to view individual travel reports
 * Retrieves report data, user's rating, and passes to the view
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the report controller and retrieve the requested report
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$report = $reportController->request();  // Gets report data based on ID parameter

// Retrieve the current user's rating for this report (if any)
// Uses -1 as user ID if not logged in to return an "illegal" rating object
$user_rating = $reportController->getUserRating(
    $report->getId(), 
    $_SESSION["user"] ?? -1
);

// Include the report view template to render the page
require_once $abs_path . "/php/view/report.php";
?>