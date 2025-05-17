<?php
require_once "TravelreportsDummy.php";

/**
 * Travelreports - Factory/proxy class for data access
 * Implements Singleton pattern and delegates to TravelreportsDummy implementation
 */
class Travelreports {
    /**
     * Static factory method that provides access to the data layer
     * Currently returns a dummy implementation for development/testing
     * 
     * @return TravelreportsDummy The singleton instance of data access object
     */
    public static function getInstance() {
        return TravelreportsDummy::getInstance();
    }
}
?>