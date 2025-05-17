<?php
use JetBrains\PhpStorm\NoReturn;

if (!isset($abs_path)) {
    require_once "../../path.php";
}
require_once $abs_path . "/php/model/Profile.php";
require_once $abs_path . "/php/model/Travelreports.php";

/**
 * AuthController - Handles authentication processes like login, logout, and registration
 * This class manages user authentication for the travel reports application
 */
class AuthController {
    /**
     * Handles user login process
     * Validates email and password, then creates a session if valid
     */
    public function login(): void
    {
        // Check if required parameters (email and password) are provided
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            return;
        }
        
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        try {
            // Connect to model layer (business logic)
            $travelreports = Travelreports::getInstance();
            $profile = $travelreports->getProfileByEmail($email);
            
            // Check if email exists in the system
            if ($profile == null) {
                $_SESSION["message"] = "invalid_email";
                return;
            }
            
            // Verify password using secure password_verify function
            if (!password_verify($password, $profile->getPassword())) {
                $_SESSION["message"] = "invalid_password";
                return;
            }
            
            // Set session variables upon successful login
            $_SESSION["user"] = $profile->getId();
            $_SESSION["message"] = "login_success";
            header("Location: index.php");
            exit;
        } catch (InternalErrorException $exc) {
            // Handle potential business logic errors
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_email";
        }
    }
    
    /**
     * Handles user logout process
     * Removes the user session and redirects to the previous page
     */
    public function logout(): void
    {
        // Remove user session if it exists
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        
        // Set success message and redirect to previous page
        $_SESSION["message"] = "logout_success";
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        exit;
    }
    
    /**
     * Handles user registration process
     * Creates a new user account after validating input
     */
    public function register(): void
    {
        // Check if all required parameters are provided
        if (empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["username"]) || empty($_POST["password_repeat"])) {
            return;
        }
        
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_repeat = $_POST["password_repeat"];
        
        // Verify that passwords match
        if ($password != $password_repeat) {
            $_SESSION["message"] = "invalid_password";
            return;
        }
        
        try {
            // Connect to model layer (business logic)
            $travelreports = Travelreports::getInstance();
            
            // Add new profile with securely hashed password
            $profile = $travelreports->addProfile($username, $email, password_hash($password, PASSWORD_DEFAULT));
            
            // Set session variables upon successful registration
            $_SESSION["message"] = "register_success";
            $_SESSION["user"] = $profile->getId();
            header("Location: index.php");
            exit;
        } catch (InternalErrorException $exc) {
            // Handle potential business logic errors
            $_SESSION["message"] = "internal_error";
        }
    }
    
    /**
     * Checks if a user is currently logged in
     * @return bool True if user is logged in, false otherwise
     */
    public function isLoggedIn(): bool
    {
        if (isset($_SESSION["user"])) {
            return true;
        }
        return false;
    }
    
    /**
     * Forces login for protected pages
     * Redirects to previous page with message if not logged in
     */
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