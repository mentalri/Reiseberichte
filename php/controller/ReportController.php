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


    public function requestForm(): void
    {
        global $abs_path;
        $authController = new AuthController();
        $authController->requireLogin();
        try {
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
        }catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: index.php");
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
                || !is_array(($_FILES['pictures']['tmp_name'])
                || count($_FILES['pictures']['tmp_name']) < 1)))
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
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->addReport(
                $travelreports->getProfile($_SESSION["user"]),
                time(),
                $_POST["title"],
                $_POST["location"],
                $_POST["description"],
                $imagePaths,
                ($_POST["tags"] ?? [])
            );
            header("Location: report.php?id=" . urlencode($report->getId()));
        } catch (MissingEntryException) {
            $_SESSION["message"] = "invalid_entry_id";
        }
    }
    public function editReport(): Report
    {
        $this->checkId();
        global $abs_path;


        try{
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->getReport($_GET["id"]);
            if($report->getAuthor()->getId()!=$_SESSION["user"]){
                $_SESSION["message"] = "not_author";
                header("Location: index.php");
                exit;
            }
            $imagePaths = $this->checkReportInputs(true);
            if ($imagePaths === null) {
                return $report;
            }
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
            $report->update(
                $_POST["title"],
                $_POST["location"],
                $_POST["description"],
                $imagePaths,
                ($_POST["tags"] ?? [])
            );
            $_SESSION["message"] = "edit_report";
            header("Location: report.php?id=" . urlencode($_GET["id"]));
            exit;
        }catch (MissingEntryException){
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: index.php");
            exit;
        }
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
            $error = $this->handlePictureUpload($file, $minWidth, $minHeight, $maxWidth, $maxHeight);
            if ($error !== null) {
                return $error;
            }
            // Update the name in the original files array if needed
            $files['name'][$key] = $file['name'];
        }

        return null; // All images are valid and processed
    }
    private function handlePictureUpload(&$file, $minWidth, $minHeight, $maxWidth, $maxHeight): ?string
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
}
