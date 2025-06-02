<?php
namespace php\controller;
global $abs_path;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\profiles\Profile;
use php\model\profiles\Profiles;
use php\model\reports\Reports;
use php\util\ImageUtils;

require_once $abs_path . "/php/model/profiles/Profiles.php";
require_once $abs_path . "/php/model/reports/Reports.php";
require_once $abs_path . "/php/controller/AuthController.php";
require_once $abs_path . "/php/util/ImageUtils.php";


class ProfileController
{
    const LOCATION_INDEX_PHP = "Location: index.php";
    const LOCATION_PROFILE_SIDE_KONTO = "Location: profile.php?side=konto";

    private function checkParameter($parameter): void
    {
        if (!isset($_REQUEST[$parameter])) {
            $_SESSION["message"] = "missing_parameter";
            header(self::LOCATION_INDEX_PHP);
            exit;
        }
    }

    public function request(): void
    {
        global $abs_path,$profile, $reports, $userReports, $userReportsMap, $following, $followers;
        $authController = new AuthController();
        $authController->requireLogin();
        $this->checkParameter("side");
        try {
            $profilesDAO = Profiles::getInstance();
            $reportsDAO = Reports::getInstance();
            switch ($_REQUEST["side"]) {
                case "konto":
                    $profile = $profilesDAO->getProfile($_SESSION["user"]);
                    $userReports = $this->requestUserReports($profile->getId());
                    require_once $abs_path . "/php/view/profile_konto.php";
                    break;
                case "reports":
                    $reports = $reportsDAO->getReports(null, null, null, null, null, null, null, null, 0, [$_SESSION["user"]]);
                    require_once $abs_path . "/php/view/profile_reports.php";
                    break;
                case "rated_reports":
                    $reports = $reportsDAO->getRatedReports($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_rated_reports.php";
                    break;
                case "friends":
                    $profile = $profilesDAO->getProfile($_SESSION["user"]);
                    $userReportsMap = [];
                    foreach ($profile->getFollowing() as $friend) {
                        $userReportsMap[$friend] = $this->requestUserReports($friend);
                    }
                    foreach ($profile->getFollowers() as $follower) {
                        if (!isset($userReportsMap[$follower])) {
                            $userReportsMap[$follower] = $this->requestUserReports($follower);
                        }
                    }
                    $following = array_map(
                        fn($id) => $profilesDAO->getProfile($id),
                        $profile->getFollowing()
                    );
                    $followers = array_map(
                        fn($id) => $profilesDAO->getProfile($id),
                        $profile->getFollowers()
                    );
                    require_once $abs_path . "/php/view/profile_friends.php";
                    break;
                case "nav":
                    require_once $abs_path . "/php/view/profile.php";
                    break;
                default:
                    $_SESSION["message"] = "invalid_side";
                    header(self::LOCATION_INDEX_PHP);
                    exit;
            }
            return;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }

    public function requestPublicProfile(): Profile
    {
        $this->checkParameter("id");
        try {
            return Profiles::getInstance()->getProfile($_GET["id"]);
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header($this->getRefPage());
            exit;
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
            header($this->getRefPage());
            exit;
        }
    }

    public function requestUserReports($id): array
    {
        try {
            return Reports::getInstance()->getReports(null, null, null, null, null, null, null, null, 0, [$id]);
        }catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header(self::LOCATION_INDEX_PHP);
            exit;
        }

    }

    public function toggleFollow(): void
    {
        $authController = new AuthController();
        $authController->requireLogin();
        $this->checkParameter("id");
        try {
            $profilesDAO = Profiles::getInstance();
            $currentUser = $profilesDAO->getProfile($_SESSION["user"]);
            $targetProfile = $profilesDAO->getProfile($_GET["id"]);
            if ($currentUser->getId() === $targetProfile->getId()) {
                $_SESSION["message"] = "cannot_follow_yourself";
                header($this->getRefPage());
                exit;
            }

            if ($currentUser->isFollowing($targetProfile)) {
                Profiles::getInstance()->unfollowProfile($currentUser->getId(), $targetProfile->getId());
                $_SESSION["message"] = "profile_unfollowed";
            } else {
                Profiles::getInstance()->followProfile($currentUser->getId(), $targetProfile->getId());
                $_SESSION["message"] = "profile_followed";
            }
        } catch (MissingEntryException) {
            $_SESSION["message"] = "profile_not_found";
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        }
    }

    public function uploadProfilePicture(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();
        $error = ImageUtils::handlePictureUpload($_FILES["profile_picture"]);
        if ($error !== null) {
            $_SESSION["message"] = $error;
            header($this->getRefPage());
            exit;
        }
        $uploadDir = $abs_path . "/uploads/profile_pictures/";
        $uploadUrlPath = "uploads/profile_pictures/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        $originalName = basename($_FILES['profile_picture']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $uniqueName = uniqid("img_", true) . '.' . $ext;
        $destination = $uploadDir . $uniqueName;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
            $imagePath = $uploadUrlPath . $uniqueName;
            try {
                $currentUser = Profiles::getInstance()->getProfile($_SESSION["user"]);
                Profiles::getInstance()->updateProfile($currentUser->getId(), $currentUser->getUsername(), $currentUser->getEmail(),
                    $currentUser->getPassword(), $imagePath, $currentUser->getDescription());
                $_SESSION["message"] = "profile_picture_uploaded";
            } catch (MissingEntryException) {
                $_SESSION["message"] = "profile_not_found";
            } catch (InternalErrorException) {
                $_SESSION["message"] = "internal_error";
            }
        } else {
            $_SESSION["message"] = "file_upload_error";
        }
        header($this->getRefPage());
        exit;
    }

    public function changeProfileDescription(): void
    {
        $authController = new AuthController();
        $authController->requireLogin();
        if(empty($_POST["description"])){
            $_SESSION["message"] = "missing_parameter";
            header(self::LOCATION_PROFILE_SIDE_KONTO);
            exit;
        }
        if (strlen(trim($_POST["description"])) > 256) {
            $_SESSION["message"] = "invalid_input_length";
            header(self::LOCATION_PROFILE_SIDE_KONTO);
            exit;
        }
        try {
            $currentUser = Profiles::getInstance()->getProfile($_SESSION["user"]);

            Profiles::getInstance()->updateProfile($currentUser->getId(), $currentUser->getUsername(), $currentUser->getEmail(),
                $currentUser->getPassword(), $currentUser->getProfilePicture(), $_POST["description"]);
            $_SESSION["message"] = "description_changed";
            header(self::LOCATION_PROFILE_SIDE_KONTO);
            exit;
        } catch (MissingEntryException) {
            $_SESSION["message"] = "profile_not_found";
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        }
    }


    public function updateLoginData(): Profile
    {
        $authController = new AuthController();
        $authController->requireLogin();
        try {
            $profilesDAO = Profiles::getInstance();
            $profile = $profilesDAO->getProfile($_SESSION["user"]);
            if (empty($_POST["username"]) || empty($_POST["email"])) {
                $_SESSION["message"] = "missing_parameter";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $password = null;
            if (!empty($_POST["new_password"])) {
                if(empty($_POST["repeat_password"]) || $_POST["new_password"] !== $_POST["repeat_password"]) {
                    $_SESSION["message"] = "invalid_password_repeat";
                    return $profile;
                }
                $password = $_POST["new_password"];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["message"] = "invalid_email";
                return $profile;
            }
            if (strlen($username) < 3 || strlen($username) > 12) {
                $_SESSION["message"] = "invalid_username";
                return $profile;
            }
            if (!empty($password) & (strlen($password) < 8 || strlen($password) > 12)) {
                $_SESSION["message"] = "invalid_password";
                return $profile;
            }
            $profiles = $profilesDAO->getProfiles();
            foreach ($profiles as $existingProfile) {
                if ($existingProfile->getEmail() === strtolower($email) && $existingProfile->getId() !== $profile->getId()) {
                    $_SESSION["message"] = "email_taken";
                     return $profile;
                }
                if ($existingProfile->getUsername() === $username && $existingProfile->getId() !== $profile->getId()) {
                    $_SESSION["message"] = "username_taken";
                    return $profile;
                }
            }
            if(empty($password)){
                $password = $profile->getPassword();
            }else {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }
            Profiles::getInstance()->updateProfile($profile->getId(), $username, $email, $password,
                $profile->getProfilePicture(), $profile->getDescription());
            $_SESSION["message"] = "profile_updated";
            header(self::LOCATION_PROFILE_SIDE_KONTO);
            exit;
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_profile_id";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header(self::LOCATION_INDEX_PHP);
            exit;
        }
    }
    public function deleteProfile(): void
    {
        $authController = new AuthController();
        $authController->requireLogin();
        try {

            Profiles::getInstance()->deleteProfile($_SESSION["user"]);
            unset($_SESSION["user"]);
            $_SESSION["message"] = "profile_deleted";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (MissingEntryException) {
            $_SESSION["message"] = "profile_not_found";
            header($this->getRefPage());
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header($this->getRefPage());
            exit;
        }
    }

    /**
     * @return string
     */
    public function getRefPage(): string
    {
        return "Location: " . ($_SERVER['HTTP_REFERER'] ?? self::LOCATION_INDEX_PHP);
    }
}
