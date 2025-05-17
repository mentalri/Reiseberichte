<?php
/**
 * Login Controller Script (login.php)
 * Handles user authentication requests and renders the login form
 * Acts as a central point for processing login attempts
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the authentication controller and process login attempt
require_once $abs_path . "/php/controller/AuthController.php";
$authController = new AuthController();
$authController->login();  // Processes login form submission if POST data exists

// Display the login form (regardless of whether a login attempt was made)
require_once $abs_path . "/php/view/login.php";
?>