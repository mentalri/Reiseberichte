<?php
/**
 * Report Deletion Script (report_delete.php)
 * Processes requests to delete travel reports
 * Verifies user ownership and handles the deletion process
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the report controller and process the deletion request
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$reportController->deleteReport();  // Verifies ownership and deletes the report

// Terminate script execution after processing
// The controller handles redirection back to appropriate page
exit;
?>