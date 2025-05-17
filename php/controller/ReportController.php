<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Travelreports.php";

/**
 * ReportController - Manages travel report operations
 * This class handles viewing, creating, editing, and deleting travel reports,
 * as well as related operations like comments and ratings
 */
class ReportController
{
    /**
     * Validates that a report ID exists and is numeric
     * Redirects with error if ID is invalid
     */
    private function checkId()
    {
        if (!isset($_REQUEST["id"]) || !is_numeric($_REQUEST["id"])) {
            $this->handleMissingEntryException();
        }
    }
    
    /**
     * Handles requests to view a specific travel report
     * 
     * @return Report The requested report object
     */
    public function request()
    {   
        $this->checkId();        
        try {
            // Process data for model layer interaction
            $id = intval($_GET["id"]);
            
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->getReport($id);

            return $report;
        } catch (MissingEntryException $exc) {
            // Handle business logic errors
            $_SESSION["message"] = "invalid_entry_id";
            header("Location: index.php");
            exit;
        }
    }
    
    /**
     * Retrieves a user's rating for a specific report
     * 
     * @param int $reportId The ID of the report
     * @param int $userId The ID of the user
     * @return Rating User's rating or an "illegal" rating object if none exists
     */
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
    
    /**
     * Loads the appropriate form for creating or editing a report
     * Verifies user authentication and ownership for edits
     */
    public function requestForm(){
        global $abs_path;
        // Check if user is logged in
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        $travelreports = Travelreports::getInstance();
        $profile = $travelreports->getProfile($_SESSION["user"]);
        
        // Determine if this is an edit or a new report
        if(isset($_GET["edit"]) && $_GET["edit"]=="true"){
            $this->checkId();
            $report = $travelreports->getReport($_GET["id"]);
            // Verify user is the author of the report
            if($report->getAuthor()->getId() != $profile->getId()){
                $_SESSION["message"] = "not_author";
                header("Location: index.php");
                exit;
            }
            require_once $abs_path . "/php/view/report_edit.php";
        } else {
            // Load new report form
            require_once $abs_path . "/php/view/report_new.php";
        }
    }
    
    /**
     * Common error handler for invalid report ID
     */
    private function handleMissingEntryException()
    {
        $_SESSION["message"] = "invalid_entry_id";
        header("Location: index.php");
        exit;
    }
    
    /**
     * Creates a new travel report with uploaded images
     */
    public function addReport()
    {
        global $abs_path;
        // Verify user is logged in
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }

        // Check for required fields
        if (!isset($_POST["title"]) || !isset($_POST["location"]) || !isset($_POST["description"])) {
            $_SESSION["message"] = "missing_entry";
            header("Location: index.php");
            exit;
        }

        // Set up file upload directories
        $uploadDir = $abs_path . "/uploads/reports/"; // Server file system path
        $uploadUrlPath = "uploads/reports/"; // Relative URL path for browser access

        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $imagePaths = [];

        // Process uploaded images
        if (isset($_FILES['pictures']) && is_array($_FILES['pictures']['tmp_name'])) {
            foreach ($_FILES['pictures']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['pictures']['error'][$key] === UPLOAD_ERR_OK) {
                    // Generate unique filename to prevent collisions
                    $originalName = basename($_FILES['pictures']['name'][$key]);
                    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                    $uniqueName = uniqid("img_", true) . '.' . $ext;
                    $destination = $uploadDir . $uniqueName;

                    // Move uploaded file to permanent location
                    if (move_uploaded_file($tmpName, $destination)) {
                        $imagePaths[] = $uploadUrlPath . $uniqueName;
                    }
                }
            }
        }

        try {
            // Create the report in the database
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->addReport(
                $travelreports->getProfile($_SESSION["user"]),
                time(), // Current timestamp
                $_POST["title"],
                $_POST["location"],
                $_POST["description"],
                $imagePaths // Array of image paths
            );
            header("Location: report.php?id=" . urlencode($report->getId()));
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    
    /**
     * Updates an existing travel report
     * Note: Implementation incomplete - missing editReport method
     */
    public function editReport()
    {
        // Verify user is logged in
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: index.php");
            exit;
        }
        
        // Check for required fields
        if (!isset($_POST["title"]) || !isset($_POST["location"]) || !isset($_POST["description"])) {
            $_SESSION["message"] = "missing_entry";
            header("Location: index.php");
            exit;
        }
        
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            # Note: Method for editing the report is missing
            # User must be the author of the report
            # $travelreports->editReport($_GET["id"], $_POST["title"], $_POST["location"], $_POST["description"], $_POST["pictures"]);
            header("Location: report.php?id=" . urlencode($_GET["id"]));
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    
    /**
     * Deletes a travel report
     * Verifies that the current user is the author of the report
     */
    public function deleteReport()
    {
        // Verify user is logged in
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit;
        }
        
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            $report = $travelreports->getReport($_GET["id"]);
            
            // Verify user is the author of the report
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
    
    /**
     * Adds a comment to a travel report
     */
    public function addComment()
    {
        // Verify user is logged in
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
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    
    /**
     * Deletes a comment from a travel report
     * Note: Implementation incomplete - missing comment deletion method
     */
    public function deleteComment()
    {
        // Verify user is logged in
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . urlencode($_GET["id"]));
            exit;
        }
        
        $this->checkId();
        try {
            $travelreports = Travelreports::getInstance();
            # Note: Comment deletion functionality is missing
            header("Location: report.php?id=" . urlencode($_GET["id"]));
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
    
    /**
     * Adds or updates a user's rating for a travel report
     */
    public function addRating()
    {
        $this->checkId();
        // Verify user is logged in
        if (!isset($_SESSION["user"])) {
            $_SESSION["message"] = "not_logged_in";
            header("Location: report.php?id=" . urlencode($_GET["id"]));
            exit;
        }
        
        try {
            $travelreports = Travelreports::getInstance();
            $travelreports->createRating($_GET["id"], $_SESSION["user"], $_POST["rating"]);
            header("Location: report.php?id=" . urlencode($_GET["id"]));
        } catch (InternalErrorException $exc) {
            $_SESSION["message"] = "internal_error";
        }
    }
}
?>