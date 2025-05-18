<?php 
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Profile.php";
require_once $abs_path . "/php/model/Travelreports.php";
require_once $abs_path . "/php/controller/AuthController.php";

class ProfileController
{
    private function checkParameter($parameter): void
    {
        if (!isset($_REQUEST[$parameter])) {
            $_SESSION["message"] = "missing_parameter";
            exit;
        }
    }

    public function request(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();
        $this->checkParameter("side");
        try {
            $travelreports = Travelreports::getInstance();
            switch ($_REQUEST["side"]) {
                case "konto":
                    $profile = $travelreports->getProfile($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_konto.php";
                    break;
                case "reports":
                    $reports = $travelreports->getReports(null, null, null, null, null, null, null, null, 0, [$_SESSION["user"]]);
                    require_once $abs_path . "/php/view/profile_reports.php";
                    break;
                case "rated_reports":
                    $reports = $travelreports->getRatedReports($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_rated_reports.php";
                    break;
                case "friends":
                    $profile = $travelreports->getProfile($_SESSION["user"]);
                    require_once $abs_path . "/php/view/profile_friends.php";
                    break;
                case "nav":
                    require_once $abs_path . "/php/view/profile.php";
                    break;
                default:
                    $_SESSION["message"] = "invalid_side";
                    header("Location: index.php");
                    exit;
            }
            return;
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }

    public function requestPublicProfile(): Profile
    {
        $this->checkParameter("id");
        try {
            return Travelreports::getInstance()->getProfile($_GET["id"]);
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function toggleFollow(): void
    {
        $authController = new AuthController();
        $authController->requireLogin();
        $this->checkParameter("id");
        try {
            $currentUser = Travelreports::getInstance()->getProfile($_SESSION["user"]);
            $targetProfile = Travelreports::getInstance()->getProfile($_GET["id"]);

            if ($currentUser->isFollowing($targetProfile)) {
                $currentUser->unfollow($targetProfile);
            } else {
                $currentUser->follow($targetProfile);
            }
        } catch (MissingEntryException $exc) {
            $_SESSION["message"] = "profile_not_found";
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }

    public function uploadProfilePicture(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();
        $error = $this->handleProfilePictureUpload($_FILES["profile_picture"]);
        if ($error !== null) {
            $_SESSION["message"] = $error;
            header("Location: " . $_SERVER['HTTP_REFERER']);
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
                $currentUser = Travelreports::getInstance()->getProfile($_SESSION["user"]);
                $currentUser->setProfilePicture($imagePath);
                $_SESSION["message"] = "profile_picture_uploaded";
            } catch (MissingEntryException $exc) {
                $_SESSION["message"] = "profile_not_found";
            } catch (InternalErrorException $exc) {
                $_SESSION["message"] = "internal_error";
            }
        } else {
            $_SESSION["message"] = "file_upload_error";
        }
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    }
    private function handleProfilePictureUpload(&$file, $minWidth = 128, $minHeight = 128, $maxWidth = 256, $maxHeight = 256): ?string
    {
        if (!isset($file) || $file["error"] != UPLOAD_ERR_OK) {
            return "missing_file";
        }

        $imageInfo = getimagesize($file["tmp_name"]);
        if ($imageInfo === false) {
            return "invalid_image";
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        if ($width < $minWidth || $height < $minHeight) {
            return "invalid_image_size";
        }

        // Load image resource
        switch ($mime) {
            case 'image/jpeg':
                $srcImage = imagecreatefromjpeg($file["tmp_name"]);
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($file["tmp_name"]);
                break;
            case 'image/gif':
                $srcImage = imagecreatefromgif($file["tmp_name"]);
                break;
            default:
                return "unsupported_image_type";
        }

        // Scale down if needed
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
            $dstImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG and GIF
            if ($mime === 'image/png' || $mime === 'image/gif') {
                imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
                imagealphablending($dstImage, false);
                imagesavealpha($dstImage, true);
            }

            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($srcImage);
            $srcImage = $dstImage;
        }

        // Save as WebP (overwrite tmp file)
        imagewebp($srcImage, $file["tmp_name"]);
        imagedestroy($srcImage);

        //update the file extension to .webp for later saving
        $file['name'] = pathinfo($file['name'], PATHINFO_FILENAME) . '.webp';

        return null; // No error
    }
    public function changeProfileDescription(): void
    {
        $authController = new AuthController();
        $authController->requireLogin();
        if(empty($_POST["description"])){
            $_SESSION["message"] = "missing_parameter";
            header("Location: profile.php?side=konto");
            exit;
        }
        if (strlen($_POST["description"]) > 256) {
            $_SESSION["message"] = "invalid_input_length";
            header("Location: profile.php?side=konto");
            exit;
        }
        try {
            $currentUser = Travelreports::getInstance()->getProfile($_SESSION["user"]);
            $currentUser->setDescription($_POST["description"]);
            $_SESSION["message"] = "description_changed";
            header("Location: profile.php?side=konto");
            exit;
        } catch (MissingEntryException) {
            $_SESSION["message"] = "profile_not_found";
        }
    }


    public function updateLoginData(): Profile
    {
        $authController = new AuthController();
        $authController->requireLogin();
        try {
            $travelreports = Travelreports::getInstance();
            $profile = $travelreports->getProfile($_SESSION["user"]);
            if (empty($_POST["username"]) || empty($_POST["email"])) {
                $_SESSION["message"] = "missing_parameter";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $profile->getPassword();
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
            if (strlen($password) < 8 || strlen($password) > 12) {
                $_SESSION["message"] = "invalid_password";
                return $profile;
            }
            $profiles = $travelreports->getProfiles();
            foreach ($profiles as $existingProfile) {
                if ($existingProfile->getEmail() === strtolower($email)) {
                    $_SESSION["message"] = "email_taken";
                     return $profile;
                }
                if ($existingProfile->getUsername() === $username) {
                    $_SESSION["message"] = "username_taken";
                    return $profile;
                }
            }
            $profile->updateProfile($username, $email, $password);
            header("Location: profile.php?side=konto");
            exit;
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_profile_id";
            header("Location: index.php");
            exit;
        }
    }
    public function deleteProfile(): void
    {
        $authController = new AuthController();
        $authController->requireLogin();
        try {
            $travelreports = Travelreports::getInstance();
            $travelreports->deleteProfile($_SESSION["user"]);
            unset($_SESSION["user"]);
            $_SESSION["message"] = "profile_deleted";
            header("Location: index.php");
            exit;
        } catch (MissingEntryException) {
            $_SESSION["message"] = "profile_not_found";
        }
    }
}

?>