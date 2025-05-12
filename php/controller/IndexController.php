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
        // Ueberpruefung der Parameter, Hinweis: Es gibt keine Parameter
        try {

            // Aufbereitung der Daten fuer die Kontaktierung des Models
            // Hinweis: Es werden keine Daten benoetigt

            // Kontaktierung des Models (Geschaeftslogik)
            $travelreports = Travelreports::getInstance();
            $reports = $travelreports->getReports(null, null, null, null, null);

            // Aufbereitung der Daten fuer die Ausgabe (View), Hinweis: in diesem Fall nichts zu tun
            return $reports;
        } catch (InternalErrorException $exc) {
            // Behandlung von potentiellen Fehlern der Geschaeftslogik
            $_SESSION["message"] = "internal_error";
        }
    }
}
?>