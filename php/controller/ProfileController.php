<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Profile.php";
require_once $abs_path . "/php/model/Travelreports.php";

/**
 * ProfileController - Handles user profile operations and views
 * This class manages profile-related requests including account management,
 * report listings, rated reports, friends/following, and public profile views
 */
class ProfileController {
    /**
     * Validates that a required parameter exists in the request
     * Terminates execution with error message if parameter is missing
     * 
     * @param string $parameter The parameter name to check
     * @return void
     */
    private function checkParameter($parameter): void
    {
        // Check if the parameter exists in the request
        if (!isset($_REQUEST[$parameter])) {
            $_SESSION["message"] = "missing_parameter";
            exit;
        }
    }
    
    /**
     * Handles profile section requests based on the 'side' parameter
     * Loads different profile views depending on the requested section
     * 
     * @return void
     */
    public function request(): void
    {
        global $abs_path;
        
        // Check if user is logged in
        if(!isset($_SESSION["user"])){
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        
        // Validate required parameters
        $this->checkParameter("side");
        
        try {
            // Connect to model layer (business logic)
            $travelreports = Travelreports::getInstance();
            
            // Process different profile sections based on the 'side' parameter
            switch ($_REQUEST["side"]) {
                case "konto":
                    // Load account settings view
                    $profile = $travelreports->getProfile($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_konto.php";
                    break;
                case "reports":
                    // Load user's own reports view
                    $reports = $travelreports->getReports(null, null, null, null, null, null, null, null, 0, [$_SESSION["user"]]);
                    require_once $abs_path . "/php/view/profile_reports.php";
                    break;
                case "rated_reports":
                    // Load reports rated by the user
                    $reports = $travelreports->getRatedReports($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_rated_reports.php";
                    break;
                case "friends":
                    // Load user's friends/following view
                    $profile = $travelreports->getProfile($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_friends.php";
                    break;
                default:
                    // Handle invalid section request
                    $_SESSION["message"] = "invalid_side";
                    header("Location: index.php");
                    exit;
            }
            return;
        } catch (InternalErrorException $exc) {
            // Handle potential business logic errors
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    
    /**
     * Retrieves public profile information for a specified user ID
     * 
     * @return Profile The requested user profile
     */
    public function requestPublicProfile(): Profile
    {
        $this->checkParameter("id");
        
        try {
            // Get profile for the requested user ID
            return Travelreports::getInstance()->getProfile($_GET["id"]);
        } catch (InternalErrorException $exc) {
            // Handle internal error exceptions
            $_SESSION["message"] = "internal_error";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } catch (MissingEntryException $e) {
            // Handle case when profile doesn't exist
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    
    /**
     * Toggles follow/unfollow status for a user
     * Follows if not following, unfollows if already following
     * 
     * @return void
     */
    public function toggleFollow(): void
    {
        // Check if user is logged in
        if(!isset($_SESSION["user"])){
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        
        // Validate required parameters
        $this->checkParameter("id");
        
        try {
            // Get current user profile
            $currentUser = Travelreports::getInstance()->getProfile($_SESSION["user_id"]);
            // Get target profile to follow/unfollow
            $targetProfile = Travelreports::getInstance()->getProfile($_GET["id"]);
            
            // Toggle follow status
            if ($currentUser->isFollowing($targetProfile)) {
                $currentUser->unfollow($targetProfile);
            } else {
                $currentUser->follow($targetProfile);
            }
        } catch (MissingEntryException $exc) {
            // Handle case when profile doesn't exist
            $_SESSION["message"] = "profile_not_found";
        } catch (InternalErrorException $exc) {
            // Handle internal error exceptions
            $_SESSION["message"] = "internal_error";
        }
    }
}
?>