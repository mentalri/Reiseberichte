<?php
/**
 * Comment Addition Script (comment_add.php)
 * Process handler for adding comments to travel reports
 * Acts as a controller endpoint that processes form submissions and redirects
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the report controller and process the comment submission
require_once $abs_path . "/php/controller/ReportController.php";
$reportController = new ReportController();
$reportController->addComment();  // Handles the comment creation process

// Terminate script execution after processing
// The controller handles redirection back to the report page
exit;
?>