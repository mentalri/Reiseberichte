<?php

use JetBrains\PhpStorm\NoReturn;

if (!isset($abs_path)) {
    require_once "../../path.php";
}
require_once $abs_path . "/php/model/Profile.php";
require_once $abs_path . "/php/model/Travelreports.php";
class AuthController {
    public function login(): void
    {
        // Ueberpruefung der Parameter
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            return;
        }
        $email = $_POST["email"];
        $password = $_POST["password"];
        try {
            // Kontaktierung des Models (Geschaeftslogik)
            $travelreports = Travelreports::getInstance();
            $profile = $travelreports->getProfileByEmail($email);
            if ($profile == null) {
                $_SESSION["message"] = "invalid_email";
                return;
            }
            if (!password_verify($password, $profile->getPassword())) {
                $_SESSION["message"] = "invalid_password";
                return;
            }
            $_SESSION["user"] = $profile->getId();
            $_SESSION["message"] = "login_success";
            header("Location: index.php");
            exit;
        } catch (InternalErrorException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_email";
        }
    }
    public function logout(): void
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        $_SESSION["message"] = "logout_success";
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        exit;
    }
    public function register(): void
    {
        // Ueberpruefung der Parameter
        if (empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["username"]) || empty($_POST["password_repeat"])) {
            return;
        }
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_repeat = $_POST["password_repeat"];
        if ($password != $password_repeat) {
            $_SESSION["message"] = "invalid_password";
            return;
        }
        try {
            // Kontaktierung des Models (Geschaeftslogik)
            $travelreports = Travelreports::getInstance();
            $profile = $travelreports->addProfile($username, $email, password_hash($password, PASSWORD_DEFAULT));
            $_SESSION["message"] = "register_success";
            $_SESSION["user"] = $profile->getId();
            header("Location: index.php");
            exit;
        } catch (InternalErrorException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "internal_error";
        }
    }
    public function isLoggedIn(): bool
    {
        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }
    public function requireLogin(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: ".$_SERVER["HTTP_REFERER"]);
            exit;
        }
    }
}


?>