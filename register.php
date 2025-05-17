<?php
/**
 * Registration Controller Script (register.php)
 * Handles new user registration requests and renders the registration form
 * Acts as a central point for processing registration attempts
 */

// Ensure session is active for user messages and eventual authentication
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the authentication controller and process registration attempt
require_once $abs_path . "/php/controller/AuthController.php";
$authController = new AuthController();
$authController->register();  // Processes registration form submission if POST data exists

// Display the registration form (regardless of whether a registration attempt was made)
require_once $abs_path . "/php/view/register.php";
?>