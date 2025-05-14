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
            $travelreports = Travelreports::getInstance();
            return $travelreports->getReports(null, null, null, null, null);
        } catch (InternalErrorException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "internal_error";
        }
    }
}
?>