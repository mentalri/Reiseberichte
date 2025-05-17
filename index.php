<?php
/**
 * Index Page Controller Script (index.php)
 * Main entry point for the application's homepage
 * Fetches travel reports using the IndexController and passes them to the view
 */

// Ensure session is active for user authentication and messages
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Set up absolute path reference if not already defined
if (!isset($abs_path)) {
    require_once "path.php";
}

// Load the index controller and retrieve filtered reports
require_once $abs_path . "/php/controller/IndexController.php";
$indexController = new IndexController();
$reports = $indexController->request();  // Gets reports based on filter parameters in $_GET

// Include the view template to render the page
require_once $abs_path . "/php/view/index.php";
?>