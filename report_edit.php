<?php
/**
 * Report Editing Script (report_edit.php)
 * Processes requests to update existing travel reports
 * Handles form data, image management, and database updates
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the report controller and process the edit request
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$reportController->editReport();  // Handles form data processing and report updating

// Terminate script execution after processing
// The controller handles redirection back to the updated report
exit;
?>