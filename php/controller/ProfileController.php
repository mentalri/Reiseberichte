<?php 
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Profile.php";
require_once $abs_path . "/php/model/Travelreports.php";

class ProfileController
{
    private function checkParameter($parameter)
    {
        // Ueberpruefung der Parameter
        if (!isset($_REQUEST[$parameter])) {
            $_SESSION["message"] = "missing_parameter";
            exit;
        }
    }
    public function request()
    {
        global $abs_path;
        if(!isset($_SESSION["user"])){
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        // Ueberpruefung der Parameter
        $this->checkParameter("side");
        try {
            // Kontaktierung des Models (Geschaeftslogik)
            $travelreports = Travelreports::getInstance();

            // Aufbereitung der Daten fuer die Kontaktierung des Models
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
                default:
                    $_SESSION["message"] = "invalid_side";
                    header("Location: index.php");
                    exit;
            }
            return;
        } catch (InternalErrorException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "internal_error";
        } catch (MissingEntryException $e) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    public function toggleFollow()
    {
        if(!isset($_SESSION["user"])){
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        $this->checkParameter("id");
        try {
            $currentUser = Travelreports::getInstance()->getProfile($_SESSION["user_id"]);
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
}

?>