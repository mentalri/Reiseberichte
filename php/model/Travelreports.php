<?php
require_once "TravelReportsDummy.php";

class Travelreports{
    public static function getInstance(){
        return TravelreportsDummy::getInstance();
    }
}

?>