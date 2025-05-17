<?php
/**
 * Report Form Request Script (report_form.php)
 * Handles requests to display report creation/editing forms
 * Enforces login requirement and loads the appropriate form
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load required controllers
require_once $abs_path . "/php/controller/ReportController.php";
require_once $abs_path . "/php/controller/AuthController.php";

// Verify user is logged in before allowing access to form
$authController = new AuthController();
$authController->requireLogin();  // Redirects to login if user not authenticated

// Load appropriate report form (new or edit) based on parameters
$reportController = new ReportController();
$report = $reportController->requestForm();  // Determines which form to display
?>