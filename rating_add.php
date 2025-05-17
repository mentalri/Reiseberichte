<?php
/**
 * Rating Addition Script (rating_add.php)
 * Process handler for adding/updating ratings for travel reports
 * Acts as a controller endpoint for rating submission
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the report controller and process the rating submission
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$reportController->addRating();  // Handles the rating creation/update process
?>