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
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            return;
        }
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        try {
            $travelreports = Travelreports::getInstance();
            $profile = $travelreports->getProfileByEmail($email);
            if (!password_verify($password, $profile->getPassword())) {
                $_SESSION["message"] = "invalid_password";
                return;
            }
            $_SESSION["user"] = $profile->getId();
            $_SESSION["message"] = "login_success";
            header("Location: index.php");
            exit;
        } catch (InternalErrorException $exc) {
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
        header("Location: ". $_SERVER["HTTP_REFERER"] ?? "index.php");
        exit;
    }
    public function register(): void
    {
        if (empty($_POST["email"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["password_repeat"])) {
            return;
        }
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $password_repeat = trim($_POST["password_repeat"]);
        if ($password !== $password_repeat) {
            $_SESSION["message"] = "invalid_password_repeat";
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["message"] = "invalid_email";
            return;
        }
        if (strlen($username) < 3 || strlen($username) > 12) {
            $_SESSION["message"] = "invalid_username";
            return;
        }
        if (strlen($password) < 8 || strlen($password) > 12) {
            $_SESSION["message"] = "invalid_password";
            return;
        }

        try {
            $travelreports = Travelreports::getInstance();
            // Check if username or email is already taken
            $profiles = $travelreports->getProfiles();
            foreach ($profiles as $existingProfile) {
                if ($existingProfile->getEmail() === strtolower($email)) {
                    $_SESSION["message"] = "email_taken";
                    return;
                }
                if ($existingProfile->getUsername() === $username) {
                    $_SESSION["message"] = "username_taken";
                    return;
                }
            }
            $profile = $travelreports->addProfile($username, $email, password_hash($password, PASSWORD_DEFAULT));
            $_SESSION["message"] = "register_success";
            $_SESSION["user"] = $profile->getId();
            header("Location: index.php");
            exit;
        } catch (InternalErrorException $exc) {
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
            header("Location: ". $_SERVER["HTTP_REFERER"] ?? "index.php");
            exit;
        }
    }
    public function requestUser(): ?Profile
    {
        if($this->isLoggedIn()){
            try {
                $travelreports = Travelreports::getInstance();
                return $travelreports->getProfile($_SESSION["user"]);
            }catch (MissingEntryException){
                $_SESSION["message"] = "invalid_entry_id";
            }

        }
        return null;
    }
}


?>