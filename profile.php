<?php
/**
 * Profile Page Controller Script (profile.php)
 * Handles requests for various profile sections (account, reports, rated reports, friends)
 * Acts as a dispatcher that loads the appropriate profile view based on the 'side' parameter
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the profile controller and process the profile section request
require_once $abs_path . "/php/controller/ProfileController.php";
$profileController = new ProfileController();
$profileController->request();  // Loads and displays the requested profile section
?>