<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($abs_path)) {
    require_once "path.php";
}
require_once $abs_path . "/php/controller/IndexController.php";
$indexController = new IndexController();
$reports = $indexController->request();
require_once $abs_path . "/php/view/index.php";
?>