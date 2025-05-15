<?php
if (!isset($abs_path)) {
    require_once "../../path.php";
}

require_once $abs_path . "/php/model/Report.php";
require_once $abs_path . "/php/model/Travelreports.php";

class IndexController
{
    public function request()
    {
        try {
            $page = $_GET["page"] ?? 0;
            #$search = $_GET["search"] ?? null;
            $location = $_GET["location"] ?? null;
            $perimeter = $_GET["perimeter"] ?? null;
            $rating = $_GET["rating"] ?? null;
            $tags = $_GET["tags"] ?? null;
            $date = $_GET["date"] ?? null;
            $date2 = $_GET["date2"] ?? null;
            $sorting = $_GET["sorting"] ?? null;
            $count = $_GET["count"] ?? null;
            return Travelreports::getInstance()
                ->getReports($location, $perimeter, $rating, $tags, $date, $date2, $sorting, $count, $page, null);
        } catch (InternalErrorException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "internal_error";
        }
    }
}
?>