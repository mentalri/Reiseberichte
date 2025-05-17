<?php
/**
 * Logout Controller Script (logout.php)
 * Handles user logout requests
 * Terminates the user session and redirects to previous page
 */

// Ensure session is active to properly terminate it
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the authentication controller and process logout
require_once $abs_path . "/php/controller/AuthController.php";
$authController = new AuthController();
$authController->logout();  // Terminates user session and redirects

// Ensure script termination after logout processing
exit;
?>