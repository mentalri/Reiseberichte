<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Travelreports.php";

/**
 * IndexController - Handles requests for travel reports
 * This class manages the retrieval of travel reports based on various filter criteria
 */
class IndexController {
    /**
     * Process request for travel reports with optional filtering parameters
     * Returns an array of reports based on search criteria from GET parameters
     * 
     * @return ?array Array of reports or null if an error occurs
     */
    public function request(): ?array
    {
        try {
            // Extract filter parameters from GET request with default values
            $page = $_GET["page"] ?? 0;                  // Pagination page number (default: 0)
            #$search = $_GET["search"] ?? null;          // Search term (commented out/unused)
            $location = $_GET["location"] ?? null;       // Location filter
            $perimeter = $_GET["perimeter"] ?? null;     // Distance/perimeter around location
            $rating = $_GET["rating"] ?? null;           // Minimum rating filter
            $tags = $_GET["tags"] ?? null;               // Tags filter
            $date = $_GET["date"] ?? null;               // Start date filter
            $date2 = $_GET["date2"] ?? null;             // End date filter
            $sorting = $_GET["sorting"] ?? "date_desc";  // Sort order (default: newest first)
            $count = $_GET["count"] ?? 10;               // Number of results per page (default: 10)
            
            // Call the model layer to get filtered reports
            return Travelreports::getInstance()
                ->getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, null);
        } catch (InternalErrorException $exc) {
            // Handle potential business logic errors
            $_SESSION["message"] = "internal_error";
            return null;
        }
    }
}
?>