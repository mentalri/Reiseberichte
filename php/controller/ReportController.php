<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Travelreports.php";
require_once $abs_path . "/php/controller/AuthController.php";

class ReportController
{
    private function checkId(): void
    {
        if (!isset($_REQUEST["id"]) || !is_numeric($_REQUEST["id"])) {
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        }
    }
    public function request()
    {   
        $this->checkId();        
        try {
            $id = intval($_GET["id"]);
            
            $travelreports = Travelreports::getInstance();
            return $travelreports->getReport($id);
        } catch (MissingEntryException) {
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
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: index.php");
            exit;
        }
    }

    /**
     * @throws MissingEntryException
     */
    public function requestForm(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();
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

    public function addReport(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();

        if (!isset($_POST["title"]) || !isset($_POST["location"]) || !isset($_POST["description"])) {
            $_SESSION["message"] = "missing_entry";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        }
        $error = $this->handleMultipleImageUploads($_FILES["pictures"]);
        if ($error !== null) {
            $_SESSION["message"] = $error;
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        }

        $uploadDir = $abs_path . "/uploads/reports/";
        $uploadUrlPath = "uploads/reports/";

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
                $imagePaths
            );
            header("Location: report.php?id=" . urlencode($report->getId()));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    public function editReport(): void
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
        $travelreports = Travelreports::getInstance();
        #Methode zum Bearbeiten des Reports fehlt
        #User muss Author des Reports sein
        #$travelreports->editReport($_GET["id"], $_POST["title"], $_POST["location"], $_POST["description"],$_POST["pictures"]);
        header("Location: report.php?id=" . urlencode($_GET["id"]));
    }
    public function deleteReport(): void
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
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    public function addComment(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . urlencode($_GET["id"]));
            exit;
        }
        
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            $travelreports->createComment($_GET["id"], $_SESSION["user"], $_POST["comment"]);
            header("Location: report.php?id=" . urlencode($_GET["id"]));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    public function deleteComment(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . urlencode($_GET["id"]));
            exit;
        }
        
        $this->checkId();
        $travelreports = Travelreports::getInstance();
        #Löschung des Kommentars fehlt
        header("Location: report.php?id=" . urlencode($_GET["id"]));
    }
    public function addRating(): void
    {
        $this->checkId();
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . urlencode($_GET["id"]));
            exit;
        }
        try {
            $travelreports = Travelreports::getInstance();
            $travelreports->createRating($_GET["id"], $_SESSION["user"], $_POST["rating"]);
            header("Location: report.php?id=" . urlencode($_GET["id"]));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    public function handleMultipleImageUploads($files, $minWidth = 100, $minHeight = 100, $maxWidth = 2000, $maxHeight = 2000): ?string
    {
        if (!isset($files['tmp_name']) || !is_array($files['tmp_name'])) {
            return "missing_files";
        }

        foreach ($files['tmp_name'] as $key => $tmpName) {
            if (!isset($files['error'][$key]) || $files['error'][$key] != UPLOAD_ERR_OK) {
                return "missing_file";
            }

            $imageInfo = getimagesize($tmpName);
            if ($imageInfo === false) {
                return "invalid_image";
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];
            if ($width < $minWidth || $height < $minHeight || $width > $maxWidth || $height > $maxHeight) {
                return "invalid_image_size";
            }
        }

        return null; // All images are valid
    }
}
