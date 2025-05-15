<?php
global $abs_path;
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/ProfileController.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$profileController = new ProfileController();
$profileController->toggleFollow();
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
?>