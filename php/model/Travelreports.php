<?php
require_once "TravelreportsDummy.php";

class Travelreports{
    public static function getInstance(){
        return TravelreportsDummy::getInstance();
    }
}

?>