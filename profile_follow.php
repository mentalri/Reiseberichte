<?php
/**
 * Profile Follow Toggle Script (profile_follow.php)
 * Handles follow/unfollow functionality between users
 * Toggles the follow status and redirects back to the previous page
 */
global $abs_path;

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Ensure session is active for user authentication
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Load the profile controller and process the follow/unfollow action
require_once $abs_path . "/php/controller/ProfileController.php";
$profileController = new ProfileController();
$profileController->toggleFollow();  // Toggles follow status based on ID in $_GET

// Redirect back to the referring page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>