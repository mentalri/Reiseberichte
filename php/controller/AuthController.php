<?php

namespace php\controller;
global $abs_path;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\profiles\Profile;
use php\model\profiles\Profiles;

require_once $abs_path . "/php/model/profiles/Profiles.php";

class AuthController {
    const LOCATION_INDEX_PHP = "Location: index.php";

    public function login(): void
    {
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            return;
        }
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        try {
            $profile = Profiles::getInstance()->getProfileByEmail($email);
            if (!password_verify($password, $profile->getPassword())) {
                $_SESSION["message"] = "invalid_password";
                return;
            }
            $_SESSION["user"] = $profile->getId();
            $_SESSION["message"] = "login_success";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_email";
        }
    }
    public function logout(): void
    {
        global $abs_path,$reports;
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        $_SESSION["message"] = "logout_success";
        require_once $abs_path . "/php/controller/IndexController.php";
        $indexController = new IndexController();
        $reports = $indexController->request();
        require_once $abs_path . "/php/view/index.php";
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
            $profilesDAO = Profiles::getInstance();
            // Check if username or email is already taken
            $profiles = $profilesDAO->getProfiles();
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
            $profile = $profilesDAO->addProfile($username, $email, $password);
            $_SESSION["message"] = "register_success";
            $_SESSION["user"] = $profile->getId();
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
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
            header(self::LOCATION_INDEX_PHP);
            exit;
        }
    }
    public function requestUser(): ?Profile
    {
        if($this->isLoggedIn()){
            try {
                return Profiles::getInstance()->getProfile($_SESSION["user"]);
            }catch (MissingEntryException){
                $_SESSION["message"] = "invalid_entry_id";
            } catch (InternalErrorException) {
                $_SESSION["message"] = "internal_error";
            }

        }
        return null;
    }
}
