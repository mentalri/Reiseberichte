<?php
/**
 * Report Creation Script (report_add.php)
 * Processes new travel report submissions
 * Handles form data, image uploads, and database storage
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the report controller and process the report creation
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$reportController->addReport();  // Handles form data processing and report creation

// Terminate script execution after processing
// The controller handles redirection to the new report page
exit;
?>