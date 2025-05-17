<?php
/**
 * Public Profile Controller Script (public_profile.php)
 * Handles requests to view another user's public profile
 * Retrieves profile data and passes it to the public profile view
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the profile controller and retrieve the requested public profile
require_once $abs_path . "/php/controller/ProfileController.php";
$profileController = new ProfileController();
$profile = $profileController->requestPublicProfile();  // Gets profile data based on ID parameter

// Include the public profile view template to render the page
require_once $abs_path . "/php/view/public_profile.php";
?>