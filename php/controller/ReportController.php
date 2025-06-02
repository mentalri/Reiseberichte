<?php
namespace php\controller;

global $abs_path;

use php\model\exceptions\InternalErrorException;
use php\model\exceptions\MissingEntryException;
use php\model\profiles\Profiles;
use php\model\reports\Rating;
use php\model\reports\Report;
use php\model\reports\Reports;
use php\util\ImageUtils;


require_once $abs_path . "/php/model/reports/Reports.php";
require_once $abs_path . "/php/model/profiles/Profiles.php";
require_once $abs_path . "/php/controller/AuthController.php";
require_once $abs_path . "/php/util/ImageUtils.php";


class ReportController
{
    const LOCATION_INDEX_PHP = "Location: index.php";
    const LOCATION_REPORT_ID = "Location: report.php?id=";

    private function checkId(): void
    {
        if (!isset($_REQUEST["id"]) || !is_numeric($_REQUEST["id"])) {
            $_SESSION["message"] = "invalid_entry_id";
            header($this->getRefPage());
            exit;
        }
    }
    public function request()
    {
        $this->checkId();
        try {
            $id = intval($_GET["id"]);
            return Reports::getInstance()->getReport($id);
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header(self::LOCATION_INDEX_PHP);
            exit;
        }
    }
    public function getUserRating($reportId, $userId)
    {
        if(!isset($_SESSION["user"])){
            return new Rating(-1,$reportId, null, -1); // Return an illegal rating object if user is not logged in
        }
        try {
            $report = Reports::getInstance()->getReport($reportId);
            foreach ($report->getRatings() as $rating) {
                if ($rating->getUser()->getId() == $userId) {
                    return $rating;
                }
            }
            return new Rating(-1,$reportId, null, -1); // Return an illegal rating object if no rating found
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header(self::LOCATION_INDEX_PHP);
            exit;
        }
    }


    public function requestForm(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();
        try {
            $profile=Profiles::getInstance()->getProfile($_SESSION["user"]);
            if(isset($_GET["edit"]) && $_GET["edit"]=="true"){
                $this->checkId();
                $report=Reports::getInstance()->getReport($_GET["id"]);
                if($report->getAuthor()->getId()!=$profile->getId()){
                    $_SESSION["message"] = "not_author";
                    header(self::LOCATION_INDEX_PHP);
                    exit;
                }
                require_once $abs_path . "/php/view/report_edit.php";
            }else{
                require_once $abs_path . "/php/view/report_new.php";
            }
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
    public function checkReportInputs($allowNoImages=false): null|array
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();

        if (!isset($_POST["title"]) || !isset($_POST["location"]) || !isset($_POST["description"])) {
            $_SESSION["message"] = "missing_entry";
            return null;
        }
        // Length checks
        $titleLen = mb_strlen(trim($_POST["title"]));
        $locationLen = mb_strlen(trim($_POST["location"]));
        $descLen = mb_strlen(trim($_POST["description"]));

        if ($titleLen < 5 || $titleLen > 50) {
            $_SESSION["message"] = "invalid_input_length";
            return null;
        }
        if ($locationLen < 5 || $locationLen > 50) {
            $_SESSION["message"] = "invalid_input_length";
            return null;
        }
        if ($descLen < 30 || $descLen > 2000) {
            $_SESSION["message"] = "invalid_input_length";
            return null;
        }
        if ($allowNoImages &&
            (!isset($_FILES['pictures'])
                || !is_array(($_FILES['pictures']['tmp_name']))
                || count($_FILES['pictures']['tmp_name']) < 1 || empty($_FILES['pictures']['tmp_name'][0])))
        {

            return [];
        }

        $error = $this->handleMultipleImageUploads($_FILES["pictures"]);
        if ($error !== null) {
            $_SESSION["message"] = $error;
            return null;
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
                    }else {
                        $_SESSION["message"] = "file_upload_error";
                    }
                }
            }
        }
        return $imagePaths;
    }
    public function addReport(): void
    {
        $imagePaths = $this->checkReportInputs();
        if ($imagePaths === null) {
            return;
        }
        $count = count($imagePaths);
        if ($count > 5) {
            $_SESSION["message"] = "too_many_files";
            return;
        }
        if ($count < 1) {
            $_SESSION["message"] = "no_files";
            return;
        }
        try {
            $report = Reports::getInstance()->addReport(
                Profiles::getInstance()->getProfile($_SESSION["user"]),
                time(),
                $_POST["title"],
                $_POST["location"],
                $_POST["description"],
                $imagePaths,
                ($_POST["tags"] ?? [])
            );
            header(self::LOCATION_REPORT_ID . urlencode($report->getId()));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function editReport(): Report
    {
        $this->checkId();
        global $abs_path;


        try{
            $report = Reports::getInstance()->getReport($_GET["id"]);
            if($report->getAuthor()->getId()!=$_SESSION["user"]){
                $_SESSION["message"] = "not_author";
                header(self::LOCATION_INDEX_PHP);
                exit;
            }
            $imagePaths = $this->checkReportInputs(true);
            if ($imagePaths === null) {
                return $report;
            }
            echo count($imagePaths);

            $deletePictures = $_POST["delete_pictures"] ?? [];
            foreach ($report->getPictures() as $picture) {
                if (!in_array($picture, $deletePictures)) {
                    $imagePaths[] = $picture;
                }
            }
            $count = count($imagePaths);
            if ($count > 5) {
                $_SESSION["message"] = "too_many_files";
                return $report;
            }
            if ($count < 1) {
                $_SESSION["message"] = "no_files";
                return $report;
            }

            foreach ($deletePictures as $picture) {
                if (file_exists($abs_path . "/" . $picture)) {
                    unlink($abs_path . "/" . $picture);
                }
            }
            Reports::getInstance()->updateReport(
                $report->getId(),
                $_POST["title"],
                $_POST["location"],
                $_POST["description"],
                $imagePaths,
                ($_POST["tags"] ?? [])
            );
            $_SESSION["message"] = "edit_report";
            header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
            exit;
        }catch (MissingEntryException){
            $_SESSION["message"] = "invalid_entry_id";
            header(self::LOCATION_INDEX_PHP);
            exit;
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
            header(self::LOCATION_INDEX_PHP);
            exit;
        }
    }
    public function deleteReport(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header($this->getRefPage());
            exit;
        }
        $this->checkId();
        try {
            $reportsDAO = Reports::getInstance();
            $report = $reportsDAO->getReport($_GET["id"]);
            if ($report->getAuthor()->getId() != $_SESSION["user"]) {
                $_SESSION["message"] = "not_author";
                header($this->getRefPage());
                exit;
            }
            $reportsDAO->deleteReport($_GET["id"]);
            $_SESSION["message"] = "report_deleted";
            header($this->getRefPage());
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function addComment(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
            exit;
        }
        
        $this->checkId();
        try {
            Reports::getInstance()->createComment($_GET["id"], $_SESSION["user"], $_POST["comment"]);
            header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        }
    }
    public function deleteComment(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
            exit;
        }
        
        $this->checkId();
        $reportsDAO = Reports::getInstance();
        #Löschung des Kommentars fehlt
        header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
    }
    public function addRating(): void
    {
        $this->checkId();
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
            exit;
        }
        try {
            Reports::getInstance()->createRating($_GET["id"], $_SESSION["user"], $_POST["rating"]);
            header(self::LOCATION_REPORT_ID . urlencode($_GET["id"]));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        } catch (InternalErrorException) {
            $_SESSION["message"] = "internal_error";
        }
    }
    private function handleMultipleImageUploads(&$files, $minWidth = 1024, $minHeight = 768, $maxWidth = 1920, $maxHeight = 1080): ?string
    {
        if (!isset($files['tmp_name']) || !is_array($files['tmp_name'])) {
            return "missing_files";
        }

        foreach ($files['tmp_name'] as $key => $tmpName) {
            $file = [
                'tmp_name' => $tmpName,
                'error' => $files['error'][$key],
                'name' => $files['name'][$key]
            ];
            $error = ImageUtils::handlePictureUpload($file, $minWidth, $minHeight, $maxWidth, $maxHeight);
            if ($error !== null) {
                return $error;
            }
            // Update the name in the original files array if needed
            $files['name'][$key] = $file['name'];
        }

        return null; // All images are valid and processed
    }

    /**
     * @return string
     */
    public function getRefPage(): string
    {
        return "Location: " . ($_SERVER["HTTP_REFERER"] ?? "index.php");
    }
}
