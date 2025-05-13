<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Travelreports.php";

class ReportController
{   private function checkId()
    {
        if (!isset($_REQUEST["id"]) || !is_numeric($_REQUEST["id"])) {
            $this->handleMissingEntryException();
        }
    }
    public function request()
    {   
        $this->checkId();        
        try {
            // Aufbereitung der Daten fuer die Kontaktierung des Models
            $id = intval($_GET["id"]);
            
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->getReport($id);

            return $report;
        } catch (MissingEntryException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: index.php");
            exit;
        }
    }
    public function getUserRating($reportId, $userId)
    {
        if(!isset($_SESSION["user"])){
            return new Rating(-1, null, -1); // Return an illegal rating object if user is not logged in
        }
        try {
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->getReport($reportId);
            foreach ($report->getRatings() as $rating) {
                if ($rating->getUser()->getId() == $userId) {
                    return $rating;
                }
            }
            return new Rating(-1, null, -1); // Return an illegal rating object if no rating found
        } catch (MissingEntryException $exc) {
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: index.php");
            exit;
        }
    }
    public function requestForm(){
        global $abs_path;
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        $travelreports = Travelreports::getInstance();
        $profile=$travelreports->getProfile($_SESSION["user"]);
        if(isset($_GET["edit"]) && $_GET["edit"]=="true"){
            $this->checkId();
            $report=$travelreports->getReport($_GET["id"]);
            if($report->getAuthor()->getId()!=$profile->getId()){
                $_SESSION["message"] = "not_author";
                header("Location: index.php");
                exit;
            }
            require_once $abs_path . "/php/view/report_edit.php";
        }else{
            require_once $abs_path . "/php/view/report_new.php";
        }
    }
    private function handleMissingEntryException()
    {
        $_SESSION["message"] = "invalid_entry_id";
        header("Location: index.php");
        exit;
    }
    public function addReport()
    {
        global $abs_path;
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }

        if (!isset($_POST["title"]) || !isset($_POST["location"]) || !isset($_POST["description"])) {
            $_SESSION["message"] = "missing_entry";
            header("Location: index.php");
            exit;
        }

        $uploadDir = $abs_path . "/uploads/reports/"; // Adjust to your structure
        $uploadUrlPath = "uploads/reports/"; // Relative URL path to access via browser

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $imagePaths = [];

        if (isset($_FILES['pictures']) && is_array($_FILES['pictures']['tmp_name'])) {
            foreach ($_FILES['pictures']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['pictures']['error'][$key] === UPLOAD_ERR_OK) {
                    $originalName = basename($_FILES['pictures']['name'][$key]);
                    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                    $uniqueName = uniqid("img_", true) . '.' . $ext;
                    $destination = $uploadDir . $uniqueName;

                    if (move_uploaded_file($tmpName, $destination)) {
                        $imagePaths[] = $uploadUrlPath . $uniqueName;
                    }
                }
            }
        }

        try {
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->addReport(
                $travelreports->getProfile($_SESSION["user"]),
                time(),
                $_POST["title"],
                $_POST["location"],
                $_POST["description"],
                $imagePaths // pass array of image paths here
            );
            header("Location: report.php?id=" . $report->getId());
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function editReport()
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        if (!isset($_POST["title"]) || !isset($_POST["location"]) || !isset($_POST["description"])) {
            $_SESSION["message"] = "missing_entry";
            header("Location: index.php");
            exit;
        }
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            #Methode zum Bearbeiten des Reports fehlt
            #User muss Author des Reports sein
            #$travelreports->editReport($_GET["id"], $_POST["title"], $_POST["location"], $_POST["description"],$_POST["pictures"]);
            header("Location: report.php?id=" . $_GET["id"]);
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function deleteReport()
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        }
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->getReport($_GET["id"]);
            if ($report->getAuthor()->getId() != $_SESSION["user"]) {
                $_SESSION["message"] = "not_author";
                header("Location: " . $_SERVER["HTTP_REFERER"]);
                exit;
            }
            $travelreports->deleteReport($_GET["id"]);
            $_SESSION["message"] = "report_deleted";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function addComment()
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . $_GET["id"]);
            exit;
        }
        
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            $travelreports->createComment($_GET["id"], $_SESSION["user"], $_POST["comment"]);
            header("Location: report.php?id=" . $_GET["id"]);
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function deleteComment()
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . $_GET["id"]);
            exit;
        }
        
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            #Löschung des Kommentars fehlt
            header("Location: report.php?id=" . $_GET["id"]);
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function addRating()
    {
        $this->checkId();
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . $_GET["id"]);
            exit;
        }
        try {
            $travelreports = Travelreports::getInstance();
            $travelreports->createRating($_GET["id"], $_SESSION["user"], $_POST["rating"]);
            header("Location: report.php?id=" . $_GET["id"]);
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    
}
?>